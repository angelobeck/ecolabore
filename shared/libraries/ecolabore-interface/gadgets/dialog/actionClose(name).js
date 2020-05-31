
me.actionClose = function(name){

me.layout.setAttribute ("aria-hidden", false);
me.element.hidden = true;

if (!me.dialogsById[name])
return;
me.dialogsById[name].hidden = true;

};