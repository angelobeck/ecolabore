
me.enabled = function (){
if (interface.focus.elementInFocus === gadgets.tree.element){
if (!gadgets.tree.nodeInFocus)
return false;

var data = gadgets.tree.nodeInFocus.data;
if (!data.location || data.location == "" || data.location == "/")
return false;

return true;
}

if (gadgets.list.children.length == 0)
return false;
if (!gadgets.list.nodeInFocus.data.location)
return false;

return true;
};
