
me.enabled = function(){
if (gadgets.playlist.element.options.length == 0)
return false;
if (!gadgets.audio.statusCanPlay)
return false;

return true;
};
