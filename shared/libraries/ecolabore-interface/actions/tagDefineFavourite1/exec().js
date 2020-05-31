
me.exec = function (){
if (gadgets.tags.children.length == 0)
return;

if (gadgets.menu.hasFocus && gadgets.menu.nodeInFocus.data.type && gadgets.menu.nodeInFocus.data.action == "tag"){
gadgets.tags.favourite1 = gadgets.menu.nodeInFocus.data.id;
gadgets.message.replace ("tagFavouriteDefined", "1", gadgets.menu.nodeInFocus.data.text);
return;
}

};
