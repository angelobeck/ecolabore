
me.enabled = function(){
if (gadgets.playlist.element.options.length == 0)
return false;
if (gadgets.playlist.element.selectedIndex == 0)
return false;

return true;
};
