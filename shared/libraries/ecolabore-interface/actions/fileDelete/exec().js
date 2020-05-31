
me.exec = function (){
var files = [];

if (interface.focus.elementInFocus === gadgets.tree.element){
var data = gadgets.tree.nodeInFocus.data;
if (!data.location || data.location == "" || data.location == "/")
return gadgets.message.replace ("fileDeleteUnable");

files[0] = data.location;
}else{

var children = gadgets.list.children;
for (var i = 0; i < children.length; i++){
var child = children[i];
if (!child.selected)
continue;
if (child.data.location)
files[files.length] = child.data.location;
}
}

if (files.length == 0)
return;

if (files.length == 1)
var confirm = window.confirm (interface.utils.getMessage ("fileDeleteConfirmSingle"));
else
var confirm = window.confirm (interface.utils.getMessage ("fileDeleteConfirm", files.length));

if (!confirm)
return;

var command = { "command":"delete", "files":files };

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
actions.treeRefreshCurrent.exec();
gadgets.message.replaceText (this.responseText);
}
}

request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (command));
};
