
me.eventBlur = function (){
var node = me.nodeInFocus;
me.nodeChangeFocus (node, me.firstNode);

while (true){
node.submenu.hidden = true;
if (node.parent.element === me.element)
break;
node = node.parent;
}
me.nodeInFocus.element.className = "";

me.hasFocus = false;
};
