
me.keyEnd = function (){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;
if (node === me.lastNode)
return;

me.nodeUnselectAll();
me.nodeChangeFocus (node, me.lastNode);
me.selectionStart = me.nodeInFocus;
};
