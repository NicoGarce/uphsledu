<?php
/**
 * UWeek – Schedules (Standalone)
 * University Week 2026 – Official Schedule
 * No main header/footer import, sport branding, no icons/emojis bloat
 */
require_once __DIR__ . '/../app/config/paths.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';
$base_path = $GLOBALS['base_path'] ?? '/uphsledu/';
$page_title = 'University Week 2026 – Schedule';
$shareUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . $base_path . 'uweek/schedules.php';
$shareTitle = 'University Week 2026 – Schedule';
$uweek_enabled = getSetting('uweek_enabled', '1') === '1';

// Structured schedule data
$schedule = [
  [
    'day' => 'Day 0',
    'weekday' => 'Saturday',
    'date' => 'Sept. 5, 2026',
    'events' => [
      ['time' => '3:00 PM onwards', 'event' => 'Coronation Night of Mr. and Ms. UPHSL 2026', 'venue' => 'Performing Arts and Theater (PAT)'],
    ]
  ],
  [
    'day' => 'Day 1',
    'weekday' => 'Monday',
    'date' => 'Sept. 7, 2026',
    'events' => [
      ['time' => '7:00 AM – 8:00 AM', 'event' => 'Holy Mass for University Week 2026', 'venue' => 'University Shrine of Our Mother of Perpetual Help'],
      ['time' => '8:00 AM onwards', 'event' => 'Motorcade, Ball Game Competitions / Other Sporting Events', 'venue' => 'Biñan, Laguna, Santa Rosa, and San Pedro, Laguna'],
      ['time' => '10:00 AM – 1:00 PM', 'event' => 'Bunting Display Competition (Preliminary Judging)', 'venue' => 'Selected/Designated University Premises'],
      ['time' => '3:00 PM – 5:00 PM', 'event' => 'Opening Ceremony', 'venue' => 'Oval Field'],
    ]
  ],
  [
    'day' => 'Day 2',
    'weekday' => 'Tuesday',
    'date' => 'Sept. 8, 2026',
    'events' => [
      ['time' => '7:00 AM – 7:00 PM', 'event' => 'Ball Game Competitions / Other Sporting Events', 'venue' => 'University Sports Facility Centers'],
      ['time' => '8:00 AM – 6:00 PM', 'event' => 'E-Sports Competition', 'venue' => 'Game Lab, Mac Lab, Digital Lab, 341, 342'],
      ['time' => '8:00 AM – 12:00 PM', 'event' => 'Battle of the Brains', 'venue' => 'Performing Arts Theater (PAT)'],
      ['time' => '1:00 PM onwards', 'event' => 'Sing and Dance Competition', 'venue' => 'Performing Arts and Theater (PAT)'],
    ]
  ],
  [
    'day' => 'Day 3',
    'weekday' => 'Wednesday',
    'date' => 'Sept. 9, 2026',
    'events' => [
      ['time' => '7:00 AM onwards', 'event' => 'Laro ng Lahi Competition', 'venue' => 'University Oval Field'],
      ['time' => '8:00 AM – 6:00 PM', 'event' => 'E-Sports Competition', 'venue' => 'Game Lab, Mac Lab, Digital Lab, 341, 342'],
      ['time' => '8:00 AM – 6:00 PM', 'event' => 'Ball Games – Elimination Round / Other Sporting Games', 'venue' => 'University Sports Facility Centers (Umbria, Mini Gym)'],
      ['time' => '8:00 AM – 12:00 PM', 'event' => 'COP', 'venue' => 'TBA'],
      ['time' => '1:00 PM – 6:00 PM', 'event' => 'Variety Show', 'venue' => 'Performing Arts Theater (PAT)'],
    ]
  ],
  [
    'day' => 'Day 4',
    'weekday' => 'Thursday',
    'date' => 'Sept. 10, 2026',
    'events' => [
      ['time' => '7:00 AM – 7:00 PM', 'event' => 'Ball Games – Elimination Round / Other Sporting Events', 'venue' => 'University Sports Facility Centers'],
      ['time' => '8:00 AM – 12:00 PM', 'event' => 'COP', 'venue' => 'TBA'],
      ['time' => '8:00 AM – 6:00 PM', 'event' => 'E-Sports Competition', 'venue' => 'Game Lab, Mac Lab, Digital Lab, 341, 342'],
      ['time' => '10:00 AM – 12:00 PM', 'event' => 'Bunting Display Competitions (Final Judging)', 'venue' => 'Selected/Designated University Premises'],
      ['time' => '2:00 PM – 5:00 PM', 'event' => 'Drag Race', 'venue' => 'Performing Arts Theater (PAT)'],
    ]
  ],
  [
    'day' => 'Final Day',
    'weekday' => 'Friday',
    'date' => 'Sept. 11, 2026',
    'events' => [
      ['time' => '9:00 AM – 12:00 PM', 'event' => 'Alumni Exhibition Games', 'venue' => 'University Sports Facility Centers'],
      ['time' => '7:00 AM – 2:00 PM', 'event' => 'Ball Games (Championship)', 'venue' => 'University Sports Facility Centers (Umbria)'],
      ['time' => '3:00 PM – 6:00 PM', 'event' => 'Battle of the Bands', 'venue' => 'University Oval Field'],
      ['time' => '5:00 PM – 6:00 PM', 'event' => 'Closing / Awarding', 'venue' => 'University Oval Field'],
    ]
  ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?> – UPHSL</title>
<meta name="description" content="Official schedule for University Week 2026 – UPHSL. Dates, times, events and venues.">
<meta property="og:title" content="<?php echo htmlspecialchars($shareTitle); ?>">
<meta property="og:description" content="Official University Week 2026 schedule – UPHSL">
<meta property="og:url" content="<?php echo htmlspecialchars($shareUrl); ?>">
<link rel="icon" type="image/png" href="<?php echo $base_path; ?>assets/images/Logos/logo.png">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;700;800&family=Inter:wght@500;600;700&display=swap" rel="stylesheet">
<style>
:root{--primary:#1c4da1;--primary-dark:#0f2a6b;--primary-light:#2a5cc8;--gold:#ffc63e;--bg:#f4f6fb;--card:#fff;--line:#e2e8f0;--text:#0f172a;--muted:#64748b;--radius:14px;}
*{box-sizing:border-box}
html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-family:'Inter',system-ui,sans-serif;scroll-behavior:smooth}
a{color:inherit}
.uweek-topbar{position:sticky;top:0;z-index:30;background:linear-gradient(90deg,var(--primary-dark) 0%,var(--primary) 60%,#1e3a8a 100%);color:#fff;border-bottom:3px solid var(--gold);box-shadow:0 4px 16px rgba(0,0,0,.18);}
.uweek-topbar-inner{max-width:1280px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;}
.uweek-brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:#fff;min-width:0;}
.uweek-brand img{width:38px;height:38px;object-fit:contain;background:transparent;border-radius:0;padding:0;filter:drop-shadow(0 1px 2px rgba(0,0,0,.2));}
.uweek-brand-text{display:flex;flex-direction:column;line-height:1}
.uweek-brand-text strong{font-family:'Barlow Semi Condensed',sans-serif;font-weight:800;letter-spacing:.3px;text-transform:uppercase;font-size:1rem;line-height:1}
.uweek-brand-text span{font-size:.68rem;opacity:.9;font-weight:600;letter-spacing:.2px;text-transform:uppercase}
.uweek-top-actions{display:flex;align-items:center;gap:8px}
.btn-top{appearance:none;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.14);color:#fff;padding:7px 11px;border-radius:9px;font-weight:700;font-size:.76rem;text-decoration:none;cursor:pointer;white-space:nowrap}
.btn-top:hover{background:rgba(255,255,255,.22)}
.btn-top.gold{background:var(--gold);color:#0f172a;border-color:#e0b03c}
.btn-top.gold:hover{background:#ffd866}
@media(max-width:640px){.uweek-topbar-inner{padding:8px 10px} .uweek-brand strong{font-size:.88rem}}
.uweek-hero{position:relative;overflow:hidden;background:var(--primary-dark);min-height:220px;display:block}
.uweek-hero::before{content:'';position:absolute;inset:0;background:url('uweeklongbanner.png') center/cover no-repeat}
@media(max-width:768px){.uweek-hero{min-height:180px}}
@media(max-width:480px){.uweek-hero{min-height:140px}}
/* Sub-nav for Brackets / Schedules */
.uweek-subnav{max-width:1280px;margin:0 auto;padding:12px 16px 0;display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
.uweek-subnav a{padding:7px 14px;border-radius:999px;border:1.5px solid var(--line);background:var(--card);font-weight:700;font-size:.78rem;font-family:'Barlow Semi Condensed',sans-serif;letter-spacing:.2px;text-transform:uppercase;text-decoration:none;color:var(--text)}
.uweek-subnav a.active{background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;border-color:var(--primary)}
@media(max-width:480px){.uweek-subnav{padding:10px 12px 0}}
/* Day nav */
.uweek-daynav{position:sticky;top:56px;z-index:20;background:rgba(244,246,251,.92);backdrop-filter:blur(8px);border-bottom:1px solid var(--line);}
.uweek-daynav-inner{max-width:1280px;margin:0 auto;padding:10px 16px;display:flex;gap:8px;overflow-x:auto;scrollbar-width:none;justify-content:center;flex-wrap:wrap}
.uweek-daynav-inner::-webkit-scrollbar{display:none}
.day-pill{flex:0 0 auto;padding:7px 12px;border-radius:999px;background:var(--card);border:1px solid var(--line);font-weight:700;font-size:.76rem;text-decoration:none;color:var(--text);white-space:nowrap;cursor:pointer}
.day-pill.active{background:var(--primary);color:#fff;border-color:var(--primary);box-shadow:0 2px 8px rgba(28,77,161,.18)}
.day-pill:focus-visible{outline:2px solid var(--gold);outline-offset:2px}
@media(max-width:768px){
  .uweek-daynav{top:52px}
  .uweek-daynav-inner{justify-content:flex-start;flex-wrap:nowrap;overflow-x:auto;padding:8px 12px}
  .day-pill{flex:0 0 auto}
}
.day-section[hidden]{display:none !important}
/* Schedule */
.uweek-wrap{max-width:980px;margin:0 auto;padding:18px 16px 40px}
@media(max-width:768px){.uweek-wrap{padding:14px 12px 28px}}
.day-section{margin-bottom:18px;background:var(--card);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.05)}
.day-head{padding:12px 16px;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;display:flex;align-items:baseline;gap:10px;flex-wrap:wrap;justify-content:space-between}
.day-head h2{margin:0;font-family:'Barlow Semi Condensed',sans-serif;font-weight:800;letter-spacing:.2px;text-transform:uppercase;font-size:1rem;line-height:1}
.day-head .date{font-weight:700;font-size:.82rem;opacity:.95;white-space:nowrap}
@media(max-width:480px){.day-head{padding:10px 12px} .day-head h2{font-size:.92rem} .day-head .date{font-size:.76rem}}
/* Table – desktop */
.schedule-table{width:100%;border-collapse:collapse}
.schedule-table thead th{text-align:left;padding:10px 12px;font-size:.72rem;letter-spacing:.4px;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;font-weight:800;background:#f8fafc}
.schedule-table tbody td{padding:11px 12px;vertical-align:top;border-bottom:1px solid #f1f5f9;font-size:.84rem;line-height:1.4}
.schedule-table tbody tr:last-child td{border-bottom:none}
.col-time{width:170px;white-space:nowrap;font-weight:700;color:var(--primary);font-variant-numeric:tabular-nums}
.col-event{font-weight:600}
.col-venue{color:var(--muted);font-size:.82rem}
/* Cards – mobile */
@media(max-width:640px){
  .schedule-table thead{display:none}
  .schedule-table, .schedule-table tbody, .schedule-table tr, .schedule-table td{display:block;width:100%}
  .schedule-table tbody tr{padding:10px 12px;border-bottom:1px solid var(--line);display:grid;gap:4px}
  .schedule-table tbody tr:last-child{border-bottom:none}
  .schedule-table tbody td{padding:0;border:none}
  .schedule-table tbody td.col-time{font-size:.78rem;color:var(--primary)}
  .schedule-table tbody td.col-event{font-size:.88rem;font-weight:700;color:var(--text)}
  .schedule-table tbody td.col-venue{font-size:.78rem;color:var(--muted);padding-top:2px;border-top:1px dashed #e2e8f0;margin-top:4px}
  .schedule-table tbody td.col-venue::before{content:'Venue: ';font-weight:700;color:var(--muted)}
}
.uweek-footer{margin-top:24px;padding:16px;text-align:center;color:var(--muted);font-size:.78rem;border-top:1px solid var(--line);background:var(--card)}
.uweek-footer a{color:var(--primary);font-weight:700;text-decoration:none}
.uweek-footer a:hover{text-decoration:underline}
#toast{position:fixed;left:50%;bottom:18px;transform:translateX(-50%) translateY(14px);background:#0f172a;color:#fff;padding:9px 12px;border-radius:10px;font-weight:700;font-size:.78rem;opacity:0;pointer-events:none;transition:.2s;z-index:60}
#toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
</style>
</head>
<body>
<nav class="uweek-topbar" aria-label="University Week 2026 navigation">
  <div class="uweek-topbar-inner">
    <a href="<?php echo $base_path; ?>uweek/" class="uweek-brand" aria-label="University Week 2026 Home">
      <img src="<?php echo $base_path; ?>assets/images/Logos/logo.png" alt="UPHSL">
      <span class="uweek-brand-text"><strong>University Week 2026</strong><span>UPHSL</span></span>
    </a>
    <div style="display:flex;gap:8px;align-items:center;">
      <a href="<?php echo $base_path; ?>" class="btn-top">Back to Website</a>
      <button class="btn-top gold" type="button" onclick="shareSchedule()">Share</button>
    </div>
  </div>
</nav>
<section class="uweek-hero" aria-label="University Week banner"></section>

<div class="uweek-subnav" aria-label="Section navigation">
  <a href="<?php echo $base_path; ?>uweek/">Brackets</a>
  <a href="<?php echo $base_path; ?>uweek/schedules.php" class="active">Schedules</a>
</div>

<nav class="uweek-daynav" aria-label="Day navigation">
  <div class="uweek-daynav-inner" id="dayNav">
    <?php foreach ($schedule as $idx => $day): $anchor = 'day-' . $idx; ?>
      <a class="day-pill" href="#<?php echo $anchor; ?>"><?php echo htmlspecialchars($day['day']); ?> • <?php echo htmlspecialchars($day['date']); ?></a>
    <?php endforeach; ?>
  </div>
</nav>

<?php if (!$uweek_enabled): ?>
<div class="uweek-wrap">
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:32px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,.05);">
    <h3 style="margin:0 0 8px;color:var(--primary);font-family:'Barlow Semi Condensed',sans-serif;text-transform:uppercase;">University Week 2026 is currently unavailable</h3>
    <p style="color:var(--muted);max-width:560px;margin:0 auto;">The University Week app is currently turned off for public viewing. Please check back later.</p>
  </div>
</div>
<?php else: ?>
<div class="uweek-wrap">
  <?php foreach ($schedule as $idx => $day): $anchor = 'day-' . $idx; ?>
  <section class="day-section" id="<?php echo $anchor; ?>">
    <div class="day-head">
      <h2><?php echo htmlspecialchars($day['day'] . ' – ' . $day['weekday']); ?></h2>
      <span class="date"><?php echo htmlspecialchars($day['date']); ?></span>
    </div>
    <table class="schedule-table" aria-label="Schedule for <?php echo htmlspecialchars($day['day']); ?>">
      <thead>
        <tr><th class="col-time">Time</th><th>Event</th><th>Venue</th></tr>
      </thead>
      <tbody>
        <?php foreach ($day['events'] as $ev): ?>
        <tr>
          <td class="col-time"><?php echo htmlspecialchars($ev['time']); ?></td>
          <td class="col-event"><?php echo htmlspecialchars($ev['event']); ?></td>
          <td class="col-venue"><?php echo htmlspecialchars($ev['venue']); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<footer class="uweek-footer">
  <div>© <?php echo date('Y'); ?> University of Perpetual Help System Laguna • <a href="<?php echo $base_path; ?>">Back to Website</a> • <a href="<?php echo $base_path; ?>uweek/">University Week 2026</a></div>
</footer>

<div id="toast" role="status" aria-live="polite"></div>
<script>
const shareUrl = <?php echo json_encode($shareUrl); ?>;
function showToast(m){const t=document.getElementById('toast');t.textContent=m;t.classList.add('show');clearTimeout(t._h);t._h=setTimeout(()=>t.classList.remove('show'),2200);}
function shareSchedule(){
  const title = 'University Week 2026 – Schedule';
  if(navigator.share){ navigator.share({title, url: shareUrl}).catch(()=>{}); }
  else if(navigator.clipboard){ navigator.clipboard.writeText(shareUrl).then(()=>showToast('Link copied: '+shareUrl)); }
  else prompt('Copy link:', shareUrl);
}
// Real tabs – only one day visible at a time
const pills = document.querySelectorAll('.day-pill');
const sections = document.querySelectorAll('.day-section');
function showDay(id, push=true){
  pills.forEach(p=>p.classList.toggle('active', p.getAttribute('href') === '#'+id));
  sections.forEach(s=>{
    const isActive = s.id === id;
    s.hidden = !isActive;
    s.classList.toggle('active', isActive);
  });
  if(push) history.replaceState(null,'','#'+id);
  // keep day nav visible – scroll wrap into view slightly
  const wrap = document.querySelector('.uweek-wrap');
  if(wrap) wrap.scrollIntoView({behavior:'smooth', block:'start'});
}
pills.forEach(p=>{
  p.addEventListener('click', (e)=>{
    e.preventDefault();
    const id = p.getAttribute('href').slice(1);
    showDay(id);
  });
});
// Init: hash or Day 0
let initial = location.hash ? location.hash.slice(1) : 'day-0';
if(!document.getElementById(initial)) initial = 'day-0';
showDay(initial, false);
// Also handle back/forward
window.addEventListener('hashchange', ()=>{
  const hid = location.hash.slice(1);
  if(document.getElementById(hid)) showDay(hid, false);
});
</script>
</body>
</html>
