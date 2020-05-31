
me.exec = function (){
var editor = gadgets.editor;

if (editor.element.value == editor.data.content){
editor.element.value = "";
editor.data.fileName = "";
editor.data.content = "";
return;
}

var saveChanges = confirm (interface.utils.getMessage ("editorSaveChanges", editor.data.fileName ));

if (!saveChanges){
editor.element.value = "";
editor.data.content = "";
editor.data.fileName = "";
return;
}

editor.data.content = interface.utils.escapeString (editor.element.value);
var command = { "command":"save", "data":editor.data };

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
gadgets.editor.element.value = "";
gadgets.editor.data.content = "";
gadgets.editor.data.fileName = "";
}
}

request.open("POST", interface.url.endpoint, true);
request.send (interface.jsonEncode (command));

};
