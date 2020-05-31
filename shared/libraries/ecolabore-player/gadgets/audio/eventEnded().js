
me.eventEnded = function (){
me.statusPaused = true;

if (!me.controlContinuous.checked)
return;

if (gadgets.playlist.element.selectedIndex + 1 == gadgets.playlist.element.options.length)
return;

gadgets.playlist.element.selectedIndex ++;
var url = gadgets.playlist.element.options[gadgets.playlist.element.selectedIndex].dataset.url;
me.actionLoad (url);
};
