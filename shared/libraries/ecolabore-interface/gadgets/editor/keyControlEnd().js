
me.keyControlEnd = function (){
var value = me.data.content;

if (value.length == 0)
return;

var lastLine = value.lastIndexOf ("\n");
if (lastLine == -1)
lastLine = 0;
else
lastLine ++;

me.selectionEnd = value.length - 1;
me.selectionStart = me.selectionEnd;
gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = "Indo ao fim " + value.slice (lastLine);
};
