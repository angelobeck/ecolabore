
me.exec = function (){
var editor = gadgets.editor;
if (editor.element.value == editor.data.content){
window.close();
return;
}

if (!editor.data.fileName || editor.data.fileName == ""){
fileName = prompt (interface.utils.getMessage ("editorSaveAs"));
if (!fileName)
window.close();
else
editor.data.fileName = fileName;
}else{
var saveChanges = confirm (interface.utils.getMessage ("editorSaveChanges", editor.data.fileName));

if (!saveChanges){
window.close();
return;
}
}

editor.data.content = interface.utils.escapeString (editor.element.value);
var command = { "command":"save", "data":editor.data };

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
window.close();
}
}

request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (command));

};
