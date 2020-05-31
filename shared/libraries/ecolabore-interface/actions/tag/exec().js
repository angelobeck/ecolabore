
me.exec = function (){
var id = gadgets.menu.nodeInFocus.data.id;
if (!gadgets.list.nodeInFocus)
return;
var data = gadgets.list.nodeInFocus.data;
if (!data.tags){
data.tags = [ id ];
return;
}

for (var i = 0; i < data.tags.length; i++){
if (data.tags[i] == id){
delete data.tags[i];
return;
}
}
data.tags[data.tags.length] = id;
};