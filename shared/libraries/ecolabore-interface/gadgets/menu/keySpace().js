
me.keySpace = function (){
var node = me.nodeInFocus;

if (node.children.length > 0)
{ // open submenu
node.submenu.hidden = false;
setTimeout (function (){
me.nodeChangeFocus (me.nodeInFocus, me.nodeInFocus.children[0]);
}, 10);
return;
} // open submenu

if (!node.data.action)
return;

var action = node.data.action;

if (actions[action] && actions[action].enabled(node))
{ // execute action
if (node.data.type && node.data.type == "checkbox")
{ // checkbox
actions[action].exec(node);
node.element.setAttribute ("aria-checked", actions[action].checked(node));
return;
} // checkbox

if (interface.focus.elementInFocus)
interface.focus.elementInFocus.focus();
actions[action].exec();
} // execute action

};
