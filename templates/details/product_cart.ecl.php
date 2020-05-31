'text'={
'caption'={
'pt'={
1='Informações sobre o carrinho de compras'
2=1
}
'en'={
1='Informations about the shopping cart'
}
}
}
'html'='
[text][if($url){] <a href="[$url]" class="button" role="button">[text:field_cart_go]</a><br>[}]
[if($timer_on){]
<div aria-live="polite" id="[$mod.name]_status" style="height:2em"></div>
[cut:script]

cartTimer = [$timer];

function cartStatus (){
var status = document.getElementById ("[$mod.name]_status");

if (cartTimer < 1){
window.location = "[$url_timeout]";
}else if (cartTimer <= 10){
status.innerHTML = cartTimer + " Segundos restantes";
cartTimer --;
window.setTimeout (cartStatus, 1000);
}else if (cartTimer < 60 && cartTimer % 10){
status.innerHTML = cartTimer + " Segundos restantes";
window.setTimeout (cartStatus, (cartTimer % 10) * 1000);
cartTimer -= (cartTimer % 10);
}else if (cartTimer <= 60){
status.innerHTML = cartTimer + " Segundos restantes";
cartTimer -= 10;
window.setTimeout (cartStatus, 10000);
}else if (cartTimer % 60){
var dif = cartTimer % 60;
var rounded = (60 + cartTimer) - dif;
status.innerHTML = (rounded / 60) + " Minutos restantes";
window.setTimeout (cartStatus, (cartTimer % 60) * 1000);
cartTimer -= (cartTimer % 60);
}else{
status.innerHTML = (cartTimer / 60) + " Minutos restantes";
cartTimer -= 60;
window.setTimeout (cartStatus, 60000);
}
}

window.addEventListener ("load", cartStatus);
[/cut]
[}]
'
