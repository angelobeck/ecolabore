
nodeCreate (data){
var index = this.nodes.length;
var node = new menuitemInterface (this, this, data);
if (index == 0){
this.nodeInFocus = node;
this.element.setAttribute ("aria-activedescendant", node.id);
}
};