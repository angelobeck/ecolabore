
me.keyShiftArrowDown = function (){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;

if (!node.next)
return interface.audio.alertError();

if (me.selectionStart == false){
me.nodeChangeFocus (node, node.next, true, true);
me.selectionStart = me.nodeInFocus;
return;
}

if (me.selectionStart.index <= node.index)
me.nodeChangeFocus (node, node.next, true, true);
else
me.nodeChangeFocus (node, node.next, false, true);
};
