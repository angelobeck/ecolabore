
me.actionOpen = function (id){
if (me.elementInFocus)
me.elementInFocus.hidden = true;

me.elementInFocus = document.getElementById ('humperstilshen_' + id);

me.baloom.hidden = false;
me.elementInFocus.hidden = false;

var element = me.baloom;
while (element.previousElementSibling){
element = element.previousElementSibling;
element.setAttribute ("aria-hidden", true);
}

window.scrollTo(0, 0);

var captions = me.elementInFocus.getElementsByClassName ("caption");
if (captions.length == 0)
return;
captions[0].setAttribute ("tabindex", 0);

setTimeout (function (){
captions[0].focus(); 
}, 10);
};
