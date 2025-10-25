<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
?>
<section class="toolbar"><h2 class="title">Analytics</h2></section>
<section>
  <canvas id="perMonth" height="120"></canvas>
  <canvas id="daily" height="120" style="margin-top:24px"></canvas>
  <canvas id="favVs" height="120" style="margin-top:24px"></canvas>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
async function loadChart(id, url, transform){
  const ctx = document.getElementById(id).getContext('2d');
  const res = await fetch(url);
  const data = await res.json();
  const cfg = transform(data);
  new Chart(ctx, cfg);
}
const base = (window.APP_BASE || '') + '/api/stats_api.php';
loadChart('perMonth', base + '?type=per_month', (rows)=>({ type:'bar', data:{ labels: rows.map(r=>r.ym), datasets:[{ label:'Catatan per Bulan', data: rows.map(r=>Number(r.cnt)), backgroundColor:'#111827' }] } }));
loadChart('daily', base + '?type=daily_activity', (rows)=>({ type:'line', data:{ labels: rows.map(r=>r.d), datasets:[{ label:'Aktivitas Harian (30 hari)', data: rows.map(r=>Number(r.cnt)), borderColor:'#111827' }] } }));
loadChart('favVs', base + '?type=fav_vs_normal', (rows)=>({ type:'doughnut', data:{ labels: rows.map(r=> r.fav==1?'Favorit':'Biasa'), datasets:[{ data: rows.map(r=>Number(r.cnt)), backgroundColor:['#f59e0b','#6b7280'] }] } }));
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
