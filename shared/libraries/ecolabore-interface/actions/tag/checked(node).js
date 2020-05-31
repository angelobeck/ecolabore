
me.checked = function (node){
var tag = node.data;

if (!gadgets.list.nodeInFocus)
return false;

var data = gadgets.list.nodeInFocus.data;
if (!data.tags || data.tags.length == 0)
return false;

for (var i = 0; i < data.tags.length; i++){
if (data.tags[i] == tag.text)
return true;
}

return false;
};
