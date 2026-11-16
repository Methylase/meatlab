document.querySelectorAll('[data-carousel]').forEach(function(c){
  var s=c.querySelectorAll('.slide'),d=c.querySelector('.dots'),i=0,t;
  s.forEach(function(_,n){var b=document.createElement('button');b.setAttribute('aria-label','Slide '+(n+1));b.onclick=function(){go(n)};d.appendChild(b)});
  function go(n){i=(n+s.length)%s.length;s.forEach(function(x,k){x.classList.toggle('on',k===i);d.children[k].classList.toggle('on',k===i)});clearInterval(t);t=setInterval(function(){go(i+1)},5500)}
  go(0)});
var loc=document.getElementById('locate');
if(loc)loc.onclick=function(){if(!navigator.geolocation)return;navigator.geolocation.getCurrentPosition(function(p){
  fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat='+p.coords.latitude+'&lon='+p.coords.longitude).then(function(r){return r.json()}).then(function(j){
  var a=j.address||{};document.getElementById('q').value=[a.suburb||a.neighbourhood,a.city||a.state].filter(Boolean).join(', ')})})};
document.querySelectorAll('.pcard').forEach(function(c){
  var p=+c.dataset.price,k=c.querySelector('[name=kg]'),a=c.querySelector('[name=amount]'),m=+c.dataset.min;
  function fromKg(){a.value=Math.round(k.value*p)}
  k.oninput=fromKg;a.oninput=function(){k.value=Math.max(m,Math.round(a.value/p*10)/10)};
  c.querySelectorAll('.stepper button').forEach(function(b){b.type='button';b.onclick=function(){k.value=Math.max(m,+(+k.value+(+b.dataset.d)).toFixed(1));fromKg()}});fromKg()});
document.querySelectorAll('.tabs button').forEach(function(b){b.onclick=function(){
  document.querySelectorAll('.tabs button').forEach(function(x){x.classList.toggle('on',x===b)});
  document.querySelectorAll('.pcard').forEach(function(c){c.style.display=(b.dataset.cat==='all'||c.dataset.cat===b.dataset.cat)?'':'none'})}});

document.querySelectorAll('[data-carousel]').forEach(function(c){
  c.addEventListener('mouseenter',function(){c.dataset.paused='1'});
  c.addEventListener('mouseleave',function(){delete c.dataset.paused});
});  