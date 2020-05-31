
me.nodeCheckActions = function (parent){

for (var i = 0; i < parent.children.length; i++)
{ // each child
var node = parent.children[i];
var data = node.data;

if (data.builder && actions[data.builder] && actions[data.builder].build)
{ // builder
node.clearChildren();
if (data.children && data.children.length > 0){
for (var j = 0; j < data.children.length; j++){
node.appendChild (data.children[j], true);
}
}

actions[data.builder].build(node);

if (node.children.length > 0)
node.element.setAttribute ("aria-haspopup", true);
else
node.element.removeAttribute ("aria-haspopup");

} // builder

if (!data.action)
continue;

var action = data.action;
if (!actions[action])
continue;

if(actions[action].enabled && actions[action].enabled(node))
node.element.setAttribute ("aria-disabled", false);
else
node.element.setAttribute ("aria-disabled", true);

if(data.type && data.type == "checkbox"){
node.element.setAttribute ("aria-checked", actions[action].checked(node));
node.element.setAttribute ("role", "menuitemcheckbox");
} // checkbox



} // each child
};
