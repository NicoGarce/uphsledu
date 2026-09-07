<?php
/**
 * UWeek – University Week 2026 (Public) – Overview / Index
 * Now shows Google Sheets scores as main content. Brackets moved to scores.php
 */
session_start();
require_once __DIR__ . '/../app/config/paths.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';
$base_path = $GLOBALS['base_path'] ?? '/uphsledu/';
$page_title = 'University Week 2026';
$uweek_enabled = getSetting('uweek_enabled', '1') === '1';
$shareUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . $base_path . 'uweek/';
$shareTitle = 'University Week 2026 – Live Scores';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?> – UPHSL</title>
<meta name="description" content="UPHSL University Week 2026 – Live scores and standings">
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
.sheet-card{background:var(--card);border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:0 8px 28px rgba(0,0,0,.07);text-align:center}
.sheet-head{padding:14px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;background:linear-gradient(180deg,#fff 0%,#fbfdff 100%);text-align:left}
.sheet-head h2{margin:0;font-family:'Barlow Semi Condensed',sans-serif;font-size:1.2rem;color:var(--primary);text-transform:uppercase}
.sheet-head p{margin:2px 0 0;color:var(--muted);font-size:.82rem}
.sheet-actions{display:flex;gap:8px;align-items:center}
.btn-open{padding:9px 12px;border-radius:10px;background:var(--primary);color:#fff;text-decoration:none;font-weight:800;font-size:.78rem}
.sheet-frame{background:#fff;display:flex;justify-content:center;align-items:center;padding:0}
.sheet-frame iframe{width:100%;max-width:100%;height:80vh;min-height:500px;border:0;display:block;background:#fff;margin:0 auto}
@media(max-width:768px){.sheet-frame iframe{height:72vh;min-height:460px}}
.uweek-footer{margin-top:28px;padding:18px 16px;text-align:center;color:var(--muted);font-size:.78rem;border-top:1px solid var(--line);background:var(--card)}
.uweek-footer a{color:var(--primary);font-weight:700;text-decoration:none}
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
      <button class="btn-share" id="topShareBtn" type="button">Share</button>
    </div>
  </div>
</nav>
<section class="uweek-hero" aria-label="University Week banner"></section>
<div class="uweek-livebar"><span><span class="dot"></span> Live • University Week 2026</span></div>
<div class="uweek-subnav" aria-label="Section navigation">
  <a href="<?php echo $base_path; ?>uweek/" class="active">Overview</a>
  <a href="<?php echo $base_path; ?>uweek/scores.php">Brackets</a>
  <a href="<?php echo $base_path; ?>uweek/schedules.php">Schedules</a>
</div>
<?php if (!$uweek_enabled): ?>
<div class="uweek-wrap"><div style="background:var(--card);border:1px solid var(--line);border-radius:16px;padding:32px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,.05);"><h3 style="margin:0 0 8px;color:var(--primary);font-family:'Barlow Semi Condensed',sans-serif;text-transform:uppercase;">University Week 2026 is currently unavailable</h3><p style="color:var(--muted)">Please check back later.</p></div></div>
<?php else: ?>
<div class="uweek-wrap">
  <div class="sheet-card">
    <div class="sheet-head">
      <div>
        <h2>Live Scores & Standings</h2>
        <p>Official University Week 2026 scores — best viewed in landscape on mobile.</p>
      </div>
      <div class="sheet-actions">
        <button class="btn-share" type="button" onclick="shareScores()" style="background:var(--gold);color:#0f172a;border:1px solid #e0b03c;padding:8px 12px;border-radius:10px;font-weight:700;cursor:pointer;">Share</button>
        <a class="btn-open" href="https://docs.google.com/spreadsheets/d/e/2PACX-1vQeJJVxf8aoVBMbOGa0wJAmdn420JC9ouIpAlojMLwtZ7nQ1-vR7e6ZlggMZ8wtGA/pubhtml?gid=791635313&single=true&widget=true&headers=false" target="_blank" rel="noopener">Open Sheet</a>
      </div>
    </div>
    <div class="sheet-frame">
        <iframe style="width: 100%; height: 80vh; min-height: 500px; border: 0; align-items: center;" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vQeJJVxf8aoVBMbOGa0wJAmdn420JC9ouIpAlojMLwtZ7nQ1-vR7e6ZlggMZ8wtGA/pubhtml?gid=791635313&amp;single=true&amp;widget=true&amp;headers=false"></iframe>
    </div>
  </div>
</div>
<?php endif; ?>
<footer class="uweek-footer"><div>© <?php echo date('Y'); ?> University of Perpetual Help System Laguna • <a href="<?php echo $base_path; ?>">Back to Website</a> • <a href="<?php echo $base_path; ?>uweek/">University Week 2026</a></div></footer>
<div id="uweekToast" role="status" aria-live="polite"></div>
<script>
const shareUrl = <?php echo json_encode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . $base_path . 'uweek/'); ?>;
function showToast(m){const t=document.getElementById('uweekToast');t.textContent=m;t.classList.add('show');clearTimeout(t._hide);t._hide=setTimeout(()=>t.classList.remove('show'),2200);}
function shareScores(){ if(navigator.share){navigator.share({title:'University Week 2026 – Live Scores', url: shareUrl}).catch(()=>{});} else if(navigator.clipboard){navigator.clipboard.writeText(shareUrl).then(()=>showToast('Link copied: '+shareUrl))} else prompt('Copy link:',shareUrl); }
document.getElementById('topShareBtn')?.addEventListener('click', shareScores);
</script>
</body>
</html>
