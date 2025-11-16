<!doctype html>
<html lang="zh-Hant">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ESG 記憶配對｜街機版</title>
<style>
/* 🎨 色票 */
:root{
  --bg:#f7f7f7; --card:#ffffff; --ink:#222; --muted:#667085;
  --brand:#1f883d; --brand-dark:#166a30;
  --line:#e6e6e6;
  --arcade-blue:#0E63E6; --arcade-green:#20B386;
}

/* 全域 */
html,body{
  height:100%; margin:0; background:var(--bg);
  font-family:system-ui,"Noto Sans TC",Segoe UI,Roboto,Arial,sans-serif;
  color:var(--ink);
  overflow:hidden; /* 不要捲動條 */
}

/* ================= 背景層 ================= */
.bg-illustration{
  position:fixed; inset:0; z-index:0;
  background:url("D:/ywl/彰師大/1141-電子商務/ChatGPT-Image.jpg") center/cover no-repeat;
  filter:blur(2px) brightness(0.92);
}
.stage{position:fixed;inset:0;z-index:1;overflow:hidden;pointer-events:none;}
.cloud{position:absolute;top:10%;width:180px;animation:moveCloud 40s linear infinite;}
.cloud2{position:absolute;top:20%;width:140px;animation:moveCloud 60s linear infinite reverse;}
@keyframes moveCloud{from{left:-200px}to{left:100%}}
.windmill{position:absolute;left:6%;bottom:10%;width:160px;}
.blade{animation:spin 8s linear infinite;transform-origin:50% 60%;}
@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
.trees{position:absolute;right:6%;bottom:8%;width:200px;}
#bg-canvas{position:fixed;inset:0;z-index:1;pointer-events:none;}

/* ================= 機台 ================= */
.arcade{
  position:relative;
  margin:40px auto;
  padding:20px;
  background:#fff;
  border:4px solid var(--line);
  border-radius:28px;
  box-shadow:0 20px 40px rgba(0,0,0,.18);
  width:min(94%,1100px);
  z-index:2;
  animation:arcadeEnter 1.2s cubic-bezier(.25,1.2,.5,1) forwards;
}
.arcade::before{
  content:"";position:absolute;top:-22px;left:50%;transform:translateX(-50%);
  width:200px;height:16px;
  background:linear-gradient(90deg,var(--arcade-blue),var(--arcade-green));
  border-radius:999px;box-shadow:0 0 12px rgba(0,0,0,.25);
}
.arcade-screen{
  background:#fff;border:2px solid var(--line);
  border-radius:20px;padding:16px;
  box-shadow:inset 0 0 40px rgba(0,0,0,.08);
}
.arcade-controls{display:flex;justify-content:center;gap:20px;margin-top:20px;}
.arcade-btn{
  width:44px;height:44px;border-radius:50%;
  background:var(--arcade-green);border:3px solid var(--line);
  box-shadow:0 4px 0 rgba(0,0,0,.25);
}
@keyframes arcadeEnter {
  0%   { transform: translateY(120%) scale(0.8); opacity: 0; }
  60%  { transform: translateY(-8%) scale(1.02); opacity: 1; }
  80%  { transform: translateY(4%) scale(0.98); }
  100% { transform: translateY(0) scale(1); }
}

/* ================= 遊戲區塊 ================= */
.wrap{max-width:1050px;margin:0 auto;}
header{display:flex;gap:16px;align-items:center;flex-wrap:wrap;justify-content:space-between;margin-bottom:12px}
h1{font-size:20px;margin:0}
.meta{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
.pill{background:#fff;border:1px solid var(--line);border-radius:999px;padding:6px 12px;font-size:14px}
.select{appearance:none;background:#fff;border:1px solid var(--line);border-radius:10px;padding:8px 10px;font-size:14px}
.btn{background:var(--brand);color:#fff;border:none;border-radius:10px;padding:10px 14px;font-weight:600;cursor:pointer}
.btn:hover{background:var(--brand-dark)}
.bar{height:10px;background:#e9ecef;border-radius:999px;overflow:hidden}
.bar>span{display:block;height:100%;width:100%;background:linear-gradient(90deg,#66bb6a,#43a047)}
.panel{display:grid;grid-template-columns:1fr 300px;gap:16px}
@media (max-width:1024px){.panel{grid-template-columns:1fr}}
.grid{display:grid;gap:8px;margin-top:14px;}

/* 卡牌縮放：依視窗高度自適應 */
.grid[data-size="4"]{grid-template-columns:repeat(4,1fr);}
.grid[data-size="6"]{grid-template-columns:repeat(6,1fr);}
.card{aspect-ratio:1/1;position:relative;cursor:pointer;user-select:none;}
.inner{position:absolute;inset:0;transform-style:preserve-3d;transition:transform .28s ease}
.card.flipped .inner,.card.matched .inner{transform:rotateY(180deg)}
.face{position:absolute;inset:0;border-radius:10px;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;backface-visibility:hidden}
.front{background:var(--card)}
.back{transform:rotateY(180deg);background:#fff}
.front::after{content:"♻️";font-size:24px;opacity:.85}
.emoji{font-size:28px;line-height:1}
.label{position:absolute;bottom:6px;left:0;right:0;text-align:center;font-size:11px;color:var(--muted)}

/* 自適應卡牌大小（剛好填滿螢幕高度） */
@media (min-width:1025px){
  .grid[data-size="4"] .card{height:18vh;}
  .grid[data-size="6"] .card{height:12vh;}
}
</style>
</head>
<body>
<!-- 背景圖層 -->
<div class="bg-illustration"></div>

<!-- 動態插畫層 -->
<div class="stage">
  <img src="https://i.imgur.com/2Jg3B6r.png" class="cloud">
  <img src="https://i.imgur.com/2Jg3B6r.png" class="cloud2">
  <svg class="windmill" viewBox="0 0 200 200">
    <circle cx="100" cy="120" r="12" fill="#555"/>
    <g class="blade">
      <rect x="96" y="10" width="8" height="100" fill="#7FC0FF"/>
      <rect x="96" y="120" width="8" height="70" fill="#7FC0FF"/>
      <rect x="30" y="116" width="70" height="8" fill="#7FC0FF"/>
      <rect x="100" y="116" width="70" height="8" fill="#7FC0FF"/>
    </g>
  </svg>
  <svg class="trees" viewBox="0 0 200 200">
    <circle cx="60" cy="140" r="40" fill="#C9F0DD" stroke="#6aa787" stroke-width="4"/>
    <circle cx="140" cy="160" r="30" fill="#D6F6E6" stroke="#6aa787" stroke-width="4"/>
  </svg>
  <canvas id="bg-canvas"></canvas>
</div>

<!-- 機台 -->
<div class="arcade">
  <div class="arcade-screen">
    <div class="wrap">
      <header>
        <h1>ESG 記憶配對｜街機版</h1>
        <div class="meta">
          <select id="levelSel" class="select">
            <option value="0">入門 4×4</option>
            <option value="1">進階 6×6</option>
          </select>
          <div class="pill">時間：<b id="time">--</b></div>
          <div class="pill">步數：<b id="moves">0</b></div>
          <div class="pill">配對：<b id="matches">0</b>/<b id="pairs">--</b></div>
          <button class="btn" id="restartBtn">重新開始</button>
        </div>
      </header>
      <div class="bar"><span id="timebar"></span></div>
      <div class="panel">
        <div><div id="grid" class="grid"></div><div id="know" class="know"></div></div>
        <aside class="side">
          <h3>任務 / 進度</h3>
          <div id="mission" class="tip"></div>
          <div class="chips" id="progressChips"></div>
          <div class="tip">已解鎖小卡：<b id="unlocked">0</b>/<b id="need">--</b></div>
          <hr>
          <h3>排行榜（本機）</h3>
          <table id="rank"><thead><tr><th>#</th><th>暱稱</th><th>關卡</th><th>時間</th><th>步數</th><th>日期</th></tr></thead><tbody></tbody></table>
        </aside>
      </div>
    </div>
  </div>
  <div class="arcade-controls">
    <div class="arcade-btn"></div>
    <div class="arcade-btn"></div>
    <div class="arcade-btn"></div>
  </div>
</div>
<script>
/* ===== 關卡設定 ===== */
const LEVELS = [
  {name:'入門 4×4', size:4, time:90, targetMoves:40},
  {name:'進階 6×6', size:6, time:150, targetMoves:120}
];
let LV=0, GRID_SIZE=LEVELS[LV].size, TIME_LIMIT=LEVELS[LV].time;

/* ===== ESG 卡牌 ===== */
const ESG_ICONS = [
  {emoji:'🍶',label:'PET 瓶',tip:'PET 瓶重量與設計影響碳排。'},
  {emoji:'🧬',label:'瓶胚（Preform）',tip:'瓶胚是導入 rPET 的關鍵。'},
  {emoji:'🔩',label:'塑膠瓶蓋',tip:'輕量化瓶蓋可降低用料與碳排。'},
  {emoji:'🏷️',label:'收縮標籤',tip:'易撕分離能提升回收效率。'},
  {emoji:'🧴',label:'塑膠瓶身',tip:'減重 1–2g 就能顯著省碳。'},
  {emoji:'🏭',label:'無菌充填',tip:'節能與良率提升能降碳。'},
  {emoji:'♻️',label:'回收再生',tip:'rPET 替代新料可降碳。'},
  {emoji:'🌞',label:'綠電(太陽能)',tip:'屋頂光電導入再生能源。'},
  {emoji:'🌬️',label:'綠電(風能)',tip:'多元綠電策略分散風險。'},
  {emoji:'🚚',label:'物流減碳',tip:'最佳化路線減少排放。'}
];

/* ===== DOM ===== */
const gridEl=document.getElementById('grid'),
      timeEl=document.getElementById('time'),
      timebar=document.getElementById('timebar'),
      movesEl=document.getElementById('moves'),
      matchEl=document.getElementById('matches'),
      pairsEl=document.getElementById('pairs'),
      levelSel=document.getElementById('levelSel'),
      restartBtn=document.getElementById('restartBtn'),
      missionEl=document.getElementById('mission'),
      chipsEl=document.getElementById('progressChips'),
      unlockedEl=document.getElementById('unlocked'),
      needEl=document.getElementById('need'),
      knowEl=document.getElementById('know'),
      rankTable=document.getElementById('rank').querySelector('tbody');

/* ===== 狀態 ===== */
let deck=[],first=null,second=null,lock=false;
let moves=0,matched=0,timer=null,timeLeft=TIME_LIMIT;
let discovered=new Set();

/* ===== 工具 ===== */
function shuffle(a){for(let i=a.length-1;i>0;i--){const j=Math.floor(Math.random()*(i+1));[a[i],a[j]]=[a[j],a[i]]}return a;}
function format(s){const m=Math.floor(s/60).toString().padStart(2,'0'),sec=(s%60).toString().padStart(2,'0');return`${m}:${sec}`}

/* ===== 遊戲邏輯 ===== */
function buildDeck(){
  const picks=shuffle([...ESG_ICONS]).slice(0,(GRID_SIZE*GRID_SIZE)/2);
  deck=shuffle(picks.flatMap((c,i)=>[{...c,id:i+'A'},{...c,id:i+'B'}]));
}
function render(){
  gridEl.setAttribute("data-size", GRID_SIZE);
  gridEl.innerHTML='';
  deck.forEach(c=>{
    const d=document.createElement('div');
    d.className='card'; d.dataset.label=c.label; d.dataset.tip=c.tip;
    d.innerHTML=`<div class="inner">
      <div class="face front"></div>
      <div class="face back">
        <div class="emoji">${c.emoji}</div>
        <div class="label">${c.label}</div>
      </div></div>`;
    d.onclick=()=>flip(d);
    gridEl.appendChild(d);
  });
  pairsEl.textContent=deck.length/2;
  needEl.textContent=deck.length/2;
}
function flip(el){
  if(lock||el.classList.contains('flipped')||el.classList.contains('matched'))return;
  el.classList.add('flipped');
  if(!first){first=el;return;}
  second=el;moves++;movesEl.textContent=moves;lock=true;
  if(first.dataset.label===second.dataset.label){
    setTimeout(()=>{
      first.classList.add('matched');second.classList.add('matched');
      matched++;matchEl.textContent=matched;
      showKnow(first.dataset.tip,first.dataset.label);
      if(matched===deck.length/2)end(true);
      reset();
    },300);
  }else{
    setTimeout(()=>{first.classList.remove('flipped');second.classList.remove('flipped');reset();},700);
  }
}
function reset(){[first,second,lock]=[null,null,false];}
function tick(){
  timeLeft--;timeEl.textContent=format(timeLeft);
  timebar.style.width=(timeLeft/TIME_LIMIT*100)+'%';
  if(timeLeft<=0)end(false);
}
function start(){
  clearInterval(timer);
  buildDeck();render();
  [first,second,lock]=[null,null,false];
  moves=0;matched=0;discovered.clear();
  movesEl.textContent=0;matchEl.textContent=0;
  timeLeft=TIME_LIMIT;timeEl.textContent=format(timeLeft);timebar.style.width='100%';
  unlockedEl.textContent=0;chipsEl.innerHTML='';
  missionEl.textContent=`目標：在 ${TIME_LIMIT} 秒內 ≤ ${LEVELS[LV].targetMoves} 步完成`;
  timer=setInterval(tick,1000);
}
function end(win){
  clearInterval(timer);
  alert(win?`完成 ${LEVELS[LV].name} 🎉\n步數 ${moves}`:'時間到！');
  saveRank();
}

/* ===== 提示 / 排行榜 ===== */
function showKnow(tip,label){
  if(!discovered.has(label)){
    discovered.add(label);
    unlockedEl.textContent=discovered.size;
    chipsEl.innerHTML+=`<span class="pill">${label}</span>`;
    knowEl.innerHTML=`<strong>${label}</strong><br>${tip}`;
    knowEl.style.display='block';
    setTimeout(()=>knowEl.style.display='none',3000);
  }
}
function loadRank(){
  const d=JSON.parse(localStorage.getItem('esg-rank')||'[]');
  rankTable.innerHTML=d.map((r,i)=>`<tr><td>${i+1}</td><td>${r.nick}</td><td>${r.level}</td><td>${r.time}</td><td>${r.moves}</td><td>${r.date}</td></tr>`).join('');
}
function saveRank(){
  const d=JSON.parse(localStorage.getItem('esg-rank')||'[]');
  d.push({nick:'玩家',level:LEVELS[LV].name,time:format(LEVELS[LV].time-timeLeft),moves,date:new Date().toLocaleDateString()});
  d.sort((a,b)=>a.moves-b.moves||a.time.localeCompare(b.time));
  localStorage.setItem('esg-rank',JSON.stringify(d.slice(0,20)));loadRank();
}

/* ===== 事件 ===== */
levelSel.onchange=()=>{LV=+levelSel.value;GRID_SIZE=LEVELS[LV].size;TIME_LIMIT=LEVELS[LV].time;start();}
restartBtn.onclick=start;
window.onload=()=>{loadRank();start();}

/* ===== 粒子背景 ===== */
const canvas=document.getElementById('bg-canvas'),ctx=canvas.getContext('2d');let W,H,particles=[];
function resize(){W=canvas.width=window.innerWidth;H=canvas.height=window.innerHeight}
function createParticles(){particles=[];for(let i=0;i<40;i++){particles.push({x:Math.random()*W,y:Math.random()*H,r:2+Math.random()*3,dx:(Math.random()-0.5)*0.6,dy:(Math.random()-0.5)*0.6})}}
function drawParticles(){ctx.clearRect(0,0,W,H);ctx.fillStyle='rgba(32,179,134,0.3)';particles.forEach(p=>{ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fill();p.x+=p.dx;p.y+=p.dy;if(p.x<0||p.x>W)p.dx*=-1;if(p.y<0||p.y>H)p.dy*=-1})}
function loop(){drawParticles();requestAnimationFrame(loop)}
window.addEventListener('resize',resize);resize();createParticles();loop();
</script>
</body>
</html>













