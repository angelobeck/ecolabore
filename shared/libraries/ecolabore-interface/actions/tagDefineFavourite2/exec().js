
me.exec = function (){
if (gadgets.tags.children.length == 0)
return;

if (gadgets.menu.hasFocus && gadgets.menu.nodeInFocus.data.type && gadgets.menu.nodeInFocus.data.action == "tag"){
gadgets.tags.favourite2 = gadgets.menu.nodeInFocus.data.id;
gadgets.message.replace ("tagFavouriteDefined", "2", gadgets.menu.nodeInFocus.data.text);
return;
}

};
