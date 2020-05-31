
me.exec = function (){
var editor = gadgets.editor;
var value = editor.element.value;
var pointer = 0;
var line = 1;
var cursor = editor.element.selectionStart;
while (true){
var next = value.indexOf ("\n", pointer);
if (next >= cursor)
break;
if (next > -1){
line ++;
pointer = next + 1;
}else{
break;
}
}
line = parseInt (prompt (interface.utils.getMessage ("editorFindLine"), line));
if (line >= 1)
{ // valid number

pointer = 0;
for (var i = 1; i < line; i++){
next = value.indexOf ("\n", pointer);
if (next > -1)
pointer = next + 1;
}
editor.element.setSelectionRange (pointer, pointer);
} // valid line
};
