
me.eventKeyDown = function (event){
var key = interface.keyboard.detectKey (event);

if (event.altKey || event.metaKey)
return;

if (event.ctrlKey){
switch (key){
case "ArrowDown":
return me.keyControlArrowDown ();
case "ArrowUp":
return me.keyControlArrowUp ();
case "Enter":
return actions.fileDownload.exec();
case "End":
return me.keyControlEnd ();
case "Home":
return me.keyControlHome ();
case "Space":
case " ":
return me.keyControlSpace ();
}
return;
} // control

if (event.shiftKey){
switch (key){
case "ArrowDown":
return me.keyShiftArrowDown ();
case "ArrowUp":
return me.keyShiftArrowUp ();
case "End":
return me.keyShiftEnd ();
case "Home":
return me.keyShiftHome ();
}
return;
} // shift

switch (key){
case "ArrowDown":
return me.keyArrowDown ();
case "ArrowUp":
return me.keyArrowUp ();
case "Backspace":
event.preventDefault();
return me.keyBackSpace ();
case "ContextMenu":
event.preventDefault();
return me.keyContextMenu ();
case "Delete":
return me.keyDelete ();
case "Enter":
return me.keyEnter ();
case "End":
return me.keyEnd ();
case "Home":
return me.keyHome ();
}

if (key.length == 1 && /^[a-z]$/.test(key))
return me.keySearch (key);
};
