
me.exec = function (){
var editor = gadgets.editor;

if (editor.data.location)
window.opener.ecolabore.gadgets.editor.load = editor.data.location;
window.location.reload (true);
};
