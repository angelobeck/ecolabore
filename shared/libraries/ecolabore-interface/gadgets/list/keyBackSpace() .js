
me.keyBackSpace = function (){
var node = gadgets.tree.nodeInFocus;
if (node === gadgets.tree.firstNode)
return;

gadgets.tree.nodeChangeFocus (node, node.parent);

me.element.focus();
};
