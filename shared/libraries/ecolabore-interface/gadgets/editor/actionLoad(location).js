
me.actionLoad = function (location){

var command = { "command":"load", "location":location };

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var data = JSON.parse(this.responseText);
if (!data)
gadgets.message.replace ("editorOpenFailure");

me.data = data;
if (data.content)
data.content = interface.utils.unescapeString (data.content);
else
data.content = "";

if (data.fileName)
document.title = data.fileName;

me.element.value = data.content;
me.element.selectionStart = 0;
me.element.selectionEnd = 0;
}
}

request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (command));

};
