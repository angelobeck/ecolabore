
me.keyArrowLeft = function (){
if (me.selectionEnd > 0)
me.selectionEnd --;

me.selectionStart = me.selectionEnd;

var char = me.data.content[me.selectionEnd];

gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = me.dictionary (char);
};
