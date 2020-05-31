
me.keyArrowUp = function (){
var node = me.nodeInFocus;

if (node === me.firstNode)
return;

if (!node.previous)
return me.nodeChangeFocus (node, node.parent);

if (node.previous.group.hidden || node.previous.children.length == 0)
return me.nodeChangeFocus (node, node.previous);

node = node.previous.children[node.previous.children.length - 1];

while(true){
if (node.group.hidden || node.children.length == 0)
return me.nodeChangeFocus (me.nodeInFocus, node);

node = node.children[node.children.length - 1];
}
};
