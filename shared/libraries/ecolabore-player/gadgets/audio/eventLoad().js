
me.eventLoad = function (){
me.controlVolume = document.getElementById("volume");
me.controlVolume.addEventListener ("change", me.eventVolumeChange);

me.element = document.getElementById("audio");
me.element.volume = me.controlVolume.value / 100;
me.element.addEventListener ("canplay", me.eventCanPlay);
me.element.addEventListener ("ended", me.eventEnded);

me.controlSeek = document.getElementById("seek");
me.controlSeek.addEventListener ("change", me.eventSeekChange);

me.controlContinuous = document.getElementById("continuous");

me.controlPlay = document.getElementById("audio-play");
me.controlPlay.onclick = function (){ if (actions.audioPlay.enabled()) actions.audioPlay.exec(); };
me.controlPause = document.getElementById("audio-pause");
me.controlPause.onclick = function (){ if (actions.audioPause.enabled()) actions.audioPause.exec(); };
me.controlRewind = document.getElementById("audio-rewind");
me.controlRewind.onclick = function (){ if (actions.audioRewind.enabled()) actions.audioRewind.exec(); };
me.controlForward = document.getElementById("audio-forward");
me.controlForward.onclick = function (){ if (actions.audioForward.enabled()) actions.audioForward.exec(); };
me.controlPrevious = document.getElementById("audio-previous");
me.controlPrevious.onclick = function (){ if (actions.audioPrevious.enabled()) actions.audioPrevious.exec(); };
me.controlNext = document.getElementById("audio-next");
me.controlNext.onclick = function (){ if (actions.audioNext.enabled()) actions.audioNext.exec(); };
me.controlVolumeDecrease = document.getElementById("audio-volume-decrease");
me.controlVolumeDecrease.onclick = function (){ if (actions.audioVolumeDecrease.enabled()) actions.audioVolumeDecrease.exec(); };
me.controlVolumeIncrease = document.getElementById("audio-volume-increase");
me.controlVolumeIncrease.onclick = function (){ if (actions.audioVolumeIncrease.enabled()) actions.audioVolumeIncrease.exec(); };

setTimeout (function (){
me.controlPlay.focus(); 
}, 20);
};

window.addEventListener ("load", me.eventLoad);