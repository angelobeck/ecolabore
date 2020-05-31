
me.exec = function (){
if (gadgets.tags.children.length == 0)
return;

if (gadgets.menu.hasFocus && gadgets.menu.nodeInFocus.data.type && gadgets.menu.nodeInFocus.data.action == "tag"){
gadgets.tags.favourite4 = gadgets.menu.nodeInFocus.data.id;
gadgets.message.replace ("tagFavouriteDefined", "4", gadgets.menu.nodeInFocus.data.text);
return;
}

};
