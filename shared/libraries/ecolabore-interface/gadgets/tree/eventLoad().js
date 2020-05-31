
me.eventLoad = function (){
me.element = document.getElementById("tree");

if (!me.element)
return;

me.element.setAttribute ("role", "tree");

me.element.addEventListener ("keydown", me.eventKeyDown);

me.element.innerHTML = "";
me.nodesById = {};
me.id = "tree";

if (me.element.dataset && me.element.dataset.load){
var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var data = JSON.parse(this.responseText);
if (!data)
return;

new nodeInterface (ecolabore, me, data, true, true);
me.nodeInFocus = me.children[0];
me.firstNode = me.children[0];
me.element.setAttribute ("aria-activedescendant", me.firstNode.id);

if (me.firstNode.children.length > 0){
me.firstNode.element.setAttribute ("aria-expanded", true);
me.firstNode.group.hidden = false;

me.actionGoLocation (gadgets.address.element.value);
}

}
}
request.open("GET", me.element.dataset.load, true);
request.send();
}
};

window.addEventListener ("load", me.eventLoad);
