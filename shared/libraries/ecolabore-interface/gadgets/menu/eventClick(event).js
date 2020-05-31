
me.eventClick = function (event){
var element = event.target;
if (element.className != "menu-item")
element = element.parentElement;
if (element.className != "menu-item")
return;

me.nodeChangeFocus (me.nodeInFocus, element);
me.keyEnter();
};
