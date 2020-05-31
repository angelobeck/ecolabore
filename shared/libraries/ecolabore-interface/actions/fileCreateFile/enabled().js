
me.enabled = function (){
if (!gadgets.tree.nodeInFocus)
return false;
if (gadgets.tree.nodeInFocus === gadgets.tree.firstNode)
return true;
if (!gadgets.tree.nodeInFocus.data.location)
return false;

return true;
};
