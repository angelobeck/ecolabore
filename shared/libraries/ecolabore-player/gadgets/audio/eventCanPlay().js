
me.eventCanPlay = function (){
me.element.play();
me.statusCanPlay = true;
me.statusPaused = false;
me.eventSeekUpdate();
};
