
me.eventLoad = function (){
me.element = document.getElementById("menu");
if (!me.element)
return;

me.element.setAttribute ("role", "menu");
me.element.addEventListener ("keydown", me.eventKeyDown);
me.element.addEventListener ("blur", me.eventBlur);
me.element.addEventListener ("focus", me.eventFocus);

if (me.element.dataset && me.element.dataset.load){
var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var data = JSON.parse(this.responseText);

if (!data || !data.children || !data.children.length)
return;

for (var i = 0; i < data.children.length; i++){
var current = data.children[i];
var node = new nodeInterface (ecolabore, me, current, true, true);
if (i == 0){
me.nodeInFocus = node;
me.firstNode = node;
}
}
me.element.setAttribute ("aria-activedescendant", me.nodeInFocus.id);
}
}
request.open("GET", me.element.dataset.load, true);
request.send();
}
};

window.addEventListener ("load", me.eventLoad);
