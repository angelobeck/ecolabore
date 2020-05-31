
me.keyHome = function (){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;
if (node === me.firstNode)
return;

me.nodeUnselectAll();
me.nodeChangeFocus (node, me.firstNode);
me.selectionStart = me.nodeInFocus;
};
