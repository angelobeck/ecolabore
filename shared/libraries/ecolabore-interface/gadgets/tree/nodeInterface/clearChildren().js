
clearChildren(){
for (var i = 0; i < this.children.length; i++){
this.children[i].clearChildren();
delete me.nodesById[this.children[i].id];
}
this.children = [];
this.group.innerHTML = "";

};
