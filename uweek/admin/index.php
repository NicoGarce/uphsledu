<?php
/**
 * UWeek Admin – University Week 2026 Admin App
 * Own folder: /uweek/admin/ – does NOT import main site header/footer
 * Keeps UPHSL branding, adds Back to Home and Share for shortened results
 */
require_once '../../app/config/paths.php';
require_once '../../app/config/database.php';
require_once '../../app/includes/functions.php';

if (!isLoggedIn() || (!isSuperAdmin() && !isAdmin())) {
    redirect('../../auth/login.php');
}
$base_path = $GLOBALS['base_path'] ?? '/uphsledu/';
$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];
$pdo = getDBConnection();

$page_title = 'University Week 2026 Admin';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        if ($action === 'toggle_category') {
            $catId = (int)($_POST['category_id'] ?? 0);
            $enabled = isset($_POST['is_enabled']) ? 1 : 0;
            if ($catId > 0) {
                $stmt = $pdo->prepare("UPDATE uweek_categories SET is_enabled = ? WHERE id = ?");
                $stmt->execute([$enabled, $catId]);
                $success = 'Category visibility updated.';
            }
        } elseif ($action === 'toggle_event') {
            $evId = (int)($_POST['event_id'] ?? 0);
            $enabled = isset($_POST['is_enabled']) ? 1 : 0;
            if ($evId > 0) {
                $stmt = $pdo->prepare("UPDATE uweek_events SET is_enabled = ? WHERE id = ?");
                $stmt->execute([$enabled, $evId]);
                $success = 'Event visibility updated.';
            }
        } elseif ($action === 'update_event') {
            $evId = (int)($_POST['event_id'] ?? 0);
            $title = Validator::sanitize($_POST['title'] ?? '', 'string');
            $code = Validator::sanitize($_POST['code'] ?? '', 'string');
            $challonge_url = trim($_POST['challonge_url'] ?? '');
            $embed_url = trim($_POST['challonge_embed_url'] ?? '');
            // Allow raw HTML for custom embed (iframes, styled text) – store as-is, do not strip tags
            $embed_html = trim($_POST['embed_html'] ?? '');
            $description = Validator::sanitize($_POST['description'] ?? '', 'string');
            $is_enabled = isset($_POST['event_enabled']) ? 1 : 0;
            $sort_order = (int)($_POST['sort_order'] ?? 0);
            if ($evId <= 0) { $error = 'Invalid event ID.'; }
            elseif ($title === '') { $error = 'Event title is required.'; }
            else {
                if ($challonge_url !== '' && !filter_var($challonge_url, FILTER_VALIDATE_URL)) $error = 'Challonge URL must be a valid URL.';
                elseif ($embed_url !== '' && !filter_var($embed_url, FILTER_VALIDATE_URL)) $error = 'Embed URL must be a valid URL.';
                else {
                    if ($embed_url === '' && $challonge_url !== '' && strpos($challonge_url, 'challonge.com') !== false) {
                        $tmp = rtrim($challonge_url, '/');
                        if (strpos($tmp, '/module') === false) $tmp .= (strpos($tmp, '?')===false ? '/module' : str_replace('?','/module?',$tmp));
                        $embed_url = $tmp;
                    }
                    $stmt = $pdo->prepare("UPDATE uweek_events SET title = ?, code = ?, challonge_url = ?, challonge_embed_url = ?, embed_html = ?, description = ?, is_enabled = ?, sort_order = ? WHERE id = ?");
                    $ok = $stmt->execute([$title,$code,$challonge_url?:null,$embed_url?:null,$embed_html ?: null,$description?:null,$is_enabled,$sort_order,$evId]);
                    $success = $ok ? 'Event updated successfully.' : 'Failed to update event.';
                    if (!$ok) $error = 'Failed to update event.';
                }
            }
        } elseif ($action === 'update_category') {
            $catId = (int)($_POST['category_id'] ?? 0);
            $label = Validator::sanitize($_POST['label'] ?? '', 'string');
            $is_enabled = isset($_POST['cat_enabled']) ? 1 : 0;
            $sort_order = (int)($_POST['sort_order'] ?? 0);
            if ($catId > 0 && $label !== '') {
                $stmt = $pdo->prepare("UPDATE uweek_categories SET label = ?, is_enabled = ?, sort_order = ? WHERE id = ?");
                $stmt->execute([$label,$is_enabled,$sort_order,$catId]);
                $success = 'Category updated.';
            } else $error = 'Invalid category data.';
        } elseif ($action === 'bulk_toggle_events') {
            $catId = (int)($_POST['category_id'] ?? 0);
            $enable = ($_POST['bulk'] ?? 'enable') === 'enable' ? 1 : 0;
            if ($catId > 0) {
                $stmt = $pdo->prepare("UPDATE uweek_events SET is_enabled = ? WHERE category_id = ?");
                $stmt->execute([$enable,$catId]);
                $success = $enable ? 'All events in category enabled.' : 'All events in category disabled.';
            }
        }
    }
}

$categories = $pdo->query("SELECT * FROM uweek_categories ORDER BY sort_order ASC, id ASC")->fetchAll();
$stats = [
    'total_cats' => count($categories),
    'total_events' => (int)$pdo->query("SELECT COUNT(*) FROM uweek_events")->fetchColumn(),
    'enabled_events' => (int)$pdo->query("SELECT COUNT(*) FROM uweek_events WHERE is_enabled=1")->fetchColumn(),
    'disabled_events' => (int)$pdo->query("SELECT COUNT(*) FROM uweek_events WHERE is_enabled=0")->fetchColumn(),
];
$grouped = [];
foreach ($categories as $cat) {
    $stmt = $pdo->prepare("SELECT * FROM uweek_events WHERE category_id = ? ORDER BY sort_order ASC, id ASC");
    $stmt->execute([$cat['id']]);
    $evs = $stmt->fetchAll();
    $grouped[] = ['cat'=>$cat,'events'=>$evs];
}
$publicBase = $base_path . 'uweek/';
$shortBase = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . $base_path . 'uweek/?e=';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?> – UPHSL</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;700;800&family=Inter:wght@500;600;700&family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
<style>
:root{--primary:#1c4da1;--primary-dark:#0f2a6b;--primary-light:#2a5cc8;--gold:#ffc63e;--gold-dark:#e6b12e;--bg:#f1f5f9;--card:#fff;--line:#e2e8f0;--line-strong:#cbd5e1;--text:#0f172a;--muted:#64748b;--radius:16px;--radius-sm:10px;}
*{box-sizing:border-box}
html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-family:'Inter',system-ui,sans-serif;-webkit-font-smoothing:antialiased}
a{color:inherit}
/* Admin nav – tournament, compact on tablet/mobile */
.uweek-admin-nav{position:sticky;top:0;z-index:30;background:linear-gradient(90deg,var(--primary-dark) 0%,var(--primary) 55%,#1e3a8a 100%);color:#fff;border-bottom:3px solid var(--gold);box-shadow:0 6px 20px rgba(15,42,107,.22);}
.uweek-admin-nav-inner{max-width:1320px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;gap:14px;justify-content:space-between;}
.uweek-admin-brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:#fff;min-width:0;flex-shrink:0;}
.uweek-admin-brand img{width:38px;height:38px;object-fit:contain;background:transparent;border-radius:0;padding:0;filter:drop-shadow(0 1px 3px rgba(0,0,0,.25));}
.uweek-admin-brand strong{font-family:'Barlow Semi Condensed',sans-serif;font-weight:800;letter-spacing:.3px;text-transform:uppercase;font-size:.98rem;line-height:1;}
.uweek-admin-brand span{font-size:.68rem;opacity:.9;font-weight:600;letter-spacing:.25px;text-transform:uppercase;}
.uweek-admin-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:flex-end;}
.btn-admin{appearance:none;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.12);color:#fff;padding:7px 12px;border-radius:999px;font-weight:700;font-size:.74rem;text-decoration:none;cursor:pointer;transition:.15s;white-space:nowrap;backdrop-filter:blur(6px);}
.btn-admin:hover{background:rgba(255,255,255,.22);transform:translateY(-1px)}
.btn-admin-primary{background:var(--gold);color:#0f172a;border-color:var(--gold-dark);box-shadow:0 3px 12px rgba(255,198,62,.32);font-weight:800;}
.btn-admin-primary:hover{background:#ffda5e}
.uweek-admin-user{font-size:.74rem;opacity:.9;white-space:nowrap;background:rgba(0,0,0,.18);padding:6px 10px;border-radius:999px;border:1px solid rgba(255,255,255,.12)}
@media(max-width:1024px){.uweek-admin-nav-inner{padding:10px 14px;gap:10px} .uweek-admin-actions{gap:6px}}
@media(max-width:900px){.uweek-admin-nav{position:relative} .uweek-admin-nav-inner{flex-wrap:wrap} .uweek-admin-actions{width:100%;justify-content:flex-start}}
@media(max-width:640px){.uweek-admin-nav-inner{padding:9px 12px} .uweek-admin-brand strong{font-size:.86rem} .uweek-admin-brand img{width:32px;height:32px} .btn-admin{padding:6px 9px;font-size:.70rem} .uweek-admin-user{display:none}}
/* Shell */
.uweek-admin-wrap{max-width:1320px;margin:0 auto;padding:22px 16px 40px;}
@media(max-width:768px){.uweek-admin-wrap{padding:16px 12px 32px}}
.dash-head{display:flex;align-items:flex-start;gap:14px;justify-content:space-between;flex-wrap:wrap;margin-bottom:18px;background:var(--card);border:1px solid var(--line);border-radius:var(--radius);padding:16px;box-shadow:0 4px 14px rgba(0,0,0,.05)}
.dash-head h1{margin:0;font-family:'Barlow Semi Condensed',sans-serif;font-weight:800;letter-spacing:.3px;text-transform:uppercase;color:var(--primary);font-size:1.55rem;line-height:1;}
.dash-head p{margin:6px 0 0;color:var(--muted);font-size:.82rem;line-height:1.5;max-width:640px}
@media(max-width:640px){.dash-head{padding:14px} .dash-head h1{font-size:1.25rem}}
/* Stats – tournament cards */
.stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:18px;}
@media(max-width:1200px){.stats-grid{grid-template-columns:repeat(3,1fr);gap:10px}}
@media(max-width:900px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr 1fr;gap:10px}}
.live-dot{width:9px;height:9px;background:#16a34a;border-radius:50%;box-shadow:0 0 0 6px rgba(22,163,74,.18);animation:livePulse 1.6s infinite;display:inline-block;vertical-align:middle;margin-right:6px}
@keyframes livePulse{0%{box-shadow:0 0 0 0 rgba(22,163,74,.28)}70%{box-shadow:0 0 0 7px rgba(22,163,74,0)}100%{box-shadow:0 0 0 0 rgba(22,163,74,0)}}
.stat-card{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 10px rgba(0,0,0,.04);transition:.15s;position:relative;overflow:hidden}
.stat-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--primary);opacity:.9}
.stat-card:nth-child(3)::before{background:#10b981}
.stat-card:nth-child(4)::before{background:#ef4444}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.08);border-color:var(--line-strong)}
.stat-icon{width:42px;height:42px;border-radius:10px;display:grid;place-items:center;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;flex-shrink:0;font-size:1rem;box-shadow:0 3px 10px rgba(28,77,161,.22)}
.stat-content h3{margin:0;font-size:1.35rem;font-weight:800;color:var(--text);font-family:'Barlow Semi Condensed',sans-serif;line-height:1}
.stat-content p{margin:2px 0 0;font-size:.70rem;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:var(--muted);}
/* Alerts */
.alert{padding:11px 12px;border-radius:10px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-weight:600;font-size:.82rem;border:1px solid transparent}
.alert-error{background:#fef2f2;color:#991b1b;border-color:#fecaca}
.alert-success{background:#f0fdf4;color:#166534;border-color:#bbf7d0}
/* Category card – cleaner */
.cat-details{background:var(--card);border:1px solid var(--line);border-radius:14px;overflow:hidden;margin-bottom:14px;box-shadow:0 3px 12px rgba(0,0,0,.05);transition:.15s}
.cat-details[open]{box-shadow:0 6px 20px rgba(0,0,0,.07);border-color:var(--line-strong)}
.cat-details summary{list-style:none;cursor:pointer;padding:14px 16px;display:flex;align-items:center;gap:12px;justify-content:space-between;flex-wrap:wrap;background:linear-gradient(180deg,#fff 0%,#f8fafc 100%);transition:.15s}
.cat-details summary::-webkit-details-marker{display:none}
.cat-details summary:hover{background:#f8fafc}
@media(max-width:640px){.cat-details summary{padding:12px 14px;gap:10px}}
/* Table – elegant + mobile cards */
.data-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.82rem}
.data-table th{background:var(--primary-dark);color:#fff;padding:11px 10px;text-align:left;font-family:'Barlow Semi Condensed',sans-serif;letter-spacing:.5px;text-transform:uppercase;font-size:.70rem;font-weight:700;white-space:nowrap;position:sticky;top:0;z-index:1}
.data-table th:first-child{border-radius:0}
.data-table td{padding:10px 10px;border-bottom:1px solid #f1f5f9;vertical-align:top;background:var(--card)}
.data-table tbody tr:hover td{background:#f8fafc}
.data-table tbody tr:last-child td{border-bottom:none}
@media(max-width:900px){
  .data-table{font-size:.80rem}
  .data-table th,.data-table td{padding:8px 7px}
}
@media(max-width:720px){
  .data-table{border:0}
  .data-table thead{display:none}
  .data-table, .data-table tbody, .data-table tr, .data-table td{display:block;width:100%}
  .data-table tr{background:var(--card);border:1px solid var(--line);border-radius:12px;margin-bottom:10px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.04)}
  .data-table td{border:none;border-bottom:1px solid #f1f5f9;padding:10px 12px;display:flex;align-items:center;justify-content:space-between;gap:12px}
  .data-table td:last-child{border-bottom:none}
  .data-table td::before{content:attr(data-label);font-weight:800;font-size:.68rem;letter-spacing:.3px;text-transform:uppercase;color:var(--muted);flex-shrink:0;min-width:72px}
  .data-table td[data-label="Visible"]::before{content:"Visible"}
  .data-table td[data-label="Code"]::before{content:"Code"}
  .data-table td[data-label="Event"]::before{content:"Event"}
  .data-table td[data-label="Challonge"]::before{content:"Link"}
  .data-table td[data-label="Embed?"]::before{content:"Embed"}
  .data-table td[data-label="Actions"]::before{content:"Actions"}
}
.btn{appearance:none;border:0;cursor:pointer;display:inline-flex;align-items:center;gap:6px;padding:7px 11px;border-radius:9px;font-weight:700;font-size:.74rem;text-decoration:none;transition:.15s;white-space:nowrap;letter-spacing:.1px}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;box-shadow:0 2px 8px rgba(28,77,161,.18)}
.btn-primary:hover{filter:brightness(1.05);transform:translateY(-1px)}
.btn-secondary{background:#475569;color:#fff}
.btn-secondary:hover{background:#334155}
.btn-sm{padding:6px 9px;font-size:.70rem;border-radius:8px}
.switch{position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0;vertical-align:middle}
.switch.small{width:40px;height:20px}
.switch input{opacity:0;width:0;height:0}
.slider{position:absolute;inset:0;background:#cbd5e1;border-radius:999px;transition:.2s;cursor:pointer;box-shadow:inset 0 1px 3px rgba(0,0,0,.08)}
.slider:before{content:"";position:absolute;height:18px;width:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 4px rgba(0,0,0,.18)}
.switch.small .slider:before{height:14px;width:14px;top:3px;left:3px}
input:checked + .slider{background:var(--primary)}
input:checked + .slider:before{transform:translateX(18px)}
.switch.small input:checked + .slider:before{transform:translateX(18px)}
.modal{display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);backdrop-filter:blur(4px);z-index:50;overflow:auto;padding:16px}
.modal-content{background:#fff;margin:3% auto;padding:0;border-radius:14px;max-width:640px;width:100%;box-shadow:0 16px 40px rgba(0,0,0,.24);overflow:hidden;border:1px solid var(--line);animation:modalIn .18s ease}
@keyframes modalIn{from{opacity:0;transform:translateY(8px) scale(.98)} to{opacity:1;transform:translateY(0) scale(1)}}
.modal-header{padding:14px 16px;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;display:flex;align-items:center;justify-content:space-between}
.modal-header h3{margin:0;font-family:'Barlow Semi Condensed',sans-serif;letter-spacing:.2px;font-size:1rem}
.modal-body{padding:16px}
.modal-actions{padding:12px 16px;border-top:1px solid var(--line);display:flex;gap:8px;justify-content:flex-end;background:#f8fafc}
.close{color:#fff;font-size:22px;cursor:pointer;line-height:1;opacity:.9}
.close:hover{opacity:1}
.form-group{margin-bottom:12px}
.form-group label{display:block;font-weight:700;font-size:.76rem;margin-bottom:5px;color:var(--text);letter-spacing:.1px}
.form-group input,.form-group textarea,.form-group select{width:100%;padding:9px 11px;border:1.5px solid var(--line);border-radius:9px;font-size:.84rem;font-family:inherit;background:#fff;transition:.15s}
.form-group input:focus,.form-group textarea:focus,.form-group select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(28,77,161,.12);background:#fff}
#uweekToast{position:fixed;left:50%;bottom:18px;transform:translateX(-50%) translateY(16px);background:#0f172a;color:#fff;padding:11px 14px;border-radius:999px;font-weight:700;font-size:.78rem;opacity:0;pointer-events:none;transition:.2s;z-index:60;box-shadow:0 10px 28px rgba(0,0,0,.22);border:1px solid rgba(255,255,255,.08)}
#uweekToast.show{opacity:1;transform:translateX(-50%) translateY(0)}
</style>
</head>
<body>
<nav class="uweek-admin-nav" aria-label="University Week 2026 Admin navigation">
  <div class="uweek-admin-nav-inner">
    <a href="<?php echo $base_path; ?>uweek/" class="uweek-admin-brand" aria-label="University Week 2026 Home">
      <img src="<?php echo $base_path; ?>assets/images/Logos/logo.png" alt="UPHSL">
      <span><strong>University Week 2026</strong><br><span>Admin</span></span>
    </a>
    <div class="uweek-admin-actions">
      <a href="<?php echo $base_path; ?>" class="btn-admin">Back to Website</a>
      <a href="<?php echo $base_path; ?>uweek/" class="btn-admin">View University Week 2026</a>
      <button class="btn-admin btn-admin-primary" type="button" onclick="shareAdmin()">Share Results</button>
      <span class="uweek-admin-user"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?> • <?php echo htmlspecialchars($userRole); ?></span>
      <a href="<?php echo $base_path; ?>auth/logout.php" class="btn-admin">Logout</a>
    </div>
  </div>
</nav>

<div class="uweek-admin-wrap">
  <?php echo CSRF::field(); ?>
  <div class="dash-head">
    <div>
      <h1>University Week 2026 Admin</h1>
      <p>Enable/disable tabs & manage Challonge embeds — <a href="../" style="color:var(--primary);font-weight:700;">View Public Page</a> • Short links use <code style="background:#fff;border:1px solid var(--line);padding:1px 5px;border-radius:6px;">?e=event-slug</code></p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button class="btn btn-secondary" onclick="document.querySelectorAll('.cat-details').forEach(d=>d.open=true)">Expand All</button>
      <button class="btn btn-secondary" onclick="document.querySelectorAll('.cat-details').forEach(d=>d.open=false)">Collapse All</button>
    </div>
  </div>

  <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <div class="stats-grid">
    <div class="stat-card"><div class="stat-icon"><i class="fas fa-layer-group"></i></div><div class="stat-content"><h3><?php echo $stats['total_cats']; ?></h3><p>Categories</p></div></div>
    <div class="stat-card"><div class="stat-icon"><i class="fas fa-list"></i></div><div class="stat-content"><h3><?php echo $stats['total_events']; ?></h3><p>Total Events</p></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:linear-gradient(135deg,#10b981,#059669)"><i class="fas fa-eye"></i></div><div class="stat-content"><h3><?php echo $stats['enabled_events']; ?></h3><p>Enabled</p></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)"><i class="fas fa-eye-slash"></i></div><div class="stat-content"><h3><?php echo $stats['disabled_events']; ?></h3><p>Disabled</p></div></div>
  </div>

  <div style="background:var(--card);border:1px solid var(--line);border-radius:12px;padding:12px 14px;margin-bottom:14px;display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:space-between;">
    <div style="color:var(--muted);font-size:.84rem;">Toggle a <strong>Category</strong> to show/hide its entire tab. Toggle <strong>Events</strong> for each bracket. Use <strong>Copy Short Link</strong> to share <code>?e=slug</code> links.</div>
  </div>

  <?php foreach ($grouped as $g): $cat=$g['cat']; $events=$g['events']; $enabledCount=count(array_filter($events, fn($e)=>(int)$e['is_enabled']===1)); ?>
  <details class="cat-details" open style="background:var(--card);border:1px solid var(--line);border-radius:12px;overflow:hidden;margin-bottom:14px;box-shadow:0 2px 10px rgba(0,0,0,.04);">
    <summary style="list-style:none;cursor:pointer;padding:14px 16px;display:flex;align-items:center;gap:12px;justify-content:space-between;flex-wrap:wrap;background:linear-gradient(135deg,#f8fafc,#fff);">
      <div style="display:flex;align-items:center;gap:12px;min-width:0;">
        <div style="width:38px;height:38px;border-radius:10px;display:grid;place-items:center;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;flex-shrink:0;"><i class="fas <?php echo htmlspecialchars($cat['icon'] ?: 'fa-trophy'); ?>"></i></div>
        <div style="min-width:0;">
          <div style="font-family:'Barlow Semi Condensed',sans-serif;font-weight:800;color:var(--primary);font-size:1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($cat['label']); ?></div>
          <div style="font-size:.78rem;color:var(--muted);"><?php echo htmlspecialchars($cat['name']); ?> • <strong><?php echo $enabledCount; ?>/<?php echo count($events); ?></strong> enabled</div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
        <button type="button" class="btn btn-sm" style="background:<?php echo (int)$cat['is_enabled']===1 ? '#10b981' : '#ef4444'; ?>;color:#fff;border:none;padding:6px 12px;border-radius:6px;font-weight:700;font-size:.70rem;cursor:pointer;" onclick="toggleCategory(<?php echo $cat['id']; ?>, <?php echo (int)$cat['is_enabled']; ?>, this)">
          <?php echo (int)$cat['is_enabled']===1 ? 'Enabled' : 'Disabled'; ?>
        </button>
        <span style="background:#f1f5f9;padding:4px 8px;border-radius:999px;font-size:.72rem;font-weight:700;"><?php echo count($events); ?> events</span>
      </div>
    </summary>
    <div style="border-top:1px solid var(--line);padding:10px 12px;background:#f8fafc;display:flex;gap:8px;flex-wrap:wrap;justify-content:space-between;">
      <div style="display:flex;gap:8px;">
        <button class="btn btn-primary btn-sm" onclick="bulkToggle(<?php echo $cat['id']; ?>, 'enable', this)">Enable All</button>
        <button class="btn btn-secondary btn-sm" onclick="bulkToggle(<?php echo $cat['id']; ?>, 'disable', this)">Disable All</button>
      </div>
      <button class="btn" style="background:#fff;border:1px solid var(--line);" onclick="openCatEdit(<?php echo $cat['id']; ?>, <?php echo htmlspecialchars(json_encode($cat['label']), ENT_QUOTES); ?>, <?php echo (int)$cat['is_enabled']; ?>, <?php echo (int)$cat['sort_order']; ?>)">Edit Category</button>
    </div>
    <div style="overflow-x:auto;">
      <table class="data-table" style="min-width:860px;">
        <thead><tr><th style="width:70px;">Visible</th><th style="width:70px;">Code</th><th>Event</th><th style="width:26%;">Challonge URL</th><th style="width:10%;">Embed?</th><th style="width:160px;">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($events as $ev): $hasCustom = !empty(trim($ev['embed_html'] ?? '')); $hasUrl=!empty($ev['challonge_url'])||!empty($ev['challonge_embed_url'])||$hasCustom;
              $shortUrl = $shortBase . urlencode($ev['slug']); ?>
          <tr>
            <td data-label="Visible">
              <button type="button" class="btn btn-sm" style="background:<?php echo (int)$ev['is_enabled']===1 ? '#10b981' : '#ef4444'; ?>;color:#fff;border:none;padding:6px 12px;border-radius:6px;font-weight:700;font-size:.70rem;cursor:pointer;" onclick="toggleEvent(<?php echo $ev['id']; ?>, <?php echo (int)$ev['is_enabled']; ?>, this)">
                <?php echo (int)$ev['is_enabled']===1 ? 'Enabled' : 'Disabled'; ?>
              </button>
            </td>
            <td data-label="Code"><span style="background:#f1f5f9;padding:4px 7px;border-radius:6px;font-weight:800;font-size:.74rem;color:var(--primary);"><?php echo htmlspecialchars($ev['code'] ?: '—'); ?></span></td>
            <td data-label="Event"><div style="font-weight:700;color:var(--text);line-height:1.1;"><?php echo htmlspecialchars($ev['title']); ?></div><div style="font-size:.72rem;color:var(--muted);max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($ev['description'] ?: 'No description'); ?></div><?php if((int)$ev['is_enabled']===0) echo '<span style="font-size:.68rem;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:999px;font-weight:700;">Hidden</span>'; ?></td>
            <td data-label="Challonge">
              <?php if($hasCustom): ?>
                <span style="font-size:.76rem;color:var(--primary);font-weight:700;">Custom HTML</span>
                <div style="font-size:.68rem;color:var(--muted);max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?php echo htmlspecialchars($ev['embed_html']); ?>"><?php echo htmlspecialchars(mb_strimwidth(trim(preg_replace('/\s+/',' ', strip_tags($ev['embed_html']))),0,60,'...')); ?></div>
              <?php elseif($hasUrl): ?>
                <a href="<?php echo htmlspecialchars($ev['challonge_url'] ?: $ev['challonge_embed_url']); ?>" target="_blank" rel="noopener" style="font-size:.76rem;color:var(--primary);word-break:break-all;text-decoration:underline;display:block;max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($ev['challonge_url'] ?: $ev['challonge_embed_url']); ?></a>
              <?php else: ?><span style="font-size:.76rem;color:#94a3b8;font-style:italic;">No link</span><?php endif; ?>
              <div style="font-size:.70rem;color:var(--muted);">Short: <code style="background:#fff;border:1px solid var(--line);padding:1px 4px;border-radius:5px;word-break:break-all;"><?php echo htmlspecialchars($shortUrl); ?></code></div>
            </td>
            <td data-label="Embed?"><?php if($hasUrl) echo '<span style="background:#dcfce7;color:#166534;padding:3px 7px;border-radius:999px;font-size:.68rem;font-weight:700;">Yes</span>'; else echo '<span style="background:#fef3c7;color:#92400e;padding:3px 7px;border-radius:999px;font-size:.68rem;font-weight:700;">No</span>'; ?></td>
            <td data-label="Actions">
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="btn btn-primary btn-sm" title="Edit" onclick='openEdit(<?php echo json_encode($ev, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>)'><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm" style="background:#fff;border:1px solid var(--line);" title="Copy Short Link" onclick="copyShort('<?php echo htmlspecialchars($shortUrl, ENT_QUOTES); ?>')"><i class="fas fa-link"></i></button>
                <a class="btn btn-sm" style="background:#fff;border:1px solid var(--line);" title="Open public" href="../?e=<?php echo urlencode($ev['slug']); ?>" target="_blank">Open</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </details>
  <?php endforeach; ?>
  <?php if(empty($grouped)): ?><div style="padding:24px;text-align:center;background:var(--card);border:1px solid var(--line);border-radius:12px;">No categories found</div><?php endif; ?>
</div>

<!-- Modals -->
<div id="editModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Event</h3><span class="close" onclick="closeEdit()">&times;</span></div><form method="POST" id="editForm"><?php echo CSRF::field(); ?><input type="hidden" name="action" value="update_event"><input type="hidden" name="event_id" id="e_id"><div class="modal-body"><div style="display:grid;grid-template-columns:100px 1fr;gap:10px;"><div class="form-group"><label>Code</label><input type="text" name="code" id="e_code"></div><div class="form-group"><label>Title *</label><input type="text" name="title" id="e_title" required></div></div><div class="form-group"><label>Challonge URL</label><input type="url" name="challonge_url" id="e_url" placeholder="https://challonge.com/..."></div><div class="form-group"><label>Embed URL (optional)</label><input type="url" name="challonge_embed_url" id="e_embed" placeholder=".../module"></div><div class="form-group"><label>Custom Embed HTML (optional – paste full HTML with iframes)</label><textarea name="embed_html" id="e_html" rows="6" placeholder='e.g. &lt;p&gt;&lt;strong&gt;Best viewed in landscape orientation.&lt;/strong&gt;&lt;/p&gt; ...'></textarea><small style="color:var(--muted);display:block;margin-top:4px;">If filled, this HTML will be rendered on the public page instead of the URL fields. Supports &lt;p&gt;, &lt;strong&gt;, &lt;br&gt;, &lt;iframe&gt; etc.</small></div><div class="form-group"><label>Description</label><textarea name="description" id="e_desc" rows="2"></textarea></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;"><div class="form-group"><label>Sort</label><input type="number" name="sort_order" id="e_sort" min="0"></div><div class="form-group"><label>Visible</label><label class="switch"><input type="checkbox" name="event_enabled" id="e_enabled" value="1"><span class="slider"></span></label></div></div></div><div class="modal-actions"><button type="button" class="btn btn-secondary" onclick="closeEdit()">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form></div></div>
<div id="catModal" class="modal"><div class="modal-content" style="max-width:480px"><div class="modal-header"><h3>Edit Category</h3><span class="close" onclick="closeCat()">&times;</span></div><form method="POST"><?php echo CSRF::field(); ?><input type="hidden" name="action" value="update_category"><input type="hidden" name="category_id" id="c_id"><div class="modal-body"><div class="form-group"><label>Label *</label><input type="text" name="label" id="c_label" required></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;"><div class="form-group"><label>Sort</label><input type="number" name="sort_order" id="c_sort" min="0"></div><div class="form-group"><label>Enabled</label><label class="switch"><input type="checkbox" name="cat_enabled" id="c_enabled" value="1"><span class="slider"></span></label></div></div></div><div class="modal-actions"><button type="button" class="btn btn-secondary" onclick="closeCat()">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form></div></div>

<div id="uweekToast" role="status" aria-live="polite"></div>
<script>
// NOTE: This admin's backend handler file is ajax-uweek.php (see the code you use
// for authorization/CSRF/toggle_event/bulk_toggle_events). All XHR calls below now
// point there instead of the nonexistent "ajax.php", which was causing every
// toggle click to fail with "Method Not Allowed".
var AJAX_ENDPOINT = window.location.pathname.replace(/\/[^\/]*$/, '/ajax-uweek.php');

function showToast(m){const t=document.getElementById('uweekToast');t.textContent=m;t.classList.add('show');clearTimeout(t._h);t._h=setTimeout(()=>t.classList.remove('show'),2300);}
function copyShort(url){ if(navigator.clipboard){ navigator.clipboard.writeText(url).then(()=>showToast('Short link copied: '+url)); } else { prompt('Copy link:', url); } }
function shareAdmin(){
  const url = <?php echo json_encode($base_path . 'uweek/'); ?>;
  const full = location.origin + url;
  if(navigator.share){ navigator.share({title:'University Week 2026', url: full}).catch(()=>{}); }
  else if(navigator.clipboard){ navigator.clipboard.writeText(full).then(()=>showToast('Link copied')); }
  else prompt('Copy link:', full);
}
function openEdit(ev){ document.getElementById('e_id').value=ev.id; document.getElementById('e_code').value=ev.code||''; document.getElementById('e_title').value=ev.title||''; document.getElementById('e_url').value=ev.challonge_url||''; document.getElementById('e_embed').value=ev.challonge_embed_url||''; document.getElementById('e_html').value=ev.embed_html||''; document.getElementById('e_desc').value=ev.description||''; document.getElementById('e_sort').value=ev.sort_order||0; document.getElementById('e_enabled').checked=parseInt(ev.is_enabled)===1; document.getElementById('editModal').style.display='block'; }
function closeEdit(){ document.getElementById('editModal').style.display='none'; }
function openCatEdit(id,label,enabled,sort){ document.getElementById('c_id').value=id; document.getElementById('c_label').value=label; document.getElementById('c_enabled').checked=enabled===1; document.getElementById('c_sort').value=sort; document.getElementById('catModal').style.display='block'; }
function closeCat(){ document.getElementById('catModal').style.display='none'; }
window.onclick=function(e){ if(e.target===document.getElementById('editModal')) closeEdit(); if(e.target===document.getElementById('catModal')) closeCat(); }
function getCsrfToken(el){
  var t = el && el.closest ? el.closest('form') : null;
  t = t ? t.querySelector('input[name="_token"]') : document.querySelector('input[name="_token"]');
  return t ? t.value : <?php echo json_encode(CSRF::token()); ?>;
}
function toggleEvent(eventId, currentEnabled, btn){
  var newEnabled = currentEnabled ? 0 : 1;
  var token = document.querySelector('input[name="_token"]').value;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', AJAX_ENDPOINT, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var d = JSON.parse(xhr.responseText);
          if(!d.success){ showToast(d.error||'Failed'); return; }
          showToast(newEnabled?'Event enabled':'Event disabled');
          btn.textContent = newEnabled ? 'Enabled' : 'Disabled';
          btn.style.background = newEnabled ? '#10b981' : '#ef4444';
          btn.onclick = function() { toggleEvent(eventId, newEnabled, btn); };
          // update Hidden badge in row
          var row=btn.closest('tr');
          if(row){
            var hidden=row.querySelector('td[data-label="Event"] span[style*="fee2e2"]');
            if(!newEnabled){
              if(!hidden){
                var td=row.querySelector('td[data-label="Event"]');
                var s=document.createElement('span');
                s.style.cssText='font-size:.68rem;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:999px;font-weight:700;margin-left:4px;';
                s.textContent='Hidden';
                td.querySelector('div').appendChild(s);
              }
            } else if(hidden){ hidden.remove(); }
          }
        } catch(e) {
          showToast('Server error');
        }
      } else {
        showToast('Network error');
      }
    }
  };
  var params = '_token=' + encodeURIComponent(token) + '&action=toggle_event&event_id=' + eventId + '&is_enabled=' + newEnabled;
  xhr.send(params);
}
function toggleCategory(catId, currentEnabled, btn){
  var newEnabled = currentEnabled ? 0 : 1;
  var token = document.querySelector('input[name="_token"]').value;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', AJAX_ENDPOINT, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var d = JSON.parse(xhr.responseText);
          if(!d.success){ showToast(d.error||'Failed'); return; }
          showToast(newEnabled?'Category enabled':'Category disabled');
          btn.textContent = newEnabled ? 'Enabled' : 'Disabled';
          btn.style.background = newEnabled ? '#10b981' : '#ef4444';
          btn.onclick = function() { toggleCategory(catId, newEnabled, btn); };
        } catch(e) {
          showToast('Server error');
        }
      } else {
        showToast('Network error');
      }
    }
  };
  var params = '_token=' + encodeURIComponent(token) + '&action=toggle_category&category_id=' + catId + '&is_enabled=' + newEnabled;
  xhr.send(params);
}
function bulkToggle(catId, bulk, btn){
  var enable = bulk==='enable'?1:0;
  var token = document.querySelector('input[name="_token"]').value;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', AJAX_ENDPOINT, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var d = JSON.parse(xhr.responseText);
          if(!d.success){ showToast(d.error||'Failed'); return; }
          showToast(enable?'All events enabled':'All events disabled');
          // update all buttons in this category details
          var details = btn ? btn.closest('details') : document;
          if(details){
            details.querySelectorAll('td[data-label="Visible"] button').forEach(function(b){
              b.textContent = enable ? 'Enabled' : 'Disabled';
              b.style.background = enable ? '#10b981' : '#ef4444';
            });
            // update Hidden badges: remove or add
            details.querySelectorAll('tbody tr').forEach(function(tr){
              var hidden=tr.querySelector('td[data-label="Event"] span[style*="fee2e2"]');
              if(!enable && !hidden){
                var td=tr.querySelector('td[data-label="Event"]');
                if(td){
                  var s=document.createElement('span');
                  s.style.cssText='font-size:.68rem;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:999px;font-weight:700;margin-left:4px;';
                  s.textContent='Hidden';
                  var div=td.querySelector('div');
                  if(div) div.appendChild(s);
                }
              } else if(enable && hidden){ hidden.remove(); }
            });
          }
        } catch(e) {
          showToast('Server error');
        }
      } else {
        showToast('Network error');
      }
    }
  };
  var params = '_token=' + encodeURIComponent(token) + '&action=bulk_toggle_events&category_id=' + catId + '&bulk=' + bulk;
  xhr.send(params);
}
function submitToggleForm(form, eventId, currentEnabled){
  var checkbox = form.querySelector('input[type="checkbox"]');
  var newEnabled = checkbox.checked ? 1 : 0;
  form.querySelector('input[name="is_enabled"]').value = newEnabled;
  var formData = new FormData(form);
  var xhr = new XMLHttpRequest();
  xhr.open('POST', AJAX_ENDPOINT, true);
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var d = JSON.parse(xhr.responseText);
          if(!d.success){ checkbox.checked = !checkbox.checked; showToast(d.error||'Failed'); return; }
          showToast(newEnabled?'Event enabled':'Event disabled');
          // update Hidden badge in row
          var row=form.closest('tr');
          if(row){
            var hidden=row.querySelector('td[data-label="Event"] span[style*="fee2e2"]');
            if(!newEnabled){
              if(!hidden){
                var td=row.querySelector('td[data-label="Event"]');
                var s=document.createElement('span');
                s.style.cssText='font-size:.68rem;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:999px;font-weight:700;margin-left:4px;';
                s.textContent='Hidden';
                td.querySelector('div').appendChild(s);
              }
            } else if(hidden){ hidden.remove(); }
          }
        } catch(e) {
          checkbox.checked = !checkbox.checked;
          showToast('Server error');
        }
      } else {
        checkbox.checked = !checkbox.checked;
        showToast('Network error');
      }
    }
  };
  xhr.send(formData);
  return false;
}

// Attach event listeners to event checkboxes
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('input[name="is_enabled"]').forEach(function(chk) {
    var eventId = chk.getAttribute('data-event-id');
    if(!eventId) {
      var parentTd = chk.closest('td');
      if(parentTd) {
        var hiddenInput = parentTd.querySelector('input[type="hidden"][name="event_id"]');
        if(hiddenInput) eventId = hiddenInput.value;
      }
    }
    if(eventId) {
      chk.addEventListener('click', function(e) {
        e.preventDefault();
        toggleEventAjax(chk, eventId);
      });
    }
  });
});
function toggleCategoryAjax(chk,catId){
  var enabled=chk.checked?1:0;
  var token=getCsrfToken(chk);
  var scrollY=window.scrollY;
  var statusEl = chk.closest('form') ? chk.closest('form').querySelector('.cat-status') : null;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', AJAX_ENDPOINT, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var d = JSON.parse(xhr.responseText);
          if(!d.success){ chk.checked=!chk.checked; showToast(d.error||'Failed'); return; }
          showToast(enabled?'Category enabled':'Category disabled');
          if(statusEl){ statusEl.textContent=enabled?'Enabled':'Disabled'; statusEl.style.color=enabled?'#059669':'#dc2626'; }
          window.scrollTo(0, scrollY);
        } catch(e) {
          chk.checked=!chk.checked;
          showToast('Server error');
        }
      } else {
        chk.checked=!chk.checked;
        showToast('Network error');
      }
    }
  };
  var params = '_token=' + encodeURIComponent(token) + '&action=toggle_category&category_id=' + catId + '&is_enabled=' + enabled;
  xhr.send(params);
}
function bulkToggleAjax(catId, bulk, btn){
  var token=getCsrfToken(btn);
  var scrollY=window.scrollY;
  var enable = bulk==='enable'?1:0;
  if(btn) btn.disabled=true;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', AJAX_ENDPOINT, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var d = JSON.parse(xhr.responseText);
          if(!d.success){ showToast(d.error||'Failed'); return; }
          showToast(enable?'All events enabled':'All events disabled');
          // update all switches in this category details
          var details = btn ? btn.closest('details') : document;
          if(details){
            details.querySelectorAll('input[name="is_enabled"]').forEach(function(cb){
              // Only event checkboxes (small switch) inside this details
              if(cb.closest('td')) cb.checked=!!enable;
            });
            // update Hidden badges: remove or add
            details.querySelectorAll('tbody tr').forEach(function(tr){
              var hidden=tr.querySelector('td[data-label="Event"] span[style*="fee2e2"]');
              if(!enable && !hidden){
                var td=tr.querySelector('td[data-label="Event"]');
                if(td){
                  var s=document.createElement('span');
                  s.style.cssText='font-size:.68rem;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:999px;font-weight:700;margin-left:4px;';
                  s.textContent='Hidden';
                  var div=td.querySelector('div');
                  if(div) div.appendChild(s);
                }
              } else if(enable && hidden){ hidden.remove(); }
            });
          }
          window.scrollTo(0, scrollY);
        } catch(e) {
          showToast('Server error');
        }
      } else {
        showToast('Network error');
      }
    }
  };
  var params = '_token=' + encodeURIComponent(token) + '&action=bulk_toggle_events&category_id=' + catId + '&bulk=' + bulk;
  xhr.send(params);
  if(btn) btn.disabled=false;
}
</script>
</body>
</html>