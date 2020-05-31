
me.eventLoad = function (){
me.element = document.getElementById("address");
if (!me.element)
return;

me.element.addEventListener ("keydown", me.eventKeyDown);
me.element.focus();
};

window.addEventListener ("load", me.eventLoad);
