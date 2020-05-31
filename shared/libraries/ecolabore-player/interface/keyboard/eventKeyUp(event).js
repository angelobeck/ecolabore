
me.eventKeyUp = function (event){
var key = me.detectKey (event);

if (key == "Alt" || key == "Meta" || event.altKey || event.metaKey){
event.preventDefault();
event.stopPropagation();
if (me.lastAlt)
{ // change focus
me.lastAlt = false;
if (!gadgets.menu || !gadgets.menu.element)
return;

if (gadgets.menu.hasFocus){
if (interface.focus.elementInFocus)
interface.focus.elementInFocus.focus();
}else{
gadgets.menu.element.focus();
}
} // change focus
}
};

