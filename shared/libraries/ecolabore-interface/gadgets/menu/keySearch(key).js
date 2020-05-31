
me.keySearch = function (key){
var numNodesFound = 0;
var nodeFound = false;

var node = me.nodeInFocus;
while (node = node.next){
if (node.key == key){
numNodesFound ++;
if (!nodeFound)
nodeFound = node;
}
}


node = me.nodeInFocus.parent.children[0];
while(true){
if (node.key == key){
numNodesFound ++;
if (!nodeFound)
nodeFound = node;
}

if (node === me.nodeInFocus)
break;

node = node.next;
}

if (nodeFound)
me.nodeChangeFocus (me.nodeInFocus, nodeFound);

if (numNodesFound == 1)
me.keyEnter ();
};
