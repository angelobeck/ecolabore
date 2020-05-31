
me.eventKeyPress = function (event){

if (event.ctrlKey || event.altKey || event.metaKey)
return;

if (event.key.length == 1)
return me.keyInsertChar (event.key);

return true;
};
