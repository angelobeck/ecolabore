
me.eventKeyDown = function (event){
var key = me.detectKey (event);

if (event.altKey || event.metaKey)
{ // Alt key
if (key == "Alt" || key == "Meta")
me.lastAlt = true;
else
me.lastAlt = false;

if (interface.shortcuts['Alt_' + key]){
event.stopPropagation();
event.preventDefault();
var action = interface.shortcuts['Alt_' + key];
if (action.enabled())
action.exec();
}

return;
} // alt key

if (event.ctrlKey && event.shiftKey)
{ // Control + Shift
if (interface.shortcuts['Control_Shift_' + key]){
event.stopPropagation();
event.preventDefault();
var action = interface.shortcuts['Control_Shift_' + key];
if (action.enabled())
action.exec();
}
} // Control + Shift

if (event.ctrlKey)
{ // Control
if (interface.shortcuts['Control_' + key]){
event.stopPropagation();
event.preventDefault();
var action = interface.shortcuts['Control_' + key];
if (action.enabled())
action.exec();
}
} // Control

if (key.startsWith ("F"))
{ // Functions keys
if (interface.shortcuts[key]){
event.stopPropagation();
event.preventDefault();
var action = interface.shortcuts[key];
if (action.enabled())
action.exec();
}
} // function keys

};

