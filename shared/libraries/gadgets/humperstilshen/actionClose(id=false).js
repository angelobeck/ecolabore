
me.actionClose = function (id=false){
if (!me.elementInFocus)
return;

me.elementInFocus.hidden = true;
me.baloom.hidden = true;
me.elementInFocus = false;

var element = me.baloom;
while (element.previousElementSibling){
element = element.previousElementSibling;
element.setAttribute ("aria-hidden", false);
}

if (id == false || id == "")
id = "humperstilshen";

var element = document.getElementById (id);

if (element && element.focus)
setTimeout (function (){ element.focus(); }, 10);
};
