
me.exec = function (){
interface.clipboard = {};

if (interface.focus.elementInFocus === gadgets.tree.element){

var data = gadgets.tree.nodeInFocus.data;
if (!data.location  || data.location == "" || data.location == "/")
return gadgets.message.replace ("fileCopyZeroFilesCopied");

interface.clipboard = { "command":"move", "files":[ data.location ]};
return gadgets.message.replace ("fileCopyOneFileCopied");
}

var selected = 0;
var files = [];
var children = gadgets.list.children;
for (var i = 0; i < children.length; i++){
var child = children[i];
if (!child.selected)
continue;
if (!child.data.location)
continue;

files[selected] = child.data.location;
selected ++;
}

if (selected == 0)
return gadgets.message.replace ("fileCopyZeroFilesCopied");

interface.clipboard = { "command":"move", "files":files };

if (selected == 1)
gadgets.message.replace ("fileCopyOneFileCopied");
else
gadgets.message.replace ("fileCopyNFilesCopied", selected);

};
