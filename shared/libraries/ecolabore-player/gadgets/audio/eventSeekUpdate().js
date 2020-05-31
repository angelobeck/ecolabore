
me.eventSeekUpdate = function (){
if (me.statusPaused)
return;
if (me.element.duration == NaN)
return;

me.controlSeek.max = Math.floor (me.element.duration);

me.controlSeek.value = Math.floor (me.element.currentTime);

setTimeout (me.eventSeekUpdate, 1000);
};
