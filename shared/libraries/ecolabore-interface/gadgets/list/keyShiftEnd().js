
me.keyShiftEnd = function (){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;

if (node === me.lastNode)
return;

if (!me.selectionStart)
me.selectionStart = node;

me.nodeUnselectAll();
current = me.selectionStart;
while (current){
current.selected = true;
current = current.next;
}

me.nodeChangeFocus (node, me.lastNode, true, true);
};
