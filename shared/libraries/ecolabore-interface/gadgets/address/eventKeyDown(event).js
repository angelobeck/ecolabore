
me.eventKeyDown = function (event){
var key = interface.keyboard.detectKey (event);

if (event.altKey || event.metaKey || event.ctrlKey || event.shiftKey)
return;

if (key == "Enter")
return gadgets.tree.actionGoLocation (me.element.value);
};
