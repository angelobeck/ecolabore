
me.build = function (node){

if (!gadgets.tree.firstNode)
return;

var children = gadgets.tree.firstNode.children;
for (var i = 0; i < children.length; i++){
var child = children[i];
if (child === gadgets.tree.nodeInFocus)
continue;

node.appendChild (children[i].data);
}

};
