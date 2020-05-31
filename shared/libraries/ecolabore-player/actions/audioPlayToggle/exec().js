
me.exec = function (){
if (!gadgets.audio.statusCanPlay)
return;
if (gadgets.audio.statusPaused){
gadgets.audio.element.play();
gadgets.audio.statusPaused = false;
}else{
gadgets.audio.element.pause();
gadgets.audio.statusPaused = true;
}
};
