
me.keyShiftHome = function (){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;

if (node === me.firstNode)
return;

if (!me.selectionStart)
me.selectionStart = node;

me.nodeUnselectAll();
current = me.selectionStart;
while (current){
current.selected = true;
current = current.previous;
}

me.nodeChangeFocus (node, me.firstNode, true, true);
};
