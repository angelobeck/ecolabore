
me.exec = function (){
gadgets.audio.controlVolume.stepDown ();
gadgets.audio.element.volume = gadgets.audio.controlVolume.value / 100;
};
