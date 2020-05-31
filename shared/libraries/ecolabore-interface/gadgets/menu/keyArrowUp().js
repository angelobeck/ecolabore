
me.keyArrowUp = function (){
var node = me.nodeInFocus;

if (node.element.tagName == "SPAN")
return;

if (!node.previous){
if (node.parent.element.tagName != "SPAN")
return;

me.nodeChangeFocus (node, node.parent);
node.parent.submenu.hidden = true;
return;
}

me.nodeChangeFocus (node, node.previous);
};
