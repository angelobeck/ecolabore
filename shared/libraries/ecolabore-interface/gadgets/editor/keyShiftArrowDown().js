
me.keyShiftArrowDown = function (){
var value = me.data.content;
var previousLine = 0;
var column = 0;
var currentLineStart = 0;
var currentLineEnd = 0;
var lineLength = 0;
var previousSelectionEnd = me.selectionEnd;

if (me.selectionEnd > 0){
if (me.selectionEnd < value.length && value[me.selectionEnd - 1] == "\n")
previousLine = me.selectionEnd;
else
previousLine = value.lastIndexOf ("\n", me.selectionEnd - 1) + 1;
}

column = me.selectionEnd - previousLine;

if (me.selectionEnd < value.length && value[me.selectionEnd] == "\n")
currentLineStart = me.selectionEnd;
else
currentLineStart = value.indexOf ("\n", me.selectionEnd);
if (currentLineStart < 0)
return;

currentLineStart ++;
if (currentLineStart == value.length)
currentLineEnd = currentLineStart;
else if (value[currentLineStart] == "\n")
currentLineEnd = currentLineStart;
else{
currentLineEnd = value.indexOf ("\n", currentLineStart);
if (currentLineEnd == -1)
currentLineEnd = value.length;
}

lineLength = currentLineEnd - currentLineStart;
if (column > lineLength)
column = lineLength;

if (currentLineStart + column == me.selectionEnd)
me.selectionEnd ++;
else
me.selectionEnd = currentLineStart + column;

// diversas formas de seleção: descelecionar ou selecionar

gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = value.substr (currentLineStart, lineLength);
};
