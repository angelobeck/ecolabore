
me.replaceText = function (text){
if (me.nextText == text)
return;

me.nextText = text;
setTimeout (me.refresh, 100);
};