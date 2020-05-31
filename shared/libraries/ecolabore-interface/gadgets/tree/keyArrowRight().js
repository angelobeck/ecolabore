
me.keyArrowRight = function (){
var node = me.nodeInFocus;

if (node.children.length == 0)
return;

if (!node.group.hidden)
return me.nodeChangeFocus (node, node.children[0]);

node.element.setAttribute ("aria-expanded", true);
node.group.hidden = false;
};
