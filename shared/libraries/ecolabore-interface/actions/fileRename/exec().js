
me.exec = function (){
if (interface.focus.elementInFocus === gadgets.tree.element){
if (!gadgets.tree.nodeInFocus)
return;

var data = gadgets.tree.nodeInFocus.data;
}else{
if (gadgets.list.children.length == 0)
return;
var data = gadgets.list.nodeInFocus.data;
}

if (!data.location || data.location.length == 0 || data.location == '/')
return;

name = window.prompt (interface.utils.getMessage ("fileRename"), data.text);
if (!name || name.length == 0)
return;

var command = { "command":"rename", "location":data.location, "new_name":name };

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
