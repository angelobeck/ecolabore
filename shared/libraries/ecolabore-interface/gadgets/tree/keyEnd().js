
me.keyEnd = function (){
var node = me.firstNode;

while (true){
if (node.group.hidden || node.children.length == 0)
return me.nodeChangeFocus (me.nodeInFocus, node);

node = node.children[node.children.length - 1];
}
};
