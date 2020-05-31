
me.keyEnter = function (event){
var node = me.nodeInFocus;

me.nodeCheckActions (node);
if (node.children.length > 0)
{ // open submenu
node.submenu.hidden = false;
setTimeout (function (){
me.nodeChangeFocus (me.nodeInFocus, me.nodeInFocus.children[0]);
}, 10);
return;
} // open submenu

if (node.data.action && actions[node.data.action] && actions[node.data.action].enabled())
{ // execute action
if (!actions[node.data.action].forwardPropagation && event){
event.preventDefault();
event.stopPropagation();
}

if (interface.focus.elementInFocus)
interface.focus.elementInFocus.focus();
actions[node.data.action].exec();
return;
} // execute action

};
