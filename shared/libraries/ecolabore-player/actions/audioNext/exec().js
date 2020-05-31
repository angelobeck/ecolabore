
me.exec = function (){
if (gadgets.playlist.element.options.length == 0)
return;
if (gadgets.playlist.element.selectedIndex + 1 == gadgets.playlist.element.options.length)
return;

gadgets.playlist.element.selectedIndex ++;
var url = gadgets.playlist.element.options[gadgets.playlist.element.selectedIndex].dataset.url;
gadgets.audio.actionLoad (url);
};
