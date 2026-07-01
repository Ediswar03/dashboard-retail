<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DataLens Pro — Retail Analytics</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #F8FAFC;
  --s1: #FFFFFF;
  --s2: #F1F5F9;
  --s3: #E2E8F0;
  --s4: #CBD5E1;
  --blue: #4F46E5;
  --blue-dim: rgba(79,70,229,.12);
  --blue-border: rgba(79,70,229,.25);
  --green: #059669;
  --green-dim: rgba(5,150,105,.1);
  --yellow: #D97706;
  --yellow-dim: rgba(217,119,6,.1);
  --red: #E11D48;
  --red-dim: rgba(225,29,72,.1);
  --purple: #7C3AED;
  --purple-dim: rgba(124,58,237,.1);
  --teal: #0D9488;
  --t1: #0F172A;
  --t2: #475569;
  --t3: #94A3B8;
  --border: #E2E8F0;
  --border2: #CBD5E1;
  --mono: 'JetBrains Mono', monospace;
  --sans: 'Inter', sans-serif;
  --r: 8px;
  --r2: 12px;
  --sidebar-w: 240px;
  --topbar-h: 60px;
  --transition: .25s cubic-bezier(.4,0,.2,1);
  --shadow: 0 4px 6px -1px rgba(0,0,0,.05), 0 2px 4px -2px rgba(0,0,0,.05);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; background: var(--bg); color: var(--t1); font-family: var(--sans); font-size: 13px; overflow: hidden; }

/* ━━━━━━━━━━━━━━ LAYOUT ━━━━━━━━━━━━━━ */
.app { display: flex; height: 100vh; }
.sidebar {
  width: var(--sidebar-w); min-width: var(--sidebar-w);
  background: var(--s1); border-right: 1px solid var(--border);
  display: flex; flex-direction: column; flex-shrink: 0;
  transition: width var(--transition), min-width var(--transition);
  overflow: hidden; position: relative; z-index: 20;
}
.sidebar.mini { width: 56px; min-width: 56px; }
.main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }

/* ━━━━━━━━━━━━━━ SIDEBAR HEAD ━━━━━━━━━━━━━━ */
.sb-head {
  height: var(--topbar-h); padding: 0 14px;
  display: flex; align-items: center; gap: 10px;
  border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.sb-logo {
  width: 30px; height: 30px; border-radius: var(--r);
  background: linear-gradient(135deg,var(--blue),var(--teal));
  display: flex; align-items: center; justify-content: center;
  font-family: var(--mono); font-size: 12px; font-weight: 600; color: #fff;
  flex-shrink: 0; box-shadow: 0 0 16px rgba(88,166,255,.3);
}
.sb-brand { overflow: hidden; transition: opacity var(--transition), width var(--transition); }
.sb-brand h2 { font-size: 15px; font-weight: 700; color: var(--t1); white-space: nowrap; }
.sb-brand span { font-family: var(--mono); font-size: 9px; color: var(--blue); letter-spacing: .06em; text-transform: uppercase; }
.sidebar.mini .sb-brand { opacity: 0; width: 0; }

/* ━━━━━━━━━━━━━━ LIVE STATUS ━━━━━━━━━━━━━━ */
.sb-status {
  margin: 10px 10px 0;
  background: var(--green-dim); border: 1px solid rgba(63,185,80,.2);
  border-radius: var(--r); padding: 8px 10px;
  display: flex; align-items: center; gap: 8px;
  overflow: hidden; flex-shrink: 0;
}
.pulse { width: 7px; height: 7px; border-radius: 50%; background: var(--green); flex-shrink: 0;
  box-shadow: 0 0 6px var(--green); animation: pls 2s infinite; }
@keyframes pls { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(.85)} }
.sb-status-txt { font-family: var(--mono); font-size: 10px; color: var(--green); white-space: nowrap; overflow: hidden; transition: opacity var(--transition); }
.sidebar.mini .sb-status-txt { opacity: 0; }

/* ━━━━━━━━━━━━━━ NAV ━━━━━━━━━━━━━━ */
.sb-scroll { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px 0; }
.sb-scroll::-webkit-scrollbar { width: 3px; }
.sb-scroll::-webkit-scrollbar-thumb { background: var(--s4); border-radius: 3px; }
.nav-section {
  padding: 14px 14px 4px;
  font-size: 9px; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--t3);
  white-space: nowrap; overflow: hidden;
  transition: opacity var(--transition);
}
.sidebar.mini .nav-section { opacity: 0; }
.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 10px; margin: 1px 8px;
  border-radius: var(--r); cursor: pointer;
  color: var(--t2); transition: all var(--transition);
  position: relative; white-space: nowrap; overflow: hidden;
  text-decoration: none;
}
.nav-item:hover { background: var(--s2); color: var(--t1); }
.nav-item.active {
  background: var(--blue-dim); color: var(--blue);
  border: 1px solid var(--blue-border);
}
.nav-item.active .nav-ic { filter: drop-shadow(0 0 4px rgba(88,166,255,.6)); }
.nav-ic {
  width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;
  font-size: 14px; flex-shrink: 0;
}
.nav-lbl { font-size: 13px; font-weight: 500; overflow: hidden; transition: opacity var(--transition), width var(--transition); }
.sidebar.mini .nav-lbl { opacity: 0; width: 0; }
.nav-badge {
  margin-left: auto; font-family: var(--mono); font-size: 9px;
  background: var(--blue-dim); color: var(--blue);
  border: 1px solid var(--blue-border); border-radius: 20px; padding: 2px 7px;
  flex-shrink: 0; transition: opacity var(--transition);
}
.sidebar.mini .nav-badge { opacity: 0; display: none; }

/* Active indicator */
.nav-item.active::after {
  content: ''; position: absolute; left: 0; top: 25%; bottom: 25%;
  width: 2px; background: var(--blue); border-radius: 0 2px 2px 0;
}

/* ━━━━━━━━━━━━━━ SIDEBAR FOOTER ━━━━━━━━━━━━━━ */
.sb-footer {
  border-top: 1px solid var(--border); padding: 10px 8px;
  display: flex; flex-direction: column; gap: 6px; flex-shrink: 0;
}
.sb-user {
  display: flex; align-items: center; gap: 8px;
  padding: 7px 8px; border-radius: var(--r);
  background: var(--s2); overflow: hidden;
}
.sb-avatar {
  width: 26px; height: 26px; border-radius: 50%;
  background: linear-gradient(135deg,var(--purple),var(--blue));
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.sb-user-info { overflow: hidden; transition: opacity var(--transition); }
.sb-user-info p { font-size: 12px; font-weight: 600; white-space: nowrap; }
.sb-user-info span { font-family: var(--mono); font-size: 9px; color: var(--t3); white-space: nowrap; }
.sidebar.mini .sb-user-info { opacity: 0; }
.sb-toggle {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 7px; border-radius: var(--r);
  border: 1px solid var(--border2); background: transparent;
  color: var(--t2); cursor: pointer; font-size: 11px; font-family: var(--sans);
  transition: all var(--transition); white-space: nowrap; overflow: hidden; width: 100%;
}
.sb-toggle:hover { background: var(--s2); color: var(--t1); border-color: var(--s4); }
.sb-toggle-lbl { transition: opacity var(--transition); }
.sidebar.mini .sb-toggle-lbl { opacity: 0; width: 0; overflow: hidden; }

/* ━━━━━━━━━━━━━━ TOPBAR ━━━━━━━━━━━━━━ */
.topbar {
  height: var(--topbar-h); padding: 0 20px;
  background: var(--s1); border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 12px; flex-shrink: 0;
}
.topbar-title h2 { font-size: 15px; font-weight: 600; }
.topbar-title p { font-family: var(--mono); font-size: 10px; color: var(--t3); margin-top: 1px; }
.topbar-space { flex: 1; }
.clock {
  font-family: var(--mono); font-size: 12px; color: var(--t2);
  background: var(--s2); border: 1px solid var(--border2);
  border-radius: var(--r); padding: 5px 12px;
}
.data-badge {
  display: flex; align-items: center; gap: 6px;
  background: var(--s2); border: 1px solid var(--border2);
  border-radius: var(--r); padding: 5px 12px;
  font-size: 11px; color: var(--t2);
}
.data-badge b { color: var(--blue); font-family: var(--mono); }

/* ━━━━━━━━━━━━━━ BUTTONS ━━━━━━━━━━━━━━ */
.btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 14px; border-radius: var(--r);
  border: 1px solid var(--border2); background: var(--s2);
  color: var(--t1); cursor: pointer; font-size: 12px;
  font-family: var(--sans); font-weight: 500;
  transition: all var(--transition); white-space: nowrap;
}
.btn:hover { border-color: var(--s4); background: var(--s3); }
.btn-blue { background: var(--blue); border-color: var(--blue); color: #fff; }
.btn-blue:hover { background: #79BAFF; border-color: #79BAFF; }
.btn-green { background: var(--green-dim); border-color: rgba(63,185,80,.3); color: var(--green); }
.btn-green:hover { background: rgba(63,185,80,.18); }
.btn-red { background: var(--red-dim); border-color: rgba(248,81,73,.25); color: var(--red); }
.btn-sm { padding: 4px 10px; font-size: 11px; }
.btn-icon { padding: 6px 10px; }

/* ━━━━━━━━━━━━━━ CONTENT ━━━━━━━━━━━━━━ */
.content { flex: 1; overflow-y: auto; padding: 20px; }
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-track { background: var(--s1); }
.content::-webkit-scrollbar-thumb { background: var(--s4); border-radius: 3px; }
.page { display: none; animation: fadein .18s ease; }
.page.active { display: block; }
@keyframes fadein { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }

/* ━━━━━━━━━━━━━━ FILTER BAR ━━━━━━━━━━━━━━ */
.filter-bar {
  display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px;
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r2); padding: 12px 16px; margin-bottom: 18px;
  box-shadow: var(--shadow);
}
.fg { display: flex; flex-direction: column; gap: 4px; }
.fg label { font-size: 9px; font-family: var(--mono); color: var(--t3); text-transform: uppercase; letter-spacing: .08em; }
.fg select, .fg input {
  background: var(--s2); border: 1px solid var(--border2);
  border-radius: var(--r); color: var(--t1); font-size: 12px;
  padding: 6px 10px; cursor: pointer; font-family: var(--sans);
  transition: border-color var(--transition);
}
.fg select:focus, .fg input:focus { outline: none; border-color: var(--blue); }
.filter-div { width: 1px; height: 32px; background: var(--border2); flex-shrink: 0; }

/* ━━━━━━━━━━━━━━ KPI CARDS ━━━━━━━━━━━━━━ */
.kpi-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(155px,1fr)); gap: 12px; margin-bottom: 18px; }
.kpi {
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r2); padding: 16px; position: relative; overflow: hidden;
  transition: border-color var(--transition), transform var(--transition);
  cursor: default; box-shadow: var(--shadow);
}
.kpi:hover { border-color: var(--border2); transform: translateY(-1px); }
.kpi-top-bar { position: absolute; top: 0; left: 0; right: 0; height: 2px; border-radius: var(--r2) var(--r2) 0 0; }
.kpi-lbl { font-size: 11px; color: var(--t2); display: flex; align-items: center; gap: 6px; margin-bottom: 10px; }
.kpi-ico { font-size: 12px; }
.kpi-val { font-family: var(--mono); font-size: 22px; font-weight: 600; line-height: 1; margin-bottom: 8px; }
.kpi-sub { font-family: var(--mono); font-size: 10px; color: var(--t3); }
.kpi-delta { display: flex; align-items: center; gap: 4px; font-family: var(--mono); font-size: 10px; margin-top: 4px; }
.up { color: var(--green); } .dn { color: var(--red); } .nt { color: var(--t3); }

/* ━━━━━━━━━━━━━━ CARDS ━━━━━━━━━━━━━━ */
.grid2 { display: grid; grid-template-columns: repeat(auto-fit,minmax(320px,1fr)); gap: 14px; margin-bottom: 14px; }
.grid3 { display: grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap: 14px; margin-bottom: 14px; }
.card {
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r2); padding: 18px; overflow: hidden;
  box-shadow: var(--shadow);
}
.card.full { grid-column: 1/-1; }
.card-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 14px; }
.card-title { font-size: 13px; font-weight: 600; }
.card-sub { font-family: var(--mono); font-size: 10px; color: var(--t3); margin-top: 2px; }
.chip {
  font-family: var(--mono); font-size: 9px; font-weight: 600;
  padding: 3px 8px; border-radius: 20px; letter-spacing: .04em;
}
.chip-blue { background: var(--blue-dim); color: var(--blue); border: 1px solid var(--blue-border); }
.chip-green { background: var(--green-dim); color: var(--green); border: 1px solid rgba(63,185,80,.2); }
.chip-purple { background: var(--purple-dim); color: var(--purple); border: 1px solid rgba(188,140,255,.2); }
.chip-yellow { background: var(--yellow-dim); color: var(--yellow); border: 1px solid rgba(210,153,34,.2); }
.cw { position: relative; }

/* ━━━━━━━━━━━━━━ TABLE ━━━━━━━━━━━━━━ */
.tbl-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 12px; }
thead th {
  background: var(--s2); color: var(--t3);
  font-size: 9px; font-family: var(--mono); text-transform: uppercase;
  letter-spacing: .06em; padding: 8px 12px; text-align: left;
  border-bottom: 1px solid var(--border2); white-space: nowrap; position: sticky; top: 0;
}
tbody td { padding: 9px 12px; border-bottom: 1px solid rgba(33,38,45,.6); }
tbody tr:hover td { background: var(--s2); }
tbody tr:last-child td { border-bottom: none; }
.tn { font-family: var(--mono); color: var(--t3); }
.tr { text-align: right; font-family: var(--mono); }
.tc-blue { color: var(--blue); } .tc-green { color: var(--green); }

/* ━━━━━━━━━━━━━━ RFM ━━━━━━━━━━━━━━ */
.rfm-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(190px,1fr)); gap: 12px; margin-bottom: 16px; }
.rfm-card {
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r2); padding: 14px; position: relative; overflow: hidden;
  box-shadow: var(--shadow);
}
.rfm-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; }
.rfm-seg { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
.rfm-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.rfm-name { font-size: 12px; font-weight: 600; }
.rfm-cnt { font-family: var(--mono); font-size: 24px; font-weight: 700; margin-bottom: 4px; }
.rfm-rev { font-family: var(--mono); font-size: 11px; color: var(--t2); margin-bottom: 8px; }
.rfm-desc { font-size: 11px; color: var(--t3); line-height: 1.5; }
.rfm-bar { height: 3px; border-radius: 2px; margin-top: 10px; opacity: .7; }

/* ━━━━━━━━━━━━━━ BASKET ITEM ━━━━━━━━━━━━━━ */
.basket-li {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: var(--r);
  background: var(--s2); margin-bottom: 6px;
  border: 1px solid transparent; transition: border-color var(--transition);
}
.basket-li:hover { border-color: var(--border2); }
.bi-num { font-family: var(--mono); font-size: 10px; color: var(--t3); min-width: 16px; }
.bi-prod { font-size: 12px; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.bi-arrow { font-family: var(--mono); font-size: 11px; color: var(--t3); flex-shrink: 0; }
.bi-count { font-family: var(--mono); font-size: 12px; color: var(--blue); font-weight: 600; flex-shrink: 0; }

/* ━━━━━━━━━━━━━━ INSIGHT ━━━━━━━━━━━━━━ */
.ins-card {
  display: flex; gap: 14px; align-items: flex-start;
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r2); padding: 16px; margin-bottom: 10px;
  transition: border-color var(--transition);
  box-shadow: var(--shadow);
}
.ins-card:hover { border-color: var(--border2); }
.ins-ico {
  width: 38px; height: 38px; border-radius: var(--r);
  display: flex; align-items: center; justify-content: center;
  font-size: 17px; flex-shrink: 0;
}
.ins-title { font-size: 13px; font-weight: 600; margin-bottom: 5px; }
.ins-body { font-size: 12px; color: var(--t2); line-height: 1.65; }
.ins-stat { font-family: var(--mono); font-size: 11px; margin-top: 7px; }

/* ━━━━━━━━━━━━━━ PROGRESS ━━━━━━━━━━━━━━ */
.prog-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.prog-lbl { font-size: 12px; min-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.prog-track { flex: 1; height: 5px; background: var(--s3); border-radius: 3px; overflow: hidden; }
.prog-fill { height: 100%; border-radius: 3px; transition: width .6s cubic-bezier(.4,0,.2,1); }
.prog-val { font-family: var(--mono); font-size: 11px; color: var(--t2); min-width: 56px; text-align: right; }

/* ━━━━━━━━━━━━━━ EXPORT PAGE ━━━━━━━━━━━━━━ */
.exp-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(210px,1fr)); gap: 14px; margin-bottom: 20px; }
.exp-card {
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r2); padding: 22px 18px; text-align: center;
  transition: border-color var(--transition), transform var(--transition);
  box-shadow: var(--shadow);
}
.exp-card:hover { border-color: var(--border2); transform: translateY(-2px); }
.exp-ico { font-size: 34px; margin-bottom: 12px; }
.exp-title { font-size: 14px; font-weight: 600; margin-bottom: 6px; }
.exp-desc { font-size: 11px; color: var(--t2); line-height: 1.6; margin-bottom: 16px; }
.exp-card .btn { width: 100%; justify-content: center; }

/* ━━━━━━━━━━━━━━ SECTION HDR ━━━━━━━━━━━━━━ */
.shdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.stitle { font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.sdot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

/* ━━━━━━━━━━━━━━ INFO BOX ━━━━━━━━━━━━━━ */
.info-box {
  border-radius: var(--r); padding: 10px 14px;
  font-size: 12px; line-height: 1.65; margin-bottom: 14px;
}
.info-blue { background: var(--blue-dim); border: 1px solid var(--blue-border); color: var(--t2); }
.info-green { background: var(--green-dim); border: 1px solid rgba(63,185,80,.2); color: var(--t2); }

/* ━━━━━━━━━━━━━━ LEGEND ━━━━━━━━━━━━━━ */
.legend { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 10px; }
.leg { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--t2); }
.leg-dot { width: 9px; height: 9px; border-radius: 2px; flex-shrink: 0; }

/* ━━━━━━━━━━━━━━ TOAST ━━━━━━━━━━━━━━ */
.toast {
  position: fixed; bottom: 20px; right: 20px; z-index: 100;
  background: var(--s2); border: 1px solid var(--border2);
  border-left: 3px solid var(--green);
  border-radius: var(--r); padding: 10px 16px;
  font-size: 12px; display: flex; align-items: center; gap: 8px;
  transform: translateY(70px); opacity: 0; transition: all .3s cubic-bezier(.4,0,.2,1);
  box-shadow: 0 4px 24px rgba(0,0,0,.4);
}
.toast.on { transform: translateY(0); opacity: 1; }

/* ━━━━━━━━━━━━━━ SCROLLBAR ━━━━━━━━━━━━━━ */
* { scrollbar-width: thin; scrollbar-color: var(--s4) transparent; }
</style>
</head>
<body>
<div class="app">

<!-- ══════════════════════════ SIDEBAR ══════════════════════════ -->
<aside class="sidebar" id="sb">
  <div class="sb-head">
    <div class="sb-logo">DL</div>
    <div class="sb-brand">
      <h2>DataLens</h2>
      <span>PRO · v2.5</span>
    </div>
  </div>

  <div class="sb-status">
    <div class="pulse"></div>
    <span class="sb-status-txt">Live · UCI Retail Dataset</span>
  </div>

  <div class="sb-scroll">
    <div class="nav-section">Analytics</div>
    <div class="nav-item active" data-page="overview" onclick="goTo(this,'overview')">
      <div class="nav-ic">◈</div>
      <span class="nav-lbl">Overview</span>
      <span class="nav-badge">Home</span>
    </div>
    <div class="nav-item" data-page="trend" onclick="goTo(this,'trend')">
      <div class="nav-ic">↗</div>
      <span class="nav-lbl">Tren Penjualan</span>
    </div>
    <div class="nav-item" data-page="geo" onclick="goTo(this,'geo')">
      <div class="nav-ic">◎</div>
      <span class="nav-lbl">Geografi</span>
    </div>
    <div class="nav-item" data-page="products" onclick="goTo(this,'products')">
      <div class="nav-ic">▦</div>
      <span class="nav-lbl">Produk</span>
    </div>

    <div class="nav-section">Data Mining</div>
    <div class="nav-item" data-page="rfm" onclick="goTo(this,'rfm')">
      <div class="nav-ic">⬡</div>
      <span class="nav-lbl">Segmentasi RFM</span>
      <span class="nav-badge">ML</span>
    </div>
    <div class="nav-item" data-page="basket" onclick="goTo(this,'basket')">
      <div class="nav-ic">⊞</div>
      <span class="nav-lbl">Basket Analysis</span>
      <span class="nav-badge">ML</span>
    </div>
    <div class="nav-item" data-page="insights" onclick="goTo(this,'insights')">
      <div class="nav-ic">◉</div>
      <span class="nav-lbl">Insight Otomatis</span>
    </div>

    <div class="nav-section">Output</div>
    <div class="nav-item" data-page="export" onclick="goTo(this,'export')">
      <div class="nav-ic">⤓</div>
      <span class="nav-lbl">Ekspor Data</span>
    </div>
    <div class="nav-item" data-page="about" onclick="goTo(this,'about')">
      <div class="nav-ic">ℹ</div>
      <span class="nav-lbl">Tentang Dataset</span>
    </div>
  </div>

  <div class="sb-footer">
    <div class="sb-user">
      <div class="sb-avatar">AV</div>
      <div class="sb-user-info">
        <p>Analyst</p>
        <span>DATA ANALYTICS · 2026</span>
      </div>
    </div>
    <button class="sb-toggle" onclick="toggleSB()">
      <span id="sb-icon">◀</span>
      <span class="sb-toggle-lbl" id="sb-lbl">Collapse</span>
    </button>
  </div>
</aside>

<!-- ══════════════════════════ MAIN ══════════════════════════ -->
<div class="main">
  <div class="topbar">
    <div class="topbar-title">
      <h2 id="ptitle">Overview</h2>
      <p id="psub">Ringkasan performa keseluruhan</p>
    </div>
    <div class="topbar-space"></div>
    <div class="data-badge"><b>522,573</b> rows · UCI 2015</div>
    <div class="clock" id="clk">00:00:00</div>
    <button class="btn btn-green btn-sm" onclick="goToExport()">⤓ Ekspor</button>
    <button class="btn btn-blue btn-sm" onclick="refreshAll()">↺ Refresh</button>
  </div>

  <div class="content" id="content">

    <!-- ════ OVERVIEW ════ -->
    <div class="page active" id="pg-overview">
      <div class="filter-bar">
        <div class="fg">
          <label>Periode</label>
          <select id="f-period" onchange="applyF()">
            <option value="all">Des 2010 – Des 2011 (Semua)</option>
            <option value="h1">2011 Semester 1 (Jan–Jun)</option>
            <option value="h2">2011 Semester 2 (Jul–Des)</option>
            <option value="q4">2011 Q4 (Okt–Des)</option>
          </select>
        </div>
        <div class="filter-div"></div>
        <div class="fg">
          <label>Wilayah</label>
          <select id="f-region" onchange="applyF()">
            <option value="all">Semua Negara</option>
            <option value="uk">United Kingdom</option>
            <option value="intl">Internasional</option>
          </select>
        </div>
        <div class="filter-div"></div>
        <div class="fg" style="align-self:flex-end">
          <button class="btn btn-sm" onclick="resetF()">↺ Reset</button>
        </div>
      </div>

      <div class="kpi-row" id="kpi-row"></div>

      <div class="grid2">
        <div class="card full">
          <div class="card-head">
            <div><div class="card-title">Tren Revenue Bulanan</div><div class="card-sub">Des 2010 – Des 2011 · GBP (£)</div></div>
            <span class="chip chip-blue">Line Chart</span>
          </div>
          <div class="cw" style="height:210px"><canvas id="c-ov-trend" role="img" aria-label="Tren revenue bulanan"></canvas></div>
          <div class="legend" id="leg-trend"></div>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title">Distribusi Nilai Transaksi</div><span class="chip chip-blue">Histogram</span></div>
          <div class="cw" style="height:200px"><canvas id="c-hist" role="img" aria-label="Histogram nilai transaksi"></canvas></div>
          <p style="font-size:10px;color:var(--t3);font-family:var(--mono);margin-top:8px">Median £10 · Avg £15 · Right-skewed (B2B dominant)</p>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title">Invoice per Jam (24h)</div><span class="chip chip-green">Bar Chart</span></div>
          <div class="cw" style="height:200px"><canvas id="c-hourly" role="img" aria-label="Invoice per jam"></canvas></div>
          <p style="font-size:10px;color:var(--t3);font-family:var(--mono);margin-top:8px">Peak: 12:00 · Pola jam kerja kantor · 0 transaksi Sabtu</p>
        </div>
      </div>
    </div>

    <!-- ════ TREND ════ -->
    <div class="page" id="pg-trend">
      <div class="card" style="margin-bottom:14px">
        <div class="card-head"><div><div class="card-title">Revenue Bulanan (£)</div><div class="card-sub">Seluruh periode analisis</div></div><span class="chip chip-blue">Trend</span></div>
        <div class="cw" style="height:240px"><canvas id="c-tr-rev" role="img" aria-label="Revenue bulanan"></canvas></div>
      </div>
      <div class="grid2">
        <div class="card">
          <div class="card-head"><div class="card-title">Invoice per Bulan</div></div>
          <div class="cw" style="height:190px"><canvas id="c-tr-ord" role="img" aria-label="Invoice per bulan"></canvas></div>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title">Revenue per Hari Kerja</div></div>
          <div class="cw" style="height:190px"><canvas id="c-tr-dow" role="img" aria-label="Revenue per hari"></canvas></div>
        </div>
      </div>
      <div class="card">
        <div class="card-head"><div class="card-title">Growth Rate Bulanan (%)</div></div>
        <div class="cw" style="height:150px"><canvas id="c-tr-growth" role="img" aria-label="Growth rate"></canvas></div>
        <p style="font-size:10px;color:var(--t3);font-family:var(--mono);margin-top:8px">Nov 2011 +31.6% (peak). Des 2011 turun karena data hanya s.d. 9 Des 2011 (tidak lengkap).</p>
      </div>
    </div>

    <!-- ════ GEO ════ -->
    <div class="page" id="pg-geo">
      <div class="grid2">
        <div class="card">
          <div class="card-head"><div><div class="card-title">Top 10 Negara (Revenue)</div><div class="card-sub">Termasuk UK</div></div><span class="chip chip-blue">All</span></div>
          <div class="cw" style="height:260px"><canvas id="c-geo-all" role="img" aria-label="Revenue per negara"></canvas></div>
        </div>
        <div class="card">
          <div class="card-head"><div><div class="card-title">Top 10 Negara (Tanpa UK)</div><div class="card-sub">Pasar internasional</div></div><span class="chip chip-green">Intl</span></div>
          <div class="cw" style="height:260px"><canvas id="c-geo-nouk" role="img" aria-label="Revenue non-UK"></canvas></div>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title">UK vs Internasional (Invoice)</div></div>
          <div class="cw" style="height:200px"><canvas id="c-geo-pie" role="img" aria-label="UK vs internasional"></canvas></div>
          <div class="legend" id="leg-geo"></div>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title">Share Revenue Negara</div></div>
          <div id="geo-prog" style="max-height:220px;overflow-y:auto"></div>
        </div>
      </div>
    </div>

    <!-- ════ PRODUCTS ════ -->
    <div class="page" id="pg-products">
      <div class="card" style="margin-bottom:14px">
        <div class="card-head"><div><div class="card-title">Top 10 Produk berdasarkan Revenue</div><div class="card-sub">Seluruh periode</div></div><span class="chip chip-blue">Bar</span></div>
        <div class="cw" style="height:300px"><canvas id="c-prod" role="img" aria-label="Top produk revenue"></canvas></div>
      </div>
      <div class="card">
        <div class="card-head"><div class="card-title">Tabel Lengkap Top 20 Produk</div></div>
        <div class="tbl-wrap" style="max-height:340px;overflow-y:auto">
          <table><thead><tr>
            <th>#</th><th>Produk</th>
            <th style="text-align:right">Revenue (£)</th>
            <th style="text-align:right">Unit Terjual</th>
            <th style="text-align:right">Orders</th>
            <th style="text-align:right">Avg/Order</th>
          </tr></thead><tbody id="prod-tbody"></tbody></table>
        </div>
      </div>
    </div>

    <!-- ════ RFM ════ -->
    <div class="page" id="pg-rfm">
      <div class="shdr">
        <div class="stitle"><span class="sdot" style="background:var(--purple)"></span>Customer Segmentation — RFM Analysis</div>
        <span class="chip chip-purple">Data Mining</span>
      </div>
      <div class="info-box info-blue">
        <strong style="color:var(--blue)">Metode RFM:</strong> Setiap pelanggan diberi skor 1–5 berdasarkan
        <em>Recency</em> (seberapa baru transaksi terakhir), <em>Frequency</em> (seberapa sering bertransaksi),
        dan <em>Monetary</em> (total nilai pembelian kumulatif). Kombinasi skor mengklasifikasikan pelanggan
        ke segmen strategis yang menjadi dasar keputusan retensi, reaktivasi, dan akuisisi pelanggan baru.
      </div>
      <div class="rfm-grid" id="rfm-grid"></div>
      <div class="grid2">
        <div class="card">
          <div class="card-head"><div class="card-title">Distribusi Segmen (Pelanggan)</div></div>
          <div class="cw" style="height:230px"><canvas id="c-rfm-cnt" role="img" aria-label="RFM count"></canvas></div>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title">Distribusi Segmen (Revenue £)</div></div>
          <div class="cw" style="height:230px"><canvas id="c-rfm-rev" role="img" aria-label="RFM revenue"></canvas></div>
        </div>
      </div>
    </div>

    <!-- ════ BASKET ════ -->
    <div class="page" id="pg-basket">
      <div class="shdr">
        <div class="stitle"><span class="sdot" style="background:var(--green)"></span>Market Basket Analysis</div>
        <span class="chip chip-green">Association Rules</span>
      </div>
      <div class="info-box info-green">
        <strong style="color:var(--green)">Metode Co-occurrence:</strong> Menghitung pasangan produk yang
        paling sering muncul bersama dalam satu invoice. Dasar untuk algoritma Apriori dan FP-Growth.
        Digunakan untuk rekomendasi produk, strategi bundling, dan product placement di platform online.
      </div>
      <div class="grid2">
        <div class="card">
          <div class="card-head"><div><div class="card-title">Top 10 Pasangan Produk</div><div class="card-sub">Co-occurrence count</div></div></div>
          <div class="cw" style="height:280px"><canvas id="c-basket" role="img" aria-label="Basket analysis"></canvas></div>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title">Daftar Asosiasi Produk</div></div>
          <div id="basket-lst" style="max-height:300px;overflow-y:auto"></div>
        </div>
      </div>
    </div>

    <!-- ════ INSIGHTS ════ -->
    <div class="page" id="pg-insights">
      <div class="shdr">
        <div class="stitle"><span class="sdot" style="background:var(--yellow)"></span>Insight Otomatis Berbasis Data</div>
      </div>
      <div id="ins-list"></div>
    </div>

    <!-- ════ EXPORT ════ -->
    <div class="page" id="pg-export">
      <div class="shdr">
        <div class="stitle"><span class="sdot" style="background:var(--blue)"></span>Ekspor Data & Laporan</div>
      </div>
      <div class="exp-grid">
        <div class="exp-card">
          <div class="exp-ico">📄</div>
          <div class="exp-title">Laporan PDF</div>
          <div class="exp-desc">Laporan eksekutif lengkap: KPI, tabel tren, top produk, dan RFM segmentasi dalam format PDF siap cetak dan presentasi.</div>
          <button class="btn btn-blue" onclick="exportPDF()">⤓ Unduh PDF</button>
        </div>
        <div class="exp-card">
          <div class="exp-ico">📊</div>
          <div class="exp-title">Excel (.xlsx)</div>
          <div class="exp-desc">Multi-sheet: Ringkasan, Tren Bulanan, Negara, Produk, RFM Segmen, dan Basket Analysis. Siap diolah lanjut.</div>
          <button class="btn btn-green" onclick="exportXLSX()">⤓ Unduh Excel</button>
        </div>
        <div class="exp-card">
          <div class="exp-ico">📋</div>
          <div class="exp-title">CSV — Top Produk</div>
          <div class="exp-desc">Tabel top produk dalam format CSV ringan untuk integrasi ke Google Sheets, Power BI, atau Tableau.</div>
          <button class="btn" onclick="exportCSV()">⤓ Unduh CSV</button>
        </div>
        <div class="exp-card">
          <div class="exp-ico">{ }</div>
          <div class="exp-title">JSON — Data Agregat</div>
          <div class="exp-desc">Semua data agregat dashboard dalam format JSON terstruktur untuk integrasi ke sistem atau API lain.</div>
          <button class="btn" onclick="exportJSON()">⤓ Unduh JSON</button>
        </div>
      </div>
    </div>

    <!-- ════ ABOUT ════ -->
    <div class="page" id="pg-about">
      <div class="card">
        <div class="card-head"><div class="card-title">Tentang Dataset</div></div>
        <div style="display:grid;gap:12px">
          <div style="display:grid;grid-template-columns:160px 1fr;gap:8px 16px;font-size:12px;line-height:1.7">
            <span style="color:var(--t3);font-family:var(--mono)">Nama</span><span>Online Retail Dataset</span>
            <span style="color:var(--t3);font-family:var(--mono)">Sumber</span><span style="color:var(--blue)">UCI Machine Learning Repository</span>
            <span style="color:var(--t3);font-family:var(--mono)">URL</span><span style="font-family:var(--mono);font-size:11px;color:var(--blue)">archive.ics.uci.edu/dataset/352/online+retail</span>
            <span style="color:var(--t3);font-family:var(--mono)">Donatur</span><span>Daqing Chen, London South Bank University (2015)</span>
            <span style="color:var(--t3);font-family:var(--mono)">DOI</span><span style="font-family:var(--mono);color:var(--blue)">10.24432/C5BW33</span>
            <span style="color:var(--t3);font-family:var(--mono)">Lisensi</span><span>CC BY 4.0 — bebas untuk riset & edukasi</span>
            <span style="color:var(--t3);font-family:var(--mono)">Periode</span><span>1 Desember 2010 – 9 Desember 2011</span>
            <span style="color:var(--t3);font-family:var(--mono)">Deskripsi</span><span>Transaksi nyata peritel online UK; menjual gift items ke >30 negara; pelanggan B2B dan retail</span>
            <span style="color:var(--t3);font-family:var(--mono)">Raw rows</span><span style="font-family:var(--mono)">541,909 baris</span>
            <span style="color:var(--t3);font-family:var(--mono)">Clean rows</span><span style="font-family:var(--mono);color:var(--green)">522,573 baris (setelah cleaning)</span>
            <span style="color:var(--t3);font-family:var(--mono)">Produk unik</span><span style="font-family:var(--mono)">3,915</span>
            <span style="color:var(--t3);font-family:var(--mono)">Negara</span><span style="font-family:var(--mono)">38</span>
          </div>
          <div style="background:var(--s2);border-radius:var(--r);padding:12px 14px;font-size:11px;color:var(--t3);font-family:var(--mono);line-height:1.7">
            Chen, D. (2015). Online Retail [Dataset]. UCI Machine Learning Repository.<br>
            https://doi.org/10.24432/C5BW33
          </div>
        </div>
      </div>
    </div>

  </div><!-- /content -->
</div><!-- /main -->
</div><!-- /app -->

<div class="toast" id="toast">✓ <span id="tmsg">OK</span></div>

<script>
// ══════════════════════════════════════════════
//  DATA (agregat dari 522.573 baris UCI dataset)
// ══════════════════════════════════════════════
const D = {
  summary:{total_revenue:10272791.33,total_invoices:19778,total_customers:4335,total_products:3915,total_countries:38,total_units:5561422,avg_order:519.4,return_rate:1.71},
  monthly:[
    {p:'2010-12',rev:789256.28,ord:1551,units:357532,g:0},
    {p:'2011-01',rev:670439.46,ord:1081,units:386741,g:-15.05},
    {p:'2011-02',rev:507866.54,ord:1093,units:282630,g:-24.25},
    {p:'2011-03',rev:690061.60,ord:1441,units:376184,g:35.87},
    {p:'2011-04',rev:515499.66,ord:1236,units:307656,g:-25.30},
    {p:'2011-05',rev:740036.33,ord:1668,units:394657,g:43.56},
    {p:'2011-06',rev:737683.99,ord:1525,units:388129,g:-0.32},
    {p:'2011-07',rev:688252.67,ord:1452,units:399291,g:-6.70},
    {p:'2011-08',rev:735370.22,ord:1340,units:420702,g:6.85},
    {p:'2011-09',rev:1028345.38,ord:1818,units:568701,g:39.84},
    {p:'2011-10',rev:1103363.97,ord:2006,units:619599,g:7.30},
    {p:'2011-11',rev:1452115.98,ord:2751,units:746952,g:31.61},
    {p:'2011-12',rev:614499.25,ord:816,units:312648,g:-57.68},
  ],
  countries:[
    {c:'United Kingdom',rev:8750015.68,ord:17906},
    {c:'Netherlands',rev:283889.34,ord:93},
    {c:'Eire',rev:270850.86,ord:282},
    {c:'Germany',rev:205381.15,ord:443},
    {c:'France',rev:184493.00,ord:382},
    {c:'Australia',rev:138103.81,ord:56},
    {c:'Spain',rev:55706.56,ord:88},
    {c:'Switzerland',rev:53065.60,ord:50},
    {c:'Japan',rev:37416.37,ord:19},
    {c:'Belgium',rev:36927.34,ord:98},
    {c:'Sweden',rev:36828.83,ord:34},
    {c:'Norway',rev:32454.64,ord:32},
    {c:'Portugal',rev:26951.11,ord:50},
    {c:'Channel Islands',rev:19997.54,ord:25},
    {c:'Finland',rev:18344.88,ord:40},
  ],
  products:[
    {n:'Regency Cakestand 3 Tier',rev:174156.54,qty:13851,ord:1988},
    {n:'Paper Craft, Little Birdie',rev:168469.60,qty:80995,ord:1},
    {n:'White Hanging Heart T-Light Holder',rev:106236.72,qty:37872,ord:2256},
    {n:'Party Bunting',rev:99445.23,qty:18283,ord:1685},
    {n:'Jumbo Bag Red Retrospot',rev:94159.81,qty:48371,ord:2089},
    {n:'Medium Ceramic Top Storage Jar',rev:81700.92,qty:78033,ord:247},
    {n:'Rabbit Night Light',rev:66870.03,qty:30739,ord:994},
    {n:'Paper Chain Kit 50s Christmas',rev:64875.59,qty:19329,ord:1160},
    {n:'Assorted Colour Bird Ornament',rev:58927.62,qty:36362,ord:1455},
    {n:'Chilli Lights',rev:54096.36,qty:10302,ord:661},
    {n:'Spotty Bunting',rev:42513.48,qty:8320,ord:1140},
    {n:'Jumbo Bag Pink Polkadot',rev:42401.01,qty:21448,ord:1218},
    {n:'Black Record Cover Frame',rev:40633.38,qty:11651,ord:375},
    {n:'Doormat Keep Calm And Come In',rev:38133.64,qty:5487,ord:728},
    {n:'Set Of 3 Cake Tins Pantry Design',rev:38108.89,qty:7483,ord:1385},
    {n:'Jam Making Set With Jars',rev:37082.13,qty:8695,ord:1132},
    {n:'Wood Black Board Ant White Finish',rev:35966.92,qty:6012,ord:685},
    {n:'Lunch Bag Red Retrospot',rev:35572.36,qty:19232,ord:1564},
    {n:'Popcorn Holder',rev:34288.67,qty:36749,ord:803},
    {n:'Pack Of 72 Retrospot Cake Cases',rev:33108.00,qty:36396,ord:1204},
  ],
  hourly:[
    {h:6,ord:1},{h:7,ord:29},{h:8,ord:565},{h:9,ord:1471},
    {h:10,ord:2339},{h:11,ord:2374},{h:12,ord:3206},{h:13,ord:2731},
    {h:14,ord:2420},{h:15,ord:2311},{h:16,ord:1315},{h:17,ord:662},
    {h:18,ord:192},{h:19,ord:145},{h:20,ord:18},
  ],
  dow:[
    {d:'Mon',rev:1683269},{d:'Tue',rev:2098657},{d:'Wed',rev:1782499},
    {d:'Thu',rev:2131756},{d:'Fri',rev:1778299},{d:'Sun',rev:798312},
  ],
  rfm:[
    {seg:'Champions',cnt:1136,rev:5827878,pct_c:26.2,pct_r:66.0,desc:'Beli baru-baru ini, sering, & nilai besar. Reward & engage aktif.'},
    {seg:'Lost',cnt:1068,rev:510160,pct_c:24.6,pct_r:5.8,desc:'Tidak aktif lama. Win-back campaign atau accept churn.'},
    {seg:'Loyal Customers',cnt:827,rev:1314393,pct_c:19.1,pct_r:14.9,desc:'Beli reguler dan sering. Kandidat upsell & cross-sell.'},
    {seg:'At Risk',cnt:637,rev:783276,pct_c:14.7,pct_r:8.9,desc:'Dulu aktif, kini menghilang. Re-engage segera.'},
    {seg:'Potential Loyalists',cnt:347,rev:159192,pct_c:8.0,pct_r:1.8,desc:'Beberapa kali beli. Loyalty program bisa mengunci mereka.'},
    {seg:'New Customers',cnt:319,rev:142328,pct_c:7.4,pct_r:1.6,desc:'Baru pertama kali beli. Onboarding & nurture intensif.'},
  ],
  basket:[
    {a:'Jumbo Bag Pink Polkadot',b:'Lunch Bag Red Retrospot',n:247},
    {a:'Charlotte Bag Suki Design',b:'Lunch Bag Red Retrospot',n:234},
    {a:'Ivory Kitchen Scales',b:'Red Kitchen Scales',n:232},
    {a:'Charlotte Bag Suki Design',b:'Red Retrospot Charlotte Bag',n:209},
    {a:'Lunch Bag Black Skull',b:'Lunch Bag Red Retrospot',n:182},
    {a:'Jumbo Bag Pink Polkadot',b:'Jumbo Bag Toys',n:172},
    {a:'Lunch Bag Red Retrospot',b:'Red Retrospot Charlotte Bag',n:161},
    {a:'Lunch Bag Red Retrospot',b:'Lunch Bag Suki Design',n:161},
    {a:'Regency Cakestand 3 Tier',b:'Roses Regency Teacup And Sauce',n:156},
    {a:'Charlotte Bag Apples Design',b:'Charlotte Bag Suki Design',n:137},
  ],
  hist:[
    {x:3.6,n:213704},{x:10.8,n:108861},{x:17.9,n:113120},{x:25.1,n:22753},
    {x:32.2,n:21477},{x:39.4,n:7808},{x:46.5,n:5062},{x:53.7,n:3134},
    {x:60.8,n:3749},{x:68.0,n:3621},{x:75.1,n:2027},{x:82.3,n:2029},
    {x:89.4,n:1592},{x:96.6,n:963},{x:103.7,n:1891},{x:110.9,n:263},
    {x:118.0,n:657},{x:125.2,n:733},{x:132.3,n:470},{x:139.5,n:559},
    {x:146.6,n:570},{x:153.8,n:432},{x:160.9,n:468},{x:168.1,n:390},
    {x:175.2,n:581},
  ],
};

// ══════════════════════════════════════════════
//  COLORS & HELPERS
// ══════════════════════════════════════════════
const C={blue:'#4F46E5',green:'#059669',yellow:'#D97706',red:'#E11D48',purple:'#7C3AED',teal:'#0D9488',grey:'#94A3B8'};
const PALETTE=[C.blue,C.green,C.purple,C.yellow,C.red,C.teal,C.grey,'#EA580C','#DB2777','#0284C7'];
const SEG_C={Champions:C.blue,'Loyal Customers':C.green,'New Customers':C.purple,'At Risk':C.yellow,Lost:C.red,'Potential Loyalists':C.teal};
const charts={};

function gbp(n){if(n>=1e6)return'£'+(n/1e6).toFixed(2)+'M';if(n>=1e3)return'£'+(n/1e3).toFixed(1)+'K';return'£'+Math.round(n).toLocaleString();}
function fmt(n){return Math.round(n).toLocaleString('en-GB');}
function mkChart(id,type,data,opts={}){
  if(charts[id])charts[id].destroy();
  const el=document.getElementById(id);
  if(!el)return;
  charts[id]=new Chart(el,{type,data,options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},...opts}});
}
const grid={color:'rgba(45,55,72,.05)'};
const tickStyle={color:'#A0AEC0',font:{size:10}};

// ══════════════════════════════════════════════
//  SIDEBAR & NAV
// ══════════════════════════════════════════════
let sbOpen=true;
function toggleSB(){
  sbOpen=!sbOpen;
  document.getElementById('sb').classList.toggle('mini',!sbOpen);
  document.getElementById('sb-icon').textContent=sbOpen?'◀':'▶';
  document.getElementById('sb-lbl').textContent=sbOpen?'Collapse':'Expand';
}

const PAGE_META={
  overview:{t:'Overview',s:'Ringkasan performa keseluruhan'},
  trend:{t:'Tren Penjualan',s:'Analisis pola waktu & pertumbuhan'},
  geo:{t:'Geografi',s:'Distribusi penjualan per negara'},
  products:{t:'Produk',s:'Performa & peringkat produk'},
  rfm:{t:'Segmentasi RFM',s:'Customer segmentation · Data Mining'},
  basket:{t:'Basket Analysis',s:'Asosiasi produk · Association Rules'},
  insights:{t:'Insight Otomatis',s:'Temuan berbasis data'},
  export:{t:'Ekspor Data',s:'PDF · Excel · CSV · JSON'},
  about:{t:'Tentang Dataset',s:'Sumber data & sitasi'},
};

function goTo(el,id){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  document.getElementById('pg-'+id).classList.add('active');
  el.classList.add('active');
  const m=PAGE_META[id];
  document.getElementById('ptitle').textContent=m.t;
  document.getElementById('psub').textContent=m.s;
  renderPage(id);
}
function goToExport(){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  document.getElementById('pg-export').classList.add('active');
  document.querySelector('[data-page="export"]').classList.add('active');
  document.getElementById('ptitle').textContent='Ekspor Data';
  document.getElementById('psub').textContent='PDF · Excel · CSV · JSON';
}

// ══════════════════════════════════════════════
//  CLOCK
// ══════════════════════════════════════════════
function tick(){document.getElementById('clk').textContent=new Date().toLocaleTimeString('id-ID');}
setInterval(tick,1000);tick();

// ══════════════════════════════════════════════
//  TOAST
// ══════════════════════════════════════════════
function toast(msg){
  const t=document.getElementById('toast');
  document.getElementById('tmsg').textContent=msg;
  t.classList.add('on');
  setTimeout(()=>t.classList.remove('on'),3000);
}

// ══════════════════════════════════════════════
//  FILTER
// ══════════════════════════════════════════════
let fPeriod='all',fRegion='all';

function getDatesFromPeriod(p) {
    if (p === 'h1') return { start: '2011-01-01', end: '2011-06-30' };
    if (p === 'h2') return { start: '2011-07-01', end: '2011-12-31' };
    if (p === 'q4') return { start: '2011-10-01', end: '2011-12-31' };
    return { start: '', end: '' };
}

async function refreshDynamic() {
    const dates = getDatesFromPeriod(fPeriod);
    const params = new URLSearchParams();
    if (dates.start) params.append('start_date', dates.start);
    if (dates.end) params.append('end_date', dates.end);
    if (fRegion !== 'all') params.append('region', fRegion);
    
    try {
        document.querySelector('.sb-status-txt').textContent = 'Live · Updating...';
        const res = await fetch(`/dashboard/data?${params.toString()}`);
        const data = await res.json();
        
        D.summary = data.summary;
        D.monthly = data.monthly;
        D.countries = data.countries;
        D.products = data.products;
        D.hourly = data.hourly;
        D.dow = data.dow;
        
        renderKPI();
        renderOV();
        renderTrend();
        renderGeo();
        renderProducts();
        
        document.querySelector('.sb-status-txt').textContent = 'Live · UCI Retail Dataset';
    } catch(e) {
        console.error(e);
        toast('Gagal memuat data dari server');
    }
}

async function applyF(){
  fPeriod=document.getElementById('f-period').value;
  fRegion=document.getElementById('f-region').value;
  await refreshDynamic();
}
async function resetF(){
  document.getElementById('f-period').value='all';
  document.getElementById('f-region').value='all';
  fPeriod='all';fRegion='all';
  await refreshDynamic();
}
function getMonthsFilt() { return D.monthly; }
function getMulti() { return {r:1, o:1}; }
async function refreshAll(){ 
  await refreshDynamic();
  toast('Data diperbarui');
}

// ══════════════════════════════════════════════
//  KPI
// ══════════════════════════════════════════════
function renderKPI(){
  const months=fPeriod==='all'?D.monthly:getMonthsFilt();
  const m=getMulti();
  const rev=months.reduce((s,x)=>s+x.rev,0)*m.r;
  const ord=Math.round(months.reduce((s,x)=>s+x.ord,0)*m.o);
  const avg=ord>0?rev/ord:0;
  const units=Math.round(months.reduce((s,x)=>s+x.units,0)*m.r);
  const kpis=[
    {lbl:'Total Revenue',val:gbp(rev),sub:'GBP (£)',color:C.blue,ico:'💷',delta:'UCI Retail Dataset'},
    {lbl:'Invoice Unik',val:fmt(ord),sub:'Transaksi tercatat',color:C.green,ico:'📋',delta:'B2B + Retail'},
    {lbl:'Unit Terjual',val:fmt(units),sub:'Total produk',color:C.yellow,ico:'📦',delta:'Volume grosir tinggi'},
    {lbl:'Avg/Invoice',val:gbp(avg),sub:'Nilai rata-rata',color:C.purple,ico:'⌀',delta:'Whole-order basis'},
    {lbl:'Pelanggan',val:fmt(D.summary.total_customers),sub:'Terdaftar + GUEST',color:C.blue,ico:'👥',delta:'38 negara'},
    {lbl:'Produk Unik',val:fmt(D.summary.total_products),sub:'SKU berbeda',color:C.teal,ico:'🏷',delta:'Gift items'},
  ];
  document.getElementById('kpi-row').innerHTML=kpis.map(k=>`
    <div class="kpi">
      <div class="kpi-top-bar" style="background:${k.color}"></div>
      <div class="kpi-lbl"><span class="kpi-ico">${k.ico}</span>${k.lbl}</div>
      <div class="kpi-val" style="color:${k.color}">${k.val}</div>
      <div class="kpi-sub">${k.sub}</div>
      <div class="kpi-delta nt">${k.delta}</div>
    </div>
  `).join('');
}

// ══════════════════════════════════════════════
//  OVERVIEW CHARTS
// ══════════════════════════════════════════════
function renderOV(){
  const months=fPeriod==='all'?D.monthly:getMonthsFilt();
  const m=getMulti();

  mkChart('c-ov-trend','line',{
    labels:months.map(x=>x.p),
    datasets:[
      {data:months.map(x=>x.rev*m.r),borderColor:C.blue,backgroundColor:'rgba(88,166,255,.07)',fill:true,tension:.38,pointRadius:4,pointHoverRadius:6,pointBackgroundColor:C.blue,borderWidth:2,label:'Revenue'},
      {data:months.map(x=>x.ord*m.o*300),borderColor:C.green,tension:.38,pointRadius:3,pointBackgroundColor:C.green,borderWidth:1.5,borderDash:[5,4],label:'Orders×300'},
    ]
  },{scales:{y:{ticks:{callback:v=>gbp(v),...tickStyle},grid},x:{ticks:{...tickStyle,maxRotation:40},grid:{display:false}}},plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>ctx.datasetIndex===0?gbp(ctx.raw):fmt(ctx.raw/300)+' orders'}}}});

  document.getElementById('leg-trend').innerHTML=`
    <div class="leg"><div class="leg-dot" style="background:${C.blue}"></div>Revenue (£)</div>
    <div class="leg"><div class="leg-dot" style="background:${C.green};opacity:.7"></div>Invoice Count</div>
  `;

  mkChart('c-hist','bar',{
    labels:D.hist.map(h=>'£'+h.x.toFixed(0)),
    datasets:[{data:D.hist.map(h=>h.n),backgroundColor:'rgba(88,166,255,.3)',borderColor:C.blue,borderWidth:1,borderRadius:2,barPercentage:.92}]
  },{scales:{y:{ticks:{callback:v=>v>=1000?(v/1000).toFixed(0)+'K':v,...tickStyle},grid},x:{ticks:{...tickStyle,autoSkip:true,maxTicksLimit:8},grid:{display:false}}}});

  mkChart('c-hourly','bar',{
    labels:D.hourly.map(h=>h.h+':00'),
    datasets:[{data:D.hourly.map(h=>h.ord),backgroundColor:D.hourly.map(h=>h.h===12?C.blue:`rgba(88,166,255,.25)`),borderRadius:4}]
  },{scales:{y:{ticks:{...tickStyle},grid},x:{ticks:{...tickStyle,autoSkip:false,maxRotation:45},grid:{display:false}}}});
}

// ══════════════════════════════════════════════
//  TREND
// ══════════════════════════════════════════════
function renderTrend(){
  mkChart('c-tr-rev','line',{
    labels:D.monthly.map(m=>m.p),
    datasets:[{data:D.monthly.map(m=>m.rev),borderColor:C.blue,backgroundColor:'rgba(88,166,255,.06)',fill:true,tension:.4,pointRadius:5,pointHoverRadius:7,pointBackgroundColor:D.monthly.map(m=>m.p==='2011-11'?C.green:C.blue),borderWidth:2.5}]
  },{scales:{y:{ticks:{callback:v=>gbp(v),...tickStyle},grid},x:{ticks:{...tickStyle,maxRotation:40},grid:{display:false}}},plugins:{tooltip:{callbacks:{label:ctx=>'£'+fmt(ctx.raw)}}}});

  mkChart('c-tr-ord','bar',{
    labels:D.monthly.map(m=>m.p),
    datasets:[{data:D.monthly.map(m=>m.ord),backgroundColor:D.monthly.map(m=>m.p==='2011-11'?C.green:'rgba(63,185,80,.3)'),borderRadius:4}]
  },{scales:{y:{ticks:{...tickStyle},grid},x:{ticks:{...tickStyle,maxRotation:45,font:{size:9}},grid:{display:false}}}});

  mkChart('c-tr-dow','bar',{
    labels:D.dow.map(d=>d.d),
    datasets:[{data:D.dow.map(d=>d.rev),backgroundColor:D.dow.map(d=>d.d==='Thu'?C.yellow:'rgba(210,153,34,.3)'),borderRadius:5}]
  },{scales:{y:{ticks:{callback:v=>gbp(v),...tickStyle},grid},x:{ticks:{...tickStyle},grid:{display:false}}}});

  mkChart('c-tr-growth','bar',{
    labels:D.monthly.map(m=>m.p),
    datasets:[{data:D.monthly.map(m=>m.g),backgroundColor:D.monthly.map(m=>m.g>=0?'rgba(63,185,80,.5)':'rgba(248,81,73,.45)'),borderRadius:3}]
  },{scales:{y:{ticks:{callback:v=>v+'%',...tickStyle},grid},x:{ticks:{...tickStyle,maxRotation:45,font:{size:9}},grid:{display:false}}},plugins:{tooltip:{callbacks:{label:ctx=>(ctx.raw>=0?'+':'')+ctx.raw.toFixed(1)+'%'}}}});
}

// ══════════════════════════════════════════════
//  GEO
// ══════════════════════════════════════════════
function renderGeo(){
  const top10=D.countries.slice(0,10);
  const nouk=D.countries.filter(c=>c.c!=='United Kingdom').slice(0,10);
  const total=D.countries.reduce((s,c)=>s+c.rev,0);
  const ukRev=D.countries[0].rev;

  mkChart('c-geo-all','bar',{
    labels:top10.map(c=>c.c==='United Kingdom'?'🇬🇧 UK':c.c),
    datasets:[{data:top10.map(c=>c.rev),backgroundColor:top10.map(c=>c.c==='United Kingdom'?C.blue:'rgba(88,166,255,.35)'),borderRadius:5}]
  },{indexAxis:'y',scales:{x:{ticks:{callback:v=>gbp(v),...tickStyle},grid},y:{ticks:{...tickStyle,font:{size:11}},grid:{display:false}}}});

  mkChart('c-geo-nouk','bar',{
    labels:nouk.map(c=>c.c),
    datasets:[{data:nouk.map(c=>c.rev),backgroundColor:[C.green,...Array(9).fill('rgba(63,185,80,.3)')],borderRadius:5}]
  },{indexAxis:'y',scales:{x:{ticks:{callback:v=>gbp(v),...tickStyle},grid},y:{ticks:{...tickStyle,font:{size:11}},grid:{display:false}}}});

  mkChart('c-geo-pie','doughnut',{
    labels:['United Kingdom','Internasional'],
    datasets:[{data:[ukRev,total-ukRev],backgroundColor:[C.blue,'rgba(88,166,255,.22)'],borderColor:'#0D1117',borderWidth:2.5,hoverOffset:6}]
  },{cutout:'58%'});
  document.getElementById('leg-geo').innerHTML=`
    <div class="leg"><div class="leg-dot" style="background:${C.blue}"></div>UK ${(ukRev/total*100).toFixed(1)}%</div>
    <div class="leg"><div class="leg-dot" style="background:rgba(88,166,255,.4)"></div>Intl ${((total-ukRev)/total*100).toFixed(1)}%</div>
  `;

  const maxRev=top10[0].rev;
  document.getElementById('geo-prog').innerHTML=D.countries.slice(0,10).map(c=>`
    <div class="prog-row">
      <span class="prog-lbl">${c.c==='United Kingdom'?'🇬🇧 '+c.c:c.c}</span>
      <div class="prog-track"><div class="prog-fill" style="width:${(c.rev/maxRev*100).toFixed(1)}%;background:${c.c==='United Kingdom'?C.blue:C.green}"></div></div>
      <span class="prog-val">${gbp(c.rev)}</span>
    </div>
  `).join('');
}

// ══════════════════════════════════════════════
//  PRODUCTS
// ══════════════════════════════════════════════
function renderProducts(){
  const top10=D.products.slice(0,10);
  mkChart('c-prod','bar',{
    labels:top10.map(p=>p.n.length>30?p.n.slice(0,30)+'…':p.n),
    datasets:[{data:top10.map(p=>p.rev),backgroundColor:[C.blue,...Array(9).fill('rgba(88,166,255,.35)')],borderRadius:5}]
  },{indexAxis:'y',scales:{x:{ticks:{callback:v=>gbp(v),...tickStyle},grid},y:{ticks:{...tickStyle,font:{size:10}},grid:{display:false}}}});

  document.getElementById('prod-tbody').innerHTML=D.products.map((p,i)=>`
    <tr>
      <td class="tn" style="width:28px">${i+1}</td>
      <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${p.n}</td>
      <td class="tr tc-blue">£${fmt(p.rev)}</td>
      <td class="tr tn">${fmt(p.qty)}</td>
      <td class="tr tn">${fmt(p.ord)}</td>
      <td class="tr tn">£${fmt(p.rev/Math.max(1,p.ord))}</td>
    </tr>
  `).join('');
}

// ══════════════════════════════════════════════
//  RFM
// ══════════════════════════════════════════════
function renderRFM(){
  const total=D.rfm.reduce((s,x)=>s+x.cnt,0);
  const totalR=D.rfm.reduce((s,x)=>s+x.rev,0);
  document.getElementById('rfm-grid').innerHTML=D.rfm.map(s=>{
    const col=SEG_C[s.seg]||C.blue;
    return`<div class="rfm-card">
      <div class="rfm-card" style="border-color:${col}22">
      <div style="position:absolute;top:0;left:0;right:0;height:2px;background:${col};border-radius:12px 12px 0 0"></div>
      <div class="rfm-seg"><div class="rfm-dot" style="background:${col};box-shadow:0 0 6px ${col}80"></div><div class="rfm-name">${s.seg}</div></div>
      <div class="rfm-cnt" style="color:${col}">${fmt(s.cnt)}</div>
      <div class="rfm-rev">${gbp(s.rev)} revenue</div>
      <div class="rfm-rev">${s.pct_c}% pelanggan · ${s.pct_r}% revenue</div>
      <div class="rfm-desc">${s.desc}</div>
      <div class="rfm-bar" style="width:${s.pct_r}%;background:${col}"></div>
    </div></div>`;
  }).join('');

  const cols=D.rfm.map(s=>SEG_C[s.seg]||C.blue);
  mkChart('c-rfm-cnt','doughnut',{
    labels:D.rfm.map(s=>s.seg),
    datasets:[{data:D.rfm.map(s=>s.cnt),backgroundColor:cols,borderColor:'#0D1117',borderWidth:2,hoverOffset:8}]
  },{cutout:'54%',plugins:{legend:{display:true,position:'right',labels:{color:'#8B949E',font:{size:10},boxWidth:10,padding:10}}}});

  mkChart('c-rfm-rev','doughnut',{
    labels:D.rfm.map(s=>s.seg),
    datasets:[{data:D.rfm.map(s=>s.rev),backgroundColor:cols,borderColor:'#0D1117',borderWidth:2,hoverOffset:8}]
  },{cutout:'54%',plugins:{legend:{display:true,position:'right',labels:{color:'#8B949E',font:{size:10},boxWidth:10,padding:10}}},tooltip:{callbacks:{label:ctx=>gbp(ctx.raw)}}});
}

// ══════════════════════════════════════════════
//  BASKET
// ══════════════════════════════════════════════
function renderBasket(){
  const labels=D.basket.map(b=>b.a.slice(0,18)+'…⇄'+b.b.slice(0,18)+'…');
  mkChart('c-basket','bar',{
    labels:D.basket.map(b=>b.a.slice(0,20)+'…'),
    datasets:[{data:D.basket.map(b=>b.n),backgroundColor:D.basket.map((_,i)=>`rgba(63,185,80,${1-i*.07})`),borderRadius:4}]
  },{indexAxis:'y',scales:{x:{ticks:{...tickStyle},grid},y:{ticks:{...tickStyle,font:{size:10}},grid:{display:false}}}});

  document.getElementById('basket-lst').innerHTML=D.basket.map((b,i)=>`
    <div class="basket-li">
      <span class="bi-num">${i+1}</span>
      <span class="bi-prod">${b.a}</span>
      <span class="bi-arrow">⇄</span>
      <span class="bi-prod">${b.b}</span>
      <span class="bi-count">${b.n}×</span>
    </div>
  `).join('');
}

// ══════════════════════════════════════════════
//  INSIGHTS
// ══════════════════════════════════════════════
function renderInsights(){
  const ins=[
    {ico:'📈',cls:'blue',col:C.blue,t:'November 2011 — Puncak Revenue Tertinggi',b:'Revenue November 2011 mencapai £1.45 juta, naik 31.6% dari Oktober dan lebih dari 2× lipat bulan terlemah. Konsisten dengan musim belanja Natal untuk perusahaan gift items.',st:'Peak: £1,452,115 · Growth MoM: +31.6%'},
    {ico:'🌍',cls:'green',col:C.green,t:'UK 85.2% Revenue, Pasar Eropa Belum Tergarap Maksimal',b:'United Kingdom menyumbang £8.75 juta dari total £10.27 juta. Netherlands (£283K) dan Eire (£270K) memimpin pasar internasional, padahal pesanan dari sana jauh lebih sedikit.',st:'UK: £8.75M · Intl Top: Netherlands £283K'},
    {ico:'🏆',cls:'purple',col:C.purple,t:'Champions (26%) Menyumbang 66% Revenue',b:'1.136 pelanggan Champions membeli baru-baru ini, sering, dan bernilai besar. Mereka hanya 26% dari pelanggan terdaftar tetapi menyumbang £5.83 juta atau 66% dari revenue yang bisa dikaitkan ke pelanggan.',st:'Champions: 1,136 cust · £5.83M revenue · 66% share'},
    {ico:'⏰',cls:'yellow',col:C.yellow,t:'0 Transaksi Hari Sabtu Sepanjang 13 Bulan',b:'Seluruh transaksi terjadi Senin–Jumat dan sesekali Minggu. Tidak satu pun invoice muncul di hari Sabtu. Ini konfirmasi kuat model B2B/grosir yang mengikuti jam kerja, bukan platform konsumer 24/7.',st:'Peak: Kamis (£2.13M) · 0 transaksi Sabtu'},
    {ico:'🛒',cls:'green',col:C.green,t:'Basket Analysis: Bag Set Paling Sering Dibeli Bersama',b:'"Jumbo Bag Pink Polkadot" dan "Lunch Bag Red Retrospot" muncul bersama dalam 247 invoice. Ini peluang bundling dan cross-sell yang konkret dan langsung bisa diimplementasikan.',st:'Top pair: 247 co-occurrences · 19,778 total invoices'},
    {ico:'⚠️',cls:'red',col:C.red,t:'1.068 Pelanggan "Lost" Butuh Win-Back',b:'24.6% pelanggan terdaftar jatuh ke segmen Lost — tidak aktif lama dengan total nilai historis £510K. Win-back campaign dengan insentif eksklusif layak diprioritaskan sebelum mereka benar-benar pergi.',st:'Lost: 1,068 cust · £510K historical revenue at risk'},
  ];
  document.getElementById('ins-list').innerHTML=ins.map(x=>`
    <div class="ins-card">
      <div class="ins-ico" style="background:${x.col}14">${x.ico}</div>
      <div>
        <div class="ins-title">${x.t}</div>
        <div class="ins-body">${x.b}</div>
        <div class="ins-stat" style="color:${x.col}">${x.st}</div>
      </div>
    </div>
  `).join('');
}

// ══════════════════════════════════════════════
//  RENDER PAGE DISPATCHER
// ══════════════════════════════════════════════
function renderPage(id){
  if(id==='overview'){renderKPI();renderOV();}
  else if(id==='trend')renderTrend();
  else if(id==='geo')renderGeo();
  else if(id==='products')renderProducts();
  else if(id==='rfm')renderRFM();
  else if(id==='basket')renderBasket();
  else if(id==='insights')renderInsights();
}

// ══════════════════════════════════════════════
//  EXPORT: PDF
// ══════════════════════════════════════════════
function exportPDF(){
  const{jsPDF}=window.jspdf;
  const doc=new jsPDF({unit:'mm',format:'a4'});
  const W=210,M=15;

  // Halaman 1 — Cover + Summary
  doc.setFillColor(7,11,15);doc.rect(0,0,W,297,'F');
  doc.setFillColor(31,111,235);doc.rect(0,0,W,3,'F');
  doc.setTextColor(88,166,255);doc.setFont('helvetica','bold');doc.setFontSize(10);
  doc.text('DATALENS PRO — RETAIL ANALYTICS PLATFORM',M,24);
  doc.setTextColor(230,237,243);doc.setFontSize(28);
  doc.text('Laporan Analitik',M,40);doc.text('Penjualan Retail',M,54);
  doc.setTextColor(139,148,158);doc.setFont('helvetica','normal');doc.setFontSize(11);
  doc.text('Online Retail Dataset — UCI Machine Learning Repository',M,68);
  doc.text('Daqing Chen (2015) · CC BY 4.0 · DOI: 10.24432/C5BW33',M,75);

  // KPI Box
  doc.setFillColor(22,27,34);doc.roundedRect(M,88,W-M*2,72,4,4,'F');
  doc.setTextColor(88,166,255);doc.setFont('helvetica','bold');doc.setFontSize(9);
  doc.text('RINGKASAN EKSEKUTIF',M+5,97);
  const kpis=[['Total Revenue','£10.27 Million'],['Invoice Unik','19,778'],['Pelanggan','4,335'],['Produk','3,915'],['Negara','38'],['Avg/Invoice','£519']];
  kpis.forEach((k,i)=>{
    const x=M+5+(i%3)*58,y=106+Math.floor(i/3)*30;
    doc.setTextColor(139,148,158);doc.setFont('helvetica','normal');doc.setFontSize(8);doc.text(k[0],x,y);
    doc.setTextColor(88,166,255);doc.setFont('helvetica','bold');doc.setFontSize(15);doc.text(k[1],x,y+9);
  });

  // Monthly table
  let y=172;
  doc.setTextColor(88,166,255);doc.setFont('helvetica','bold');doc.setFontSize(9);
  doc.text('TREN PENDAPATAN BULANAN',M,y);y+=5;
  doc.setFillColor(33,38,45);doc.rect(M,y,W-M*2,7,'F');
  ['Periode','Revenue (£)','Invoice','Growth %'].forEach((h,i)=>{
    doc.setTextColor(139,148,158);doc.setFont('helvetica','bold');doc.setFontSize(8);
    doc.text(h,M+3+i*44,y+5);
  });
  y+=8;
  D.monthly.forEach((m,i)=>{
    if(y>282)return;
    if(i%2===0){doc.setFillColor(22,27,34);doc.rect(M,y,W-M*2,6,'F');}
    doc.setTextColor(230,237,243);doc.setFont('helvetica','normal');doc.setFontSize(8);
    doc.text(m.p,M+3,y+4.5);
    doc.text('£'+Math.round(m.rev).toLocaleString(),M+47,y+4.5);
    doc.text(m.ord.toString(),M+91,y+4.5);
    doc.setTextColor(m.g>=0?63:248,m.g>=0?185:81,m.g>=0?80:73);
    doc.text((m.g>0?'+':'')+m.g.toFixed(1)+'%',M+135,y+4.5);
    y+=6;
  });

  // Halaman 2 — RFM
  doc.addPage();
  doc.setFillColor(7,11,15);doc.rect(0,0,W,297,'F');
  doc.setFillColor(31,111,235);doc.rect(0,0,W,2,'F');
  doc.setTextColor(88,166,255);doc.setFont('helvetica','bold');doc.setFontSize(10);
  doc.text('CUSTOMER SEGMENTATION — RFM ANALYSIS',M,18);
  let ry=26;
  const segC={Champions:[88,166,255],'Loyal Customers':[63,185,80],'New Customers':[188,140,255],'At Risk':[210,153,34],Lost:[248,81,73],'Potential Loyalists':[57,211,83]};
  D.rfm.forEach(s=>{
    const cl=segC[s.seg]||[88,166,255];
    doc.setFillColor(22,27,34);doc.roundedRect(M,ry,W-M*2,28,3,3,'F');
    doc.setFillColor(...cl);doc.roundedRect(M,ry,4,28,2,2,'F');
    doc.setTextColor(...cl);doc.setFont('helvetica','bold');doc.setFontSize(10);
    doc.text(s.seg,M+8,ry+9);
    doc.setTextColor(139,148,158);doc.setFont('helvetica','normal');doc.setFontSize(8);
    doc.text(`${fmt(s.cnt)} pelanggan · £${fmt(s.rev)} revenue`,M+8,ry+16);
    doc.text(`${s.pct_c}% pelanggan · ${s.pct_r}% revenue · ${s.desc.slice(0,60)}`,M+8,ry+22);
    ry+=32;
  });

  // Halaman 3 — Top Products
  doc.addPage();
  doc.setFillColor(7,11,15);doc.rect(0,0,W,297,'F');
  doc.setFillColor(31,111,235);doc.rect(0,0,W,2,'F');
  doc.setTextColor(88,166,255);doc.setFont('helvetica','bold');doc.setFontSize(10);
  doc.text('TOP 20 PRODUK BERDASARKAN REVENUE',M,18);
  let py=26;
  doc.setFillColor(33,38,45);doc.rect(M,py,W-M*2,7,'F');
  ['#','Produk','Revenue','Qty','Orders'].forEach((h,i)=>{
    doc.setTextColor(139,148,158);doc.setFont('helvetica','bold');doc.setFontSize(8);
    doc.text(h,M+[2,10,118,143,158][i],py+5);
  });
  py+=8;
  D.products.forEach((p,i)=>{
    if(py>285)return;
    if(i%2===0){doc.setFillColor(22,27,34);doc.rect(M,py,W-M*2,6,'F');}
    doc.setTextColor(139,148,158);doc.setFont('helvetica','normal');doc.setFontSize(7.5);
    doc.text((i+1).toString(),M+2,py+4.5);
    const nm=p.n.length>44?p.n.slice(0,44)+'…':p.n;
    doc.setTextColor(230,237,243);doc.text(nm,M+10,py+4.5);
    doc.setTextColor(88,166,255);doc.text('£'+fmt(p.rev),M+118,py+4.5);
    doc.setTextColor(139,148,158);doc.text(fmt(p.qty),M+143,py+4.5);
    doc.text(fmt(p.ord),M+160,py+4.5);
    py+=6;
  });

  doc.setTextColor(72,79,88);doc.setFontSize(7);
  doc.text('DataLens Pro · UCI Online Retail · Daqing Chen (2015) · DOI:10.24432/C5BW33',M,291);

  doc.save('DataLens_Pro_Laporan_OnlineRetail.pdf');
  toast('PDF berhasil diunduh (3 halaman)');
}

// ══════════════════════════════════════════════
//  EXPORT: EXCEL
// ══════════════════════════════════════════════
function exportXLSX(){
  const wb=XLSX.utils.book_new();
  // Summary
  const sum=[['DATALENS PRO — ONLINE RETAIL ANALYTICS'],['UCI Machine Learning Repository · Daqing Chen (2015)'],[''],
    ['Metrik','Nilai'],['Total Revenue (£)',D.summary.total_revenue],['Invoice Unik',D.summary.total_invoices],
    ['Total Pelanggan',D.summary.total_customers],['Total Produk',D.summary.total_products],
    ['Total Negara',D.summary.total_countries],['Total Unit',D.summary.total_units],['Avg/Invoice (£)',D.summary.avg_order],
    [''],['Sitasi: Chen, D. (2015). Online Retail [Dataset]. UCI ML Repository. DOI:10.24432/C5BW33'],
  ];
  XLSX.utils.book_append_sheet(wb,XLSX.utils.aoa_to_sheet(sum),'Ringkasan');
  // Monthly
  XLSX.utils.book_append_sheet(wb,XLSX.utils.aoa_to_sheet([
    ['Periode','Revenue (£)','Invoice','Unit','Growth (%)'],
    ...D.monthly.map(m=>[m.p,m.rev,m.ord,m.units,m.g]),
  ]),'Tren Bulanan');
  // Countries
  XLSX.utils.book_append_sheet(wb,XLSX.utils.aoa_to_sheet([
    ['Negara','Revenue (£)','Invoice'],
    ...D.countries.map(c=>[c.c,c.rev,c.ord]),
  ]),'Negara');
  // Products
  XLSX.utils.book_append_sheet(wb,XLSX.utils.aoa_to_sheet([
    ['Produk','Revenue (£)','Qty','Orders','Avg/Order (£)'],
    ...D.products.map(p=>[p.n,p.rev,p.qty,p.ord,+(p.rev/Math.max(1,p.ord)).toFixed(2)]),
  ]),'Produk');
  // RFM
  const tCnt=D.rfm.reduce((s,x)=>s+x.cnt,0),tRev=D.rfm.reduce((s,x)=>s+x.rev,0);
  XLSX.utils.book_append_sheet(wb,XLSX.utils.aoa_to_sheet([
    ['Segmen','Pelanggan','Revenue (£)','% Pelanggan','% Revenue','Deskripsi'],
    ...D.rfm.map(s=>[s.seg,s.cnt,s.rev,s.pct_c,s.pct_r,s.desc]),
  ]),'RFM Segmen');
  // Basket
  XLSX.utils.book_append_sheet(wb,XLSX.utils.aoa_to_sheet([
    ['Produk A','Produk B','Co-occurrence'],
    ...D.basket.map(b=>[b.a,b.b,b.n]),
  ]),'Basket Analysis');
  XLSX.writeFile(wb,'DataLens_Pro_OnlineRetail.xlsx');
  toast('Excel (.xlsx) berhasil diunduh — 6 sheet');
}

// ══════════════════════════════════════════════
//  EXPORT: CSV & JSON
// ══════════════════════════════════════════════
function exportCSV(){
  const rows=['Produk,Revenue (£),Qty,Orders,Avg/Order',...D.products.map(p=>`"${p.n}",${p.rev},${p.qty},${p.ord},${(p.rev/Math.max(1,p.ord)).toFixed(2)}`)];
  const b=new Blob([rows.join('\n')],{type:'text/csv'});
  const a=document.createElement('a');a.href=URL.createObjectURL(b);a.download='DataLens_TopProduk.csv';a.click();
  toast('CSV berhasil diunduh');
}
function exportJSON(){
  const b=new Blob([JSON.stringify(D,null,2)],{type:'application/json'});
  const a=document.createElement('a');a.href=URL.createObjectURL(b);a.download='DataLens_Data.json';a.click();
  toast('JSON berhasil diunduh');
}

// ══════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════
refreshDynamic();
</script>
</body>
</html>
