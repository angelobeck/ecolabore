
me.eventLoad = function (){
window.addEventListener ("keydown", me.eventKeyDown);
window.addEventListener ("keyup", me.eventKeyUp);
};

window.addEventListener ("load", me.eventLoad);
