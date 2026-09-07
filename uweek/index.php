<?php
/**
 * UWeek – Scores / Brackets (Standalone)
 * Moved from index.php – Brackets now live here as scores.php
 */
session_start();
require_once __DIR__ . '/../app/config/paths.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';

$base_path = $GLOBALS['base_path'] ?? '/uphsledu/';
$page_title = 'University Week 2026 – Brackets';

$shortEvent = $_GET['e'] ?? '';
if ($shortEvent !== '') {
    $evByShort = getUWeekEventBySlug($shortEvent);
    if ($evByShort) {
        $_GET['event'] = $shortEvent;
        $_GET['cat'] = $evByShort['category_slug'];
    }
}
$allCategories = getUWeekCategories(true);
$activeCatSlug = $_GET['cat'] ?? '';
$activeEventSlug = $_GET['event'] ?? '';
$activeCategory = null;
$activeEvents = [];
$activeEvent = null;
if (!empty($allCategories)) {
    if ($activeCatSlug !== '') {
        foreach ($allCategories as $c) {
            if ($c['slug'] === $activeCatSlug) { $activeCategory = $c; break; }
        }
    }
    if (!$activeCategory) $activeCategory = $allCategories[0];
    $activeEvents = getUWeekEvents((int)$activeCategory['id'], true);
    if (!empty($activeEvents)) {
        if ($activeEventSlug !== '') {
            foreach ($activeEvents as $e) {
                if ($e['slug'] === $activeEventSlug) { $activeEvent = $e; break; }
            }
        }
        if (!$activeEvent) $activeEvent = $activeEvents[0];
    }
}
function build_challonge_embed($url, $embed) {
    $src = trim($embed ?: $url ?: '');
    if ($src === '') return '';
    if (strpos($src, 'challonge.com') !== false && strpos($src, '/module') === false) {
        $src = rtrim($src, '/');
        if (strpos($src, '?') === false) $src .= '/module';
        else $src = str_replace('?', '/module?', $src);
    }
    if (strpos($src, 'http') !== 0) $src = 'https://' . ltrim($src, '/');
    return $src;
}
$embedSrc = $activeEvent ? build_challonge_embed($activeEvent['challonge_url'], $activeEvent['challonge_embed_url']) : '';
$shareSlug = $activeEvent['slug'] ?? '';
$shareUrl = ($shareSlug !== '') ? ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $base_path . 'uweek/?e=' . urlencode($shareSlug)) : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . $base_path . 'uweek/');
$shareTitle = $activeEvent ? ($activeEvent['title'] . ' – University Week 2026') : 'University Week 2026 – Brackets';
$uweek_enabled = getSetting('uweek_enabled', '1') === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?> – UPHSL</title>
<meta name="description" content="UPHSL University Week live brackets – powered by Challonge">
<meta property="og:title" content="<?php echo htmlspecialchars($shareTitle); ?>">
<meta property="og:url" content="<?php echo htmlspecialchars($shareUrl); ?>">
<link rel="icon" type="image/png" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;700;800&family=Inter:wght@500;600;700&display=swap" rel="stylesheet">
<style>
:root{--primary:#1c4da1;--primary-dark:#0f2a6b;--primary-light:#2a5cc8;--gold:#ffc63e;--bg:#f4f6fb;--card:#fff;--line:#e2e8f0;--text:#0f172a;--muted:#64748b;--radius:16px;}
*{box-sizing:border-box}html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-family:'Inter',sans-serif}
.uweek-topbar{position:sticky;top:0;z-index:30;background:linear-gradient(90deg,var(--primary-dark) 0%,var(--primary) 55%,#1e3a8a 100%);color:#fff;border-bottom:3px solid var(--gold);box-shadow:0 4px 18px rgba(0,0,0,.18)}
.uweek-topbar-inner{max-width:1280px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;gap:14px;justify-content:space-between}
.uweek-brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:#fff;min-width:0}
.uweek-brand img{width:40px;height:40px;object-fit:contain;background:transparent;border-radius:0;padding:0;filter:drop-shadow(0 1px 2px rgba(0,0,0,.2))}
.uweek-brand-text{display:flex;flex-direction:column;line-height:1}
.uweek-brand-text strong{font-family:'Barlow Semi Condensed',sans-serif;font-weight:800;letter-spacing:.4px;font-size:1.05rem;text-transform:uppercase}
.uweek-brand-text span{font-size:.72rem;opacity:.9;letter-spacing:.3px;text-transform:uppercase;font-weight:600}
.uweek-top-actions{display:flex;align-items:center;gap:8px;flex-shrink:0}
.btn-back,.btn-share{appearance:none;border:0;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:7px;padding:8px 12px;border-radius:10px;font-weight:700;font-size:.78rem;white-space:nowrap}
.btn-back{background:rgba(255,255,255,.14);color:#fff;border:1px solid rgba(255,255,255,.22)}
.btn-share{background:var(--gold);color:#0f172a;border:1px solid #e0b03c}
.uweek-hero{position:relative;overflow:hidden;background:var(--primary-dark);min-height:220px;display:block}
.uweek-hero::before{content:'';position:absolute;inset:0;background:url('uweeklongbanner.png') center/cover no-repeat}
@media(max-width:768px){.uweek-hero{min-height:180px}}@media(max-width:480px){.uweek-hero{min-height:140px}}
.uweek-livebar{max-width:1280px;margin:0 auto;padding:0 16px;position:relative;z-index:2;margin-top:-14px;display:flex;justify-content:center}
.uweek-livebar span{display:inline-flex;align-items:center;gap:8px;background:#fff;color:var(--primary-dark);border:1px solid var(--line);padding:7px 12px;border-radius:999px;font-weight:800;font-size:.72rem;box-shadow:0 4px 14px rgba(0,0,0,.08);letter-spacing:.2px;text-transform:uppercase}
.uweek-livebar .dot{width:8px;height:8px;background:#16a34a;border-radius:50%;box-shadow:0 0 0 6px rgba(22,163,74,.18);animation:pulse 1.6s infinite}
@keyframes pulse{0%{box-shadow:0 0 0 0 rgba(22,163,74,.28)}70%{box-shadow:0 0 0 7px rgba(22,163,74,0)}100%{box-shadow:0 0 0 0 rgba(22,163,74,0)}}
.uweek-subnav{max-width:1280px;margin:10px auto 0;padding:0 16px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
.uweek-subnav a{padding:7px 14px;border-radius:999px;border:1.5px solid var(--line);background:var(--card);font-weight:700;font-size:.78rem;font-family:'Barlow Semi Condensed',sans-serif;letter-spacing:.2px;text-transform:uppercase;text-decoration:none;color:var(--text)}
.uweek-subnav a.active{background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;border-color:var(--primary)}
.uweek-wrap{max-width:1280px;margin:0 auto;padding:18px 16px 40px}
@media(max-width:768px){.uweek-wrap{padding:14px 12px 28px}}
.uweek-cats{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;padding:14px 0 6px}
.cat-tab{flex:0 1 auto;display:inline-flex;align-items:center;justify-content:center;padding:8px 14px;border-radius:999px;background:var(--card);border:1.5px solid var(--line);color:#1e293b;font-weight:800;font-size:.78rem;font-family:'Barlow Semi Condensed',sans-serif;letter-spacing:.2px;text-transform:uppercase;text-decoration:none;min-height:34px}
.cat-tab.active{background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;border-color:var(--primary)}
@media(max-width:768px){.uweek-cats{display:none !important}}
.uweek-layout{display:grid;grid-template-columns:300px 1fr;gap:18px;margin-top:10px;align-items:start}
@media(max-width:768px){.uweek-layout{display:flex;flex-direction:column;gap:12px}.event-list{display:none !important}.embed-card{order:1}}
.event-list{background:var(--card);border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.06);align-self:start;position:sticky;top:72px}
.event-list-header{padding:12px 14px;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;display:flex;justify-content:space-between}
.event-list-header h3{margin:0;font-family:'Barlow Semi Condensed',sans-serif;font-size:.9rem;text-transform:uppercase}
.event-items{max-height:66vh;overflow-y:auto}
.event-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-bottom:1px solid #f1f5f9;text-decoration:none;color:var(--text);min-height:44px}
.event-item.active{background:#eef2ff;border-left:3px solid var(--primary)}
.event-title{font-weight:700;font-size:.78rem}
.event-sub{font-size:.68rem;color:var(--muted)}
.mobile-cat-switcher,.mobile-event-switcher{display:none;align-items:center;gap:8px;margin:12px 0 4px;padding:10px 12px;background:var(--card);border:1px solid var(--line);border-radius:12px}
.mobile-cat-switcher label,.mobile-event-switcher label{font-weight:800;font-size:.74rem;white-space:nowrap;text-transform:uppercase}
.mobile-cat-switcher select,.mobile-event-switcher select{flex:1;padding:9px 10px;border:1px solid #cbd5e1;border-radius:8px;font-weight:700;font-size:.82rem;background:#f8fafc}
@media(max-width:768px){.mobile-cat-switcher,.mobile-event-switcher{display:flex}}@media(min-width:769px){.mobile-cat-switcher,.mobile-event-switcher{display:none !important}}
.embed-card{background:var(--card);border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:0 8px 28px rgba(0,0,0,.07)}
.embed-head{padding:14px 16px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;background:linear-gradient(180deg,#fff 0%,#fbfdff 100%)}
.embed-head h2{margin:0;font-family:'Barlow Semi Condensed',sans-serif;font-size:1.25rem;color:var(--primary);text-transform:uppercase}
.embed-meta{color:var(--muted);font-size:.78rem}
.badge-live{padding:5px 9px;border-radius:999px;background:#dcfce7;color:#166534;font-weight:800;font-size:.70rem;border:1px solid #bbf7d0}
.badge-soon{padding:5px 9px;border-radius:999px;background:#fef3c7;color:#92400e;font-weight:800;font-size:.70rem;border:1px solid #fde68a}
.btn-open{padding:9px 12px;border-radius:10px;background:var(--primary);color:#fff;text-decoration:none;font-weight:800;font-size:.78rem}
.btn-share-small{padding:8px 11px;border-radius:10px;background:#fff;color:var(--primary);border:1.5px solid var(--line);font-weight:800;font-size:.74rem;cursor:pointer}
.embed-wrap{position:relative;background:#0f172a}.embed-wrap iframe{width:100%;height:820px;border:0;display:block;background:#fff}
.custom-embed-wrap{padding:18px;background:#fff;overflow:auto}.custom-embed-wrap iframe{width:100%;height:500px;border:0;margin:10px 0 14px;border-radius:8px}
.custom-bracket-block{margin-bottom:20px}.landscape-note{margin:0 0 14px !important;background:#fffbeb;border:1px solid #fde68a;color:#92400e;padding:8px 12px;border-radius:8px;font-weight:700;font-size:.78rem;text-align:center}
.bracket-event-title{margin:14px 0 8px !important;font-family:'Barlow Semi Condensed',sans-serif;font-weight:800;color:var(--primary);font-size:1.15rem;border-left:4px solid var(--gold);padding-left:10px;text-transform:uppercase}
.bracket-frame{background:#f8fafc;border:1px solid var(--line);border-radius:10px;padding:6px}
@media(max-width:768px){.embed-wrap iframe{height:67vh;min-height:520px}.custom-embed-wrap iframe,.bracket-frame iframe{height:460px}}
@media(max-width:480px){.custom-embed-wrap iframe,.bracket-frame iframe{height:400px}}
.embed-placeholder{padding:52px 18px;text-align:center;color:#475569;background:linear-gradient(180deg,#f8fafc,#fff)}
.uweek-footer{margin-top:28px;padding:18px 16px;text-align:center;color:var(--muted);font-size:.78rem;border-top:1px solid var(--line);background:var(--card)}
#uweekToast{position:fixed;left:50%;bottom:22px;transform:translateX(-50%) translateY(20px);background:#0f172a;color:#fff;padding:10px 14px;border-radius:10px;font-weight:700;font-size:.82rem;opacity:0;pointer-events:none;transition:.22s;z-index:99}
#uweekToast.show{opacity:1;transform:translateX(-50%) translateY(0)}
</style>
</head>
<body>
<nav class="uweek-topbar" aria-label="University Week 2026 navigation">
  <div class="uweek-topbar-inner">
    <a href="<?php echo $base_path; ?>uweek/" class="uweek-brand" aria-label="University Week 2026 Home">
      <img src="<?php echo $base_path; ?>assets/images/Logos/logo.png" alt="UPHSL">
      <span class="uweek-brand-text"><strong>University Week 2026</strong><span>UPHSL</span></span>
    </a>
    <div class="uweek-top-actions">
      <a href="<?php echo $base_path; ?>" class="btn-back">Back to Website</a>
      <button class="btn-share" id="topShareBtn" type="button">Share Results</button>
    </div>
  </div>
</nav>
<section class="uweek-hero" aria-label="University Week banner"></section>
<div class="uweek-livebar"><span><span class="dot"></span> Live • University Week 2026</span></div>
<div class="uweek-subnav" aria-label="Section navigation">
  <a href="<?php echo $base_path; ?>uweek/scores.php">Overview</a>
  <a href="<?php echo $base_path; ?>uweek/" class="active">Brackets</a>
  <a href="<?php echo $base_path; ?>uweek/schedules.php">Schedules</a>
</div>
<?php if (!$uweek_enabled): ?>
<div class="uweek-wrap"><div class="empty-state" style="margin-top:18px;"><h3>University Week 2026 is currently unavailable</h3><p>Turned off for public viewing.</p></div></div>
<?php else: ?>
<div class="uweek-wrap">
  <?php if (empty($allCategories)): ?>
    <div class="empty-state"><h3>No events configured yet</h3><p>Please check back soon.</p></div>
  <?php else: ?>
    <nav class="uweek-cats" aria-label="Event categories">
      <?php foreach ($allCategories as $cat): $isActive = $activeCategory && $activeCategory['id'] === $cat['id']; ?>
        <a class="cat-tab <?php echo $isActive ? 'active' : ''; ?>" href="?cat=<?php echo htmlspecialchars($cat['slug']); ?>"><?php echo htmlspecialchars($cat['label']); ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="mobile-cat-switcher" aria-label="Category switcher">
      <label for="mobileCatSelect">Category:</label>
      <select id="mobileCatSelect" onchange="if(this.value) window.location.href=this.value">
        <?php foreach ($allCategories as $cat): $isCatSel = $activeCategory && (int)$activeCategory['id'] === (int)$cat['id']; ?>
          <option value="?cat=<?php echo urlencode($cat['slug']); ?>" <?php echo $isCatSel ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['label']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mobile-event-switcher" aria-label="Quick event switcher">
      <label for="mobileEventSelect">Event:</label>
      <select id="mobileEventSelect" onchange="if(this.value) window.location.href=this.value">
        <?php foreach ($activeEvents as $ev): $isSel = $activeEvent && (int)$activeEvent['id'] === (int)$ev['id']; ?>
          <option value="?cat=<?php echo urlencode($activeCategory['slug']); ?>&event=<?php echo urlencode($ev['slug']); ?>" <?php echo $isSel ? 'selected' : ''; ?>><?php echo htmlspecialchars($ev['title']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="uweek-layout">
      <aside class="event-list" aria-label="Events">
        <div class="event-list-header"><h3><?php echo htmlspecialchars($activeCategory['label']); ?></h3></div>
        <div class="event-items">
          <?php foreach ($activeEvents as $ev): $isEvActive = $activeEvent && $activeEvent['id'] === $ev['id']; ?>
            <a class="event-item <?php echo $isEvActive ? 'active' : ''; ?>" href="?cat=<?php echo htmlspecialchars($activeCategory['slug']); ?>&event=<?php echo htmlspecialchars($ev['slug']); ?>">
              <div style="min-width:0;flex:1;"><div class="event-title"><?php echo htmlspecialchars($ev['title']); ?></div><div class="event-sub"><?php echo htmlspecialchars($activeCategory['name']); ?></div></div>
            </a>
          <?php endforeach; ?>
        </div>
      </aside>
      <section class="embed-card" aria-live="polite">
        <?php
            $customHtml = trim($activeEvent['embed_html'] ?? '');
            $hasCustom = $customHtml !== '';
            $hasEmbed = $hasCustom || $embedSrc !== '';
        ?>
          <div class="embed-head">
            <div style="min-width:0;"><h2><?php echo htmlspecialchars($activeEvent['title']); ?></h2><div class="embed-meta"><?php echo htmlspecialchars($activeCategory['label']); ?></div></div>
            <div class="embed-actions">
              <?php if ($hasEmbed): ?><span class="badge-live">Live</span><button class="btn-share-small" type="button" onclick="shareShort()">Share</button><?php if(!$hasCustom): ?><a class="btn-open" href="<?php echo htmlspecialchars($activeEvent['challonge_url'] ?: $embedSrc); ?>" target="_blank" rel="noopener">Open on Challonge</a><?php endif; ?><?php else: ?><span class="badge-soon">Coming Soon</span><button class="btn-share-small" type="button" onclick="shareShort()">Share</button><?php endif; ?>
            </div>
          </div>
          <?php if ($hasCustom): ?><div class="custom-embed-wrap"><?php echo $customHtml; ?></div>
          <?php elseif ($hasEmbed): ?><div class="embed-wrap"><iframe src="<?php echo htmlspecialchars($embedSrc); ?>" title="<?php echo htmlspecialchars($activeEvent['title']); ?> Challonge Bracket" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allow="fullscreen" sandbox="allow-scripts allow-same-origin allow-popups allow-forms"></iframe></div>
          <?php else: ?><div class="embed-placeholder"><h3>Bracket Coming Soon</h3><p>Stay tuned — brackets and results will be available here shortly.</p></div><?php endif; ?>
      </section>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>
<footer class="uweek-footer"><div>© <?php echo date('Y'); ?> University of Perpetual Help System Laguna • <a href="<?php echo $base_path; ?>">Back to Website</a> • <a href="<?php echo $base_path; ?>uweek/">University Week 2026</a></div></footer>
<div id="uweekToast" role="status" aria-live="polite"></div>
<script>
const shareUrl = <?php echo json_encode($shareUrl); ?>;
const shareTitle = <?php echo json_encode($shareTitle); ?>;
function showToast(m){const t=document.getElementById('uweekToast');t.textContent=m;t.classList.add('show');clearTimeout(t._hide);t._hide=setTimeout(()=>t.classList.remove('show'),2200);}
function shareShort(){ if(navigator.share){navigator.share({title:shareTitle,url:shareUrl}).catch(()=>{});} else if(navigator.clipboard){navigator.clipboard.writeText(shareUrl).then(()=>showToast('Short link copied: '+shareUrl))} else prompt('Copy link:',shareUrl);}
document.getElementById('topShareBtn')?.addEventListener('click',shareShort);
</script>
</body>
</html>
