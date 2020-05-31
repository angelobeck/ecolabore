
me.exec = function (){

gadgets.editor.data.content = interface.utils.escapeString (gadgets.editor.element.value);

var command = { "command":"save", "data":gadgets.editor.data };

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
gadgets.message.replaceText (this.responseText);
gadgets.editor.data.content = gadgets.editor.element.value;
gadgets.editor.element.disabled = false;
}
}

request.open("POST", interface.url.endpoint, true);
request.send (JSON.stringify (command));

gadgets.editor.element.disabled = true;
};
