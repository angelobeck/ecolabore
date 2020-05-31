
me.delete = function (){
var id = gadgets.menu.nodeInFocus.data.id;
var text = gadgets.menu.nodeInFocus.data.text;

var confirmation = confirm (interface.utils.getMessage ("tagDeleteConfirm", text));
if (!confirmation)
return;

var command = { "command":"tag_remove", "id":id };
var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var answer = JSON.parse(this.responseText);
if (!answer || !answer.id || answer.id == 0)
return;

var children = gadgets.tags.children;
for (var i = 0; i < children.length; i++){
var child = children[i];
if (child.id == answer.id){
gadgets.message.replace ("tagDeleted", child.text);
delete children[i];
}
}

}
}
request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (command));
};
