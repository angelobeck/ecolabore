
me.keyInsertChar = function (key){
var startBlock = me.data.content.slice (0, me.selectionEnd);
var endBlock = me.data.content.slice (me.selectionEnd);
me.data.content = startBlock + key + endBlock;
me.selectionEnd += key.length;
me.selectionStart = me.selectionEnd;
};
