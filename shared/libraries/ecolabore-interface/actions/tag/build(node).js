
me.build = function (node){
var children = gadgets.tags.children;
if (children.length == 0)
return;

for (var i = 0;  i < children.length; i++){
if (!children[i])
continue;
var data = {};
data.text = children[i].text;
data.id = children[i].id;
data.type = "checkbox";
data.action = "tag";
node.appendChild(data);
}
};
