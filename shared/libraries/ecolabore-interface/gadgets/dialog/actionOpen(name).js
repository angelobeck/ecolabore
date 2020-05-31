
me.actionOpen = function(name){
if (!me.dialogsById[name])
return;

me.layout.setAttribute ("aria-hidden", true);
me.element.hidden = false;
me.dialogsById[name].hidden = false;

};