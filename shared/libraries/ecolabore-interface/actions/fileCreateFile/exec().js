
me.exec = function (){
if (!gadgets.tree.nodeInFocus)
return;
if (gadgets.tree.nodeInFocus === gadgets.tree.firstNode){
var data = { "location":"/" };
}else{
var data = gadgets.tree.nodeInFocus.data;
if (!data.location)
return gadgets.message.replace ("fileCreateFileUnable");
}
var name = window.prompt (interface.utils.getMessage ("fileCreateFile"), interface.utils.getMessage ("fileCreateFileName"));
if (!name || name.length == 0)
return;

var command = { "command":"create_file", "location":data.location, "name":name };

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
