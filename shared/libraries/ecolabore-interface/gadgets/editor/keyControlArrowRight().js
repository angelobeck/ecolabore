
me.keyControlArrowRight = function (){
value = me.data.content;

while (true){
if (me.selectionEnd == value.length || value[me.selectionEnd] == "\n" || value[me.selectionEnd] == " ")
break;

me.selectionEnd ++;
}

while (true){
if (me.selectionEnd == value.length || (value[me.selectionEnd] != "\n" && value[me.selectionEnd] != " "))
break;

me.selectionEnd ++;
}

var pointer = me.selectionEnd;
while (true){
if (pointer == value.length || value[pointer] == "\n" || value[pointer] == " ")
break;

pointer ++;
}

me.selectionStart = me.selectionEnd;

gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = value.substr (me.selectionStart, pointer - me.selectionStart);
};
