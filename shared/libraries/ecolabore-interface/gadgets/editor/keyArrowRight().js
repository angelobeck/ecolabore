
me.keyArrowRight = function (){
var value = me.data.content;

if (me.selectionEnd < me.data.content.length)
me.selectionEnd ++;

me.selectionStart = me.selectionEnd;

var char = value[me.selectionEnd];

gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = me.dictionary (char);
};
