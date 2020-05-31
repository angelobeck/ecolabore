
constructor (ecolabore, parent, data, recursive=false, firstLevel=false){
this.ecolabore = ecolabore;
this.parent = parent;
this.index = parent.children.length;
parent.children[this.index] = this;
this.data = data;
this.children = [];
this.id = parent.id + "_" + this.index;
me.nodesById[this.id] = this;

this.container = document.createElement ("DIV");
this.element = document.createElement ("DIV");
this.group = document.createElement ("DIV");

this.container.className = "tree-item";

this.element.setAttribute ("role", "treeitem");

this.element.onclick = function (event){
me.nodeChangeFocus (me.nodeInFocus, me.nodesById[this.id]);
me.keyEnter(); 
};
this.element.addEventListener ("contextmenu", me.eventContextMenu);
this.element.id = this.id;

var labelText = document.createElement ("DIV");
labelText.className = "tree-label";
if (data.text)
labelText.appendChild (document.createTextNode (data.text));
else if (data.name)
labelText.appendChild (document.createTextNode (data.name));

this.element.appendChild (labelText);

this.group.setAttribute ("role", "group");
this.group.hidden = true;
this.group.className = "tree-group";

this.container.appendChild (this.element);
this.container.appendChild (this.group);

if (firstLevel)
parent.element.appendChild (this.container);
else
parent.group.appendChild (this.container);

if (recursive && data.children && data.children.length > 0)
{ // create child nodes
for (var i = 0; i < data.children.length; i++){
if (!data.children[i].type || data.children[i].type != "dir")
continue;

var child = new nodeInterface (ecolabore, this, data.children[i], true);
}
if (this.children.length > 0)
this.element.setAttribute ("aria-expanded", false);
} // create child nodes
};
