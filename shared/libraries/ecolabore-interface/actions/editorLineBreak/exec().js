
me.exec = function (){
if (gadgets.editor.element.getAttribute ("cols") == 1024)
gadgets.editor.element.setAttribute ("cols", "");
else
gadgets.editor.element.setAttribute ("cols", "1024");

gadgets.editor.element.setAttribute ("wrap", "soft");
};
