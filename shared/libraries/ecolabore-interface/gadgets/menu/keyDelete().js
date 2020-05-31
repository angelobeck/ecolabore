
me.keyDelete = function (){
var data = me.nodeInFocus.data;

if (!data.action)
return;

if (actions[data.action].delete)
actions[data.action].delete();
};
