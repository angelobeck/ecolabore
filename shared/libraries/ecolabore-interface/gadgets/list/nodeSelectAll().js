
me.nodeSelectAll = function (){
for (var i = 0; i < me.children.length; i++){
me.children[i].selected = true;
}
me.nodeCountSelected();
};
