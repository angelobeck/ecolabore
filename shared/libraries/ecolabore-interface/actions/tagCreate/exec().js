
me.exec = function (){
var name = prompt ("Tag");

if (name.length == 0)
return;

var command = { "command":"tag_create", "name":name };
var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
me.loaded = true;
var answer = JSON.parse(this.responseText);
if (!answer)
return;

var children = gadgets.tags.children;

children[children.length] = answer;
gadgets.message.replace ("tagCreated", answer.text);
}
}
request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (command));
};
