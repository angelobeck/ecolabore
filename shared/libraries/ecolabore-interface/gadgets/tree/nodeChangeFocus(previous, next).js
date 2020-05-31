
me.nodeChangeFocus = function (previous, next){
if (previous === next && previous.element.className == "focus")
return;

previous.element.className = "";
previous.element.setAttribute ("aria-selected", false);

me.nodeInFocus = next;
me.element.setAttribute ("aria-activedescendant", next.id);
// next.element.scrollIntoView();
next.element.className = "focus";
next.element.setAttribute ("aria-selected", true);

if (next.data.location)
gadgets.address.element.value = next.data.location;
else if (next === me.firstNode)
gadgets.address.element.value = "/";

gadgets.list.eventRefresh (next.data);
};
