
constructor (ecolabore, parent, data){
this.ecolabore = ecolabore;
this.parent = parent;
this.index = parent.children.length;
parent.children[this.index] = this;
this.data = data;
this.id = parent.id + "_" + this.index;
me.nodesById[this.id] = this;

this.element = document.createElement ("DIV");
this.element.setAttribute ("role", "option");
this.element.onclick = function (event){
me.nodeChangeFocus (me.nodeInFocus, me.nodesById[this.id]);
if (event.ctrlKey)
me.keyControlSpace();
else
me.keyEnter(); 
};
this.element.addEventListener ("contextmenu", me.eventContextMenu);
this.element.id = this.id;

var fields = parent.fields;
for (var i = 0; i < fields.length; i++){
var field = fields[i];

if (parent.columns[field])
var column = parent.columns[field](data);
else if (data[field]){
var column = document.createElement ("SPAN");
column.appendChild (document.createTextNode (data[field]));
}else
var column = document.createElement ("SPAN");
this.element.appendChild (column);
}

parent.element.appendChild (this.element);
};
