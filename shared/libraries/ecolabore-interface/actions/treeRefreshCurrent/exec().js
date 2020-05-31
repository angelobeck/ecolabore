
me.exec = function (){
if (gadgets.address.element.value == '/')
return actions.treeRefreshAll.exec();

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var received = JSON.parse(this.responseText);
if (!received || !received.location)
return;

var node = gadgets.tree.firstNode;
var parts = received.location.split ('/');
var path = '';
for (var i = 0; i < parts.length; i++){
var folder = parts[i] + "/";
if (folder.length == 1)
return;

path = path + folder;
if (!node.children || node.children.length == 0)
return;

for (var j = 0; j < node.children.length; j++){
var child = node.children[j];
if (!child.data.location || child.data.location + '/' != path)
continue;

node = child;
break;
}
}

node.clearChildren();
for (var i = 0; i < received.children.length; i++){
node.appendChild (received.children[i], true);
}

gadgets.tree.actionGoLocation (gadgets.address.element.value);
}
}

var command = { "location":gadgets.address.element.value };
request.open ("POST", gadgets.tree.element.dataset.load, true);
request.send (JSON.stringify (command));
};
