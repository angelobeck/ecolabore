
me.keyControlArrowUp = function (){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;
if (!node.previous)
return interface.audio.alertError();

me.nodeChangeFocus (node, node.previous, node.selected, node.previous.selected);
me.selectionStart = me.nodeInFocus;
};
