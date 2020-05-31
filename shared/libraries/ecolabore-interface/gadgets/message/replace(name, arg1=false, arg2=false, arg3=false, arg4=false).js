
me.replace = function (name, arg1=false, arg2=false, arg3=false, arg4=false){
var text = interface.utils.getMessage (name, arg1, arg2, arg3, arg4);
if (me.nextText == text)
return;

me.nextText = text;
setTimeout (me.refresh, 100);
};