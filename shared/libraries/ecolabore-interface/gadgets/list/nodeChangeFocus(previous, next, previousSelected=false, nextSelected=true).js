
me.nodeChangeFocus = function (previous, next, previousSelected=false, nextSelected=true){
if (previous === next)
return;

previous.element.className = "";
next.element.className = "focus";

if (previousSelected === true || previousSelected === "true")
previous.selected = true;
else
previous.selected = false;

if (nextSelected === true || nextSelected === "true")
next.selected = true;
else
next.selected = false;

me.nodeInFocus = next;
me.scrolled = me.element.scrollTop;
me.element.setAttribute ("aria-activedescendant", next.id);
setTimeout (me.nodeScrollIntoView, 10);
me.nodeCountSelected();

var data = next.data;
if (!data.location || !data.type || data.type == "dir"){
gadgets.file.buttonDownload.href = "#";
return;
}
var param = btoa (data.location);
param = param.replace(/[=]/g, "");
gadgets.file.buttonDownload.href = interface.url.download + "/_location-" + param;
};
