
me.keyArrowUp = function (){

var value = me.data.content;

var currentLineStart = 0;
var currentLineEnd = 0;
var column = 0;
var lineLength = 0;

if (me.selectionEnd == 0)
return;
if (value[me.selectionEnd - 1] == "\n")
currentLineEnd = me.selectionEnd - 1;
else{
currentLineEnd = value.lastIndexOf ("\n", me.selectionEnd - 1);
if (currentLineEnd == -1)
return;
}

column = me.selectionEnd - (currentLineEnd + 1);

if (currentLineEnd == 0)
currentLineStart = 0;
else if (value[currentLineEnd - 1] == "\n")
currentLineStart = currentLineEnd;
else
currentLineStart = value.lastIndexOf ("\n", currentLineEnd - 1) + 1;

lineLength = currentLineEnd - currentLineStart;
if (column > lineLength)
column = lineLength;

me.selectionEnd = currentLineStart + column;
me.selectionStart = me.selectionEnd;
gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = value.substr (currentLineStart, lineLength);
};
