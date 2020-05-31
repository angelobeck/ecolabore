
me.keyContextMenu = function (){
if (!me.nodeInFocus)
return;

alert ("Menu de contexto em item da lista: " + me.nodeInFocus.element.innerText);
};
