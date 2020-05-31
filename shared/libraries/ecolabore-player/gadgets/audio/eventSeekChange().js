
me.eventSeekChange = function (){
if (me.element.duration == NaN || me.element.paused || me.element.ended)
return;

me.element.currentTime = me.controlSeek.value;
};
