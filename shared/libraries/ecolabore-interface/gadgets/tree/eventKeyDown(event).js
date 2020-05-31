
me.eventKeyDown = function (event){
var key = interface.keyboard.detectKey (event);

if (event.ctrlKey || event.altKey || event.shiftKey || event.metaKey)
return;

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
return me.keyContextMenu ();
case "Delete":
return me.keyDelete ();
case "End":
return me.keyEnd ();
case "Home":
return me.keyHome ();
}

if (key.length == 1)
return me.keySearch (key);
};