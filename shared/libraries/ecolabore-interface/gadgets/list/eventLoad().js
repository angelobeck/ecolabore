
me.eventLoad = function (){
me.element = document.getElementById("list");
if (!me.element)
return;

me.element.setAttribute ("role", "listbox");
me.element.addEventListener ("keydown", me.eventKeyDown);

me.eventRefresh ();
};

window.addEventListener ("load", me.eventLoad);
