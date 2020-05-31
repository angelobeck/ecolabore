
me.eventKeyDown = function (event){
if (event.ctrlKey || event.altKey || event.shiftKey || event.metaKey)
return;

var key = interface.keyboard.detectKey (event);
switch (key){
case "ArrowDown":
return me.keyArrowDown ();
case "ArrowLeft":
return me.keyArrowLeft ();
case "ArrowRight":
return me.keyArrowRight ();
case "ArrowUp":
return me.keyArrowUp ();
case "ContextMenu":
event.preventDefault();
event.stopPropagation();
return me.keyContextMenu ();
case "Delete":
event.stopPropagation();
return me.keyDelete ();
case "Enter":
return me.keyEnter (event);
case " ":
case "Space":
return me.keySpace ();
}

if (key.length == 1){
return me.keySearch (key);
}
};