
me.keyShiftArrowUp = function (){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;

if (!node.previous)
return interface.audio.alertError();

if (me.selectionStart == false){
me.nodeChangeFocus (node, node.previous, true, true);
me.selectionStart = me.nodeInFocus;
return;
}

if (me.selectionStart.index >= node.index)
me.nodeChangeFocus (node, node.previous, true, true);
else
me.nodeChangeFocus (node, node.previous, false, true);
};
