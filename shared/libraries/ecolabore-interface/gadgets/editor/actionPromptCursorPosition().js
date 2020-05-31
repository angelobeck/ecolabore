
me.actionPromptCursorPosition = function (location){

var value = me.element.value;
var pointer = 0;
var line = 1;
var cursor = me.element.selectionStart;
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
var column = 1 + cursor - pointer;
gadgets.message.replaceText (""); 
gadgets.message.replace ("editorPromptCursorPosition", line, column);
};
