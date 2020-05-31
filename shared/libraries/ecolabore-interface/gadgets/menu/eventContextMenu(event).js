
me.eventContextMenu = function (event){
var element = event.target;
if (element.className != "menu-item")
element = element.parentElement;
if (element.className != "menu-item")
return;

event.preventDefault();

if (element !== me.nodeInFocus)
me.nodeChangeFocus (me.nodeInFocus, element);

me.keyContextMenu();
};
