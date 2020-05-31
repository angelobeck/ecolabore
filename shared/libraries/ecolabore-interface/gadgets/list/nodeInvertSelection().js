
me.nodeInvertSelection = function (){
for (var i = 0; i < me.children.length; i++){
var child = me.children[i];
child.selected = !child.selected;
}
};
