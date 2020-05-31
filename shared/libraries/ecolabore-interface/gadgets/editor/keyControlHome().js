
me.keyControlHome = function (){
var value = me.data.content;

newLine = value.indexOf ("\n");
if (newLine == -1)
newLine = value.length - 1;

me.selectionEnd = 0;
me.selectionStart = 0;
gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = value.slice (0, newLine);
};
