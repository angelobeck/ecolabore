
me.keySearch = function (key){
if (!me.nodeInFocus)
return;

var node = me.nodeInFocus;
while (node.next){
node = node.next;
if (node.key == key){
me.nodeUnselectAll();
me.nodeChangeFocus (me.nodeInFocus, node);
me.selectionStart = me.nodeInFocus;
return;
}
}

var node = me.firstNode;
while (node !== me.nodeInFocus){
if (node.key == key){
me.nodeUnselectAll();
me.nodeChangeFocus (me.nodeInFocus, node);
me.selectionStart = me.nodeInFocus;
return;
}
node = node.next;
}

};
