
me.keyEnd = function (){

var value = me.data.content;

me.selectionEnd = value.indexOf ("\n", me.selectionEnd);
if (me.selectionEnd == -1)
me.selectionEnd = value.length;

me.selectionStart = me.selectionEnd;
gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = me.dictionary (value.substr (me.selectionEnd, 1));
};
