
me.exec = function (){
interface.clipboard = {};

if (interface.focus.elementInFocus === gadgets.tree.element){
if (!gadgets.tree.nodeInFocus)
return gadgets.message.replace ("fileCopyZeroFilesCopied");

var data = gadgets.tree.nodeInFocus.data;
if (!data.location || data.location == "" || data.location == "/")
return gadgets.message.replace ("fileCopyZeroFilesCopied");

interface.clipboard = { "command":"copy", "files":[ data.location ]};
gadgets.message.replace ("fileCopyOneFileCopied");
return;
}

if (gadgets.list.children.length == 0)
return gadgets.message.replace ("fileCopyZeroFilesCopied");

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

interface.clipboard = { "command":"copy", "files":files };

if (selected == 1)
gadgets.message.replace ("fileCopyOneFileCopied");
else
gadgets.message.replace ("fileCopyNFilesCopied", selected);

};
