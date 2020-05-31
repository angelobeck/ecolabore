
me.eventContextMenu = function (event){
var element = event.target;
event.stopPropagation();
event.preventDefault();

if (!element.dataset.index)
element = element.parentElement;

if (element !== me.nodeInFocus){
me.nodeUnselectAll();
me.nodeChangeFocus (me.nodeInFocus, element);
}

me.keyContextMenu (element);
};
