
constructor (root, parent, data){
this.root = root;
this.parent = parent;
this.data = data;

this.index = parent.nodes.length;
this.id = parent.id + "_" + this.index;

if (root === parent){
var element = document.createElement ("SPAN");
var container = document.createElement ("SPAN");
}else{
var element = document.createElement ("DIV");
var container = document.createElement ("DIV");
}

var label = document.createTextNode (data.caption);
var submenu = document.createElement ("DIV");
submenu.hidden = true;

element.setAttribute ("role", "menuitem");
element.id = this.id;
element.appendChild (label);

container.appendChild (element);
container.appendChild (submenu);

this.element = element;
this.nodes = [];
this.parent.nodes[this.index] = this;

if (root === parent)
root.element.appendChild (container);
else
parent.element.nextElementSibling.appendChild (container);

root.nodesById[this.id] = this;
};
