
me.exec = function (){
var editor = gadgets.editor;

if(editor.find == "")
editor.find = prompt ("Localizar", editor.find);

if (editor.find === false)
editor.find = '';
if (editor.find == "")
return;

var pointer = editor.element.selectionEnd;
var next = editor.element.value.indexOf (editor.find, pointer);
if (next < 0)
interface.audio.alertError();
else
editor.element.setSelectionRange (next, next + editor.find.length);
editor.actionPromptCursorPosition();
};
