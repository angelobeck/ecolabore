
me.actionGoLocation = function (location){
if (location == "/" || location == "")
return me.nodeChangeFocus (me.nodeInFocus, me.firstNode);

var parts = location.split ('/');
var node = me.firstNode;
for (var i = 0; i < parts.length; i++){
var folder = parts[i];

var children = node.children;
var found = false;
for (var j = 0; j < children.length; j++){
var child = children[j];

if (child.data.name != folder)
continue;

found = true;
break;
} // each node

if (!found)
return;

child.parent.group.hidden = false;
child.parent.element.setAttribute ("aria-expanded", true);
node = child;
} // each folder

me.nodeChangeFocus (me.nodeInFocus, node);
};
