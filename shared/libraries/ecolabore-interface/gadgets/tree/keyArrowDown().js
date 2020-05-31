
me.keyArrowDown = function (){
var node = me.nodeInFocus;

if (node.nextVisible)
me.nodeChangeFocus (node, node.nextVisible);

};
