
me.keyEnter = function (){
if (me.element.options.length == 0)
return;

var url = me.element.options[me.element.selectedIndex].dataset.url;
gadgets.audio.actionLoad (url);
};
