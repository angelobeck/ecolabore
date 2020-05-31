
me.keyArrowRight = function (){
var node = me.nodeInFocus;

if (node.element.tagName == "SPAN")
{ // horizontal navigation
if (node.next)
me.nodeChangeFocus (node, node.next);
return;
} // horizontal navigation

me.nodeCheckActions (node);
if (node.children.length == 0)
return;

node.submenu.hidden = false;
setTimeout (function (){
me.nodeChangeFocus (me.nodeInFocus, me.nodeInFocus.children[0]);
}, 10);
};
