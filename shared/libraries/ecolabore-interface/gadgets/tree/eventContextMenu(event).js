
me.eventContextMenu = function (event){
var element = event.target;
event.preventDefault();

if (element !== me.nodeInFocus)
me.nodeChangeFocus (me.nodeInFocus, element);

me.keyContextMenu (element);
};
