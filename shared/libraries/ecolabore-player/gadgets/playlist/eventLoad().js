
me.eventLoad = function (){
me.element = document.getElementById("playlist");
me.element.selectedIndex = 0;
me.element.addEventListener ("keydown", me.eventKeyDown);
};

window.addEventListener ("load", me.eventLoad);
