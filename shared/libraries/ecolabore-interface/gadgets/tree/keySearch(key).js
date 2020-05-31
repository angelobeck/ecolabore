
me.keySearch = function (key){

var node = me.nodeInFocus;

while (node = node.nextVisible){
if (node.key == key)
return me.nodeChangeFocus (me.nodeInFocus, node);
}

var node = me.firstNode;
while (node !== me.nodeInFocus){
if (node.key == key)
return me.nodeChangeFocus (me.nodeInFocus, node);

node = node.nextVisible;
}

};
