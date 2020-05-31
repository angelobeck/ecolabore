
me.keyDelete = function (){

if (me.selectionStart == me.selectionEnd && me.selectionStart < me.data.content.length){
var startBlock = me.data.content.slice (0, me.selectionEnd);
var endBlock = me.data.content.slice (me.selectionEnd + 1);
me.data.content = startBlock + endBlock;
me.selectionStart = me.selectionEnd;
}

var char = me.data.content.substr (me.selectionEnd, 1);

gadgets.message.element.innerHTML = "";
gadgets.message.element.innerHTML = me.dictionary (char);
};
