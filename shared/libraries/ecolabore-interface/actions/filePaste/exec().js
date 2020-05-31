
me.exec = function (){
var data = interface.clipboard;
if (!data.command || (data.command != "copy" && data.command != "move"))
return;
if (!gadgets.tree.nodeInFocus)
return;
if (gadgets.tree.nodeInFocus === gadgets.tree.firstNode)
data.target = "/";
else if (gadgets.tree.nodeInFocus.data.location)
data.target = gadgets.tree.nodeInFocus.data.location;
else
return;

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
actions.treeRefreshCurrent.exec();
gadgets.message.replaceText (this.responseText);
}
}

request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (data));
};
