
me.eventKeyDown = function (event){
key = event.key;

if (event.ctrlKey){
if (key == "ArrowDown")
return me.keyControlArrowDown();
if (key == "ArrowLeft")
return me.keyControlArrowLeft();
if (key == "ArrowRight")
return me.keyControlArrowRight();
if (key == "ArrowUp")
return me.keyControlArrowUp();
if (key == "End")
return me.keyControlEnd();
if (key == "Home")
return me.keyControlHome();

return;
}

if (event.shiftKey){
if (key == "ArrowDown")
return me.keyShiftArrowDown();
if (key == "ArrowLeft")
return me.keyShiftArrowLeft();
if (key == "ArrowRight")
return me.keyShiftArrowRight();
if (key == "ArrowUp")
return me.keyShiftArrowUp();
if (key == "End")
return me.keyShiftEnd ();
if (key == "Home")
return me.keyShiftHome();
}

if (key == "ArrowDown")
return me.keyArrowDown();
if (key == "ArrowLeft")
return me.keyArrowLeft();
if (key == "ArrowRight")
return me.keyArrowRight();
if (key == "ArrowUp")
return me.keyArrowUp();
if (key == "Backspace")
return me.keyBackSpace();
if (key == "Delete")
return me.keyDelete();
if (key == "Enter")
return me.keyInsertChar ("\n");
if (key == "End")
return me.keyEnd();
if (key == "Home")
return me.keyHome();

return true;
};
