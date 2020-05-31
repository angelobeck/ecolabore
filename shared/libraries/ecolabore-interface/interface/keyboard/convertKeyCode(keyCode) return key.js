

me.convertKeyCode = function (keyCode){
switch (keyCode){
case 8:
return "Backspace";
case 9:
return "Tab";
case 13:
return "Enter";
case 16:
return "Shift";
case 17:
return "Control";
case 18:
return "Alt";
case 27:
return "Esc";
case 32:
return " ";
case 33:
return "PageUp";
case 34:
return "PageDown";
case 35:
return "End";
case 36:
return "Home";
case 37:
return "ArrowLeft";
case 38:
return "ArrowUp";
case 39:
return "ArrowRight";
case 40:
return "ArrowDown";
case 93:
return "ContextMenu";


case 112:
return "F1";
case 113:
return "F2";
case 114:
return "F3";
case 115:
return "F4";
case 116:
return "F5";
case 117:
return "F6";
case 118:
return "F7";
case 119:
return "F8";
case 120:
return "F9";
case 121:
return "F10";
case 122:
return "F11";
case 123:
return "F12";

}

return String.fromCharCode (keyCode).toLowerCase();
};