
me.exec = function (){
if (!gadgets.audio.statusCanPlay)
return;

gadgets.audio.controlSeek.stepDown (5);
gadgets.audio.element.currentTime = gadgets.audio.controlSeek.value;
};