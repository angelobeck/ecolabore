
me.alertError = function (){
var audio = me.element;
audio.src = interface.urlShared + "/audio/error.mp3";
audio.load();
audio.play();
};