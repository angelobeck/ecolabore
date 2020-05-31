
me.nodeChangeFocus = function (previous, next){
if (previous === next)
return;

previous.element.className = "";
previous.element.setAttribute ("aria-selected", false);

me.nodeInFocus = next;
me.element.setAttribute ("aria-activedescendant", next.id);
next.element.className = "focus";
next.element.setAttribute ("aria-selected", true);

};
