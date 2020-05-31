
me.actionLoad = function (url){
if (me.element.src == url)
return;

me.statusCanPlay = false;
me.statusPaused = true;
me.element.src = url;
me.element.load();
};
