
me.eventRefresh = function (data=false){
me.children = [];
me.nodesById = {};
me.element.innerHTML = "";
me.nodeInFocus = false;
me.firstNode = false;
me.lastNode = false;
me.fields = me.nodeGetColumns();

if (gadgets.message.nextText == false)
gadgets.message.replace ("listReady");

if (data == false || !data.children || !data.children.length || data.children.length == 0)
{ // create empty list

var row = document.createElement ("DIV");
row.id = "list_0";
row.setAttribute ("role", "option");


var column = document.createElement ("SPAN");
column.appendChild (document.createTextNode (interface.utils.getMessage ("listEmpty")));

row.appendChild (column);

me.element.appendChild (row);
me.element.setAttribute ("aria-activedescendant", "list_0");
me.nodeCountSelected();
interface.audio.alertLoad();
return;
} // create empty list

for (var i = 0; i < data.children.length; i ++){
var child = data.children[i];
if (child.type == "dir")
new nodeInterface (ecolabore, me, child);
}

for (var i = 0; i < data.children.length; i ++){
var child = data.children[i];
if (child.type != "dir")
new nodeInterface (ecolabore, me, child);
}

me.nodeInFocus = me.children[0];
me.nodeInFocus.selected = true;
me.nodeInFocus.element.className = "focus";
me.element.setAttribute ("aria-activedescendant", me.nodeInFocus.id);
me.firstNode = me.nodeInFocus;
me.lastNode = me.children[me.children.length - 1];
interface.audio.alertLoad();
};
