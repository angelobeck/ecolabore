
me.nodeCountSelected = function (){
var selected = 0;
var children = me.children;
for (var i = 0; i < children.length; i++){
if (children[i].selected)
selected ++;
}

if (selected == 0)
gadgets.message.replace ("listSelectedZeroElements");
else if (selected == 1)
gadgets.message.replace ("listSelectedOneElement");
else
gadgets.message.replace ("listSelectedNElements", selected);

return selected;
};
