
me.keyControlHome = function (){
if (!me.nodeInFocus)
return;

if (me.nodeInFocus === me.firstNode)
return;

me.nodeChangeFocus (me.nodeInFocus, me.firstNode, me.nodeInFocus.selected, me.firstNode.selected);
me.selectionStart = me.nodeInFocus;
};
