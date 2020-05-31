
me.eventLoad = function (){
var elements = document.getElementsByClassName("ecl-menu");

for (var i = 0; i < elements.length; i++){
element = elements[i];
if(!element.id)
continue;

var menu = new menuInterface (element);
gadgets[element.id] = menu;

}
};

window.addEventListener ("load", me.eventLoad);
