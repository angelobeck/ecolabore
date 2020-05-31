
me.keyArrowLeft = function (){
var node = me.nodeInFocus;

if (node.element.tagName == "SPAN")
{ // horizontal navigation
if (node.previous)
me.nodeChangeFocus (node, node.previous);
return;
} // horizontal navigation

if (node.parent.element.tagName == "SPAN")
return;

me.nodeChangeFocus (node, node.parent);
node.parent.submenu.hidden = true;
};
