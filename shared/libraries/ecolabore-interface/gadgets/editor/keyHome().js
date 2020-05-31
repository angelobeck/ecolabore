
me.keyHome = function (){
var value = me.data.content;

if (me.selectionEnd > 0 && value[me.selectionEnd - 1] != "\n"){
var pointer = 0;
var newLine = 0;

while (true){
pointer = value.indexOf ("\n", newLine);

if (pointer == -1 || pointer >= me.selectionEnd)
break;

newLine = pointer + 1;
}

me.selectionEnd = newLine;
}

me.selectionStart = me.selectionEnd;
gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = me.dictionary (value.substr (me.selectionEnd, 1));
};
