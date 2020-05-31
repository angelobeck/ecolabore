
me.exec = function (){
if (gadgets.playlist.element.options.length == 0)
return;

var url = gadgets.playlist.element.options[gadgets.playlist.element.selectedIndex].dataset.url;

if (gadgets.audio.element.src != url){
gadgets.audio.element.src = url;
gadgets.audio.element.load();
gadgets.audio.element.play();
return;
}

if (!gadgets.audio.statusCanPlay)
return;
if (gadgets.audio.statusPaused)
gadgets.audio.element.play();

gadgets.audio.statusPaused = false;
};
