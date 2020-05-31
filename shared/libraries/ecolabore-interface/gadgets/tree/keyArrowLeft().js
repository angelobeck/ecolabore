
me.keyArrowLeft = function (){
var node = me.nodeInFocus;

if (node === me.firstNode){
if (!node.group.hidden){
node.element.setAttribute ("aria-expanded", false);
node.group.hidden = true;
}

return;
}
if (node.group.hidden || node.children.length == 0)
return me.nodeChangeFocus (node, node.parent);

node.element.setAttribute ("aria-expanded", false);
node.group.hidden = true;
};
