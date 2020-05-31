
me.eventClick = function (event){
var element = event.target;

if (element === me.nodeInFocus && element.nextElementSibling.firstElementChild){
if (element.nextElementSibling.hidden){
element.nextElementSibling.hidden = false;
element.setAttribute ("aria-expanded", true);
}else{
element.nextElementSibling.hidden = true;
element.setAttribute ("aria-expanded", false);
}
return;
}

me.nodeChangeFocus (me.nodeInFocus, element);
};
