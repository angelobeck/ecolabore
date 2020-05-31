
me.keyControlArrowLeft = function (){
value = me.data.content;

while (true){
if (me.selectionEnd == 0 || (value[me.selectionEnd - 1] != "\n" && value[me.selectionEnd - 1] != " "))
break;

me.selectionEnd --;
}

while (true){
if (me.selectionEnd == 0 || value[me.selectionEnd - 1] == "\n" || value[me.selectionEnd - 1] == " ")
break;

me.selectionEnd --;
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
