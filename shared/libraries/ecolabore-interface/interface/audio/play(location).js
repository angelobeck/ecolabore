
me.play = function (location){
var audio = me.element;
audio.src = location;
audio.load();
audio.play();
};
