
me.exec = function (){
if (gadgets.playlist.element.options.length == 0)
return;
if (!gadgets.audio.statusCanPlay)
return;

if (!gadgets.audio.statusPaused)
gadgets.audio.element.pause();

gadgets.audio.statusPaused = true;
};
