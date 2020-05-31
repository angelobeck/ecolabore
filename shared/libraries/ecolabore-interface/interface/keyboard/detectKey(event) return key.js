
me.detectKey = function (event){
if (event.isComposing || event.keyCode === 229)
return false;

if (event.key)
return event.key;
if (event.keyCode)
return interface.keyboard.convertKeyCode (event.keyCode);
if (event.which)
return interface.keyboard.convertKeyCode (event.which);
};
