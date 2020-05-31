
me.eventKeyDown = function (event){
var key = interface.keyboard.detectKey (event);

if (event.ctrlKey || event.altKey || event.shiftKey || event.metaKey)
return;

switch (key){
case "Enter":
return me.keyEnter();
}
};

