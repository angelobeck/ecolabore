
me.eventLoad = function (){
var element = document.createElement ("AUDIO");
element.setAttribute ("controls", false);
element.hidden = true;
document.body.appendChild (element);
me.element = element;
};

window.addEventListener ("load", me.eventLoad);