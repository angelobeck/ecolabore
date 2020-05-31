
me.enabled = function (){
if (!gadgets.list.nodeInFocus)
return false;

var data = gadgets.list.nodeInFocus.data;
if (!data.type || data.type != "file")
return false;

return true;
};
