
me.keyControlSpace = function (){
if (!me.nodeInFocus)
return;

me.nodeInFocus.selected = !me.nodeInFocus.selected;

me.nodeCountSelected();
};
