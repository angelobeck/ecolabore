
me.keyControlEnd = function (){
if (!me.nodeInFocus)
return;

if (me.nodeInFocus === me.lastNode)
return;

me.nodeChangeFocus (me.nodeInFocus, me.lastNode, me.nodeInFocus.selected, me.lastNode.selected);
me.selectionStart = me.nodeInFocus;
};
