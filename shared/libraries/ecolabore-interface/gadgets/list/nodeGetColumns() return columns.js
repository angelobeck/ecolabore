
me.nodeGetColumns = function (){
var node = gadgets.tree.nodeInFocus;
while (true){
if (node.data.columns)
return node.data.columns;

if (node === gadgets.tree.firstNode)
return [ "text" ];

node = node.parent;
}
};
