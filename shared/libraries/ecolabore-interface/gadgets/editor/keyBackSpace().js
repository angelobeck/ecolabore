
me.keyBackSpace = function (){
if (me.selectionEnd == 0)
return;

var startBlock = me.data.content.slice (0, me.selectionEnd - 1);
var char = me.data.content.substr (me.selectionEnd - 1, 1);
var endBlock = me.data.content.slice (me.selectionEnd);
me.data.content = startBlock + endBlock;
me.selectionEnd --;
me.selectionStart = me.selectionEnd;

gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = me.dictionary (char);
};
