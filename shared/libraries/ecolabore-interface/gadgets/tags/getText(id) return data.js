
me.getText = function (id){
for (var i = 0; i < me.children.length; i++){
var child = me.children[i];
if (child.id == id)
return child.text;
}


};