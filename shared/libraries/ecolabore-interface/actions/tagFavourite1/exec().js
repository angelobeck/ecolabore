
me.exec = function (){
var id = gadgets.tags.favourite1;

if (!id)
return;

var text = gadgets.tags.getText (id);
if (!gadgets.list.nodeInFocus)
return;
var data = gadgets.list.nodeInFocus.data;
if (!data.tags){
data.tags = [ id ];
gadgets.message.replace ("tagApplied", text);
return;
}

for (var i = 0; i < data.tags.length; i++){
if (data.tags[i] == id){
delete data.tags[i];
gadgets.message.replace ("tagRemoved", text);
return;
}
}
data.tags[data.tags.length] = id;
gadgets.message.replace ("tagApplied", text);
};