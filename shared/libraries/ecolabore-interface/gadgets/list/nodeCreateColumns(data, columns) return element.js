
me.nodeCreateColumns = function (data, columns){
var row = document.createElement ("DIV");
for (var i = 0;  i < columns.length; i++){
var field = columns[i];

if (me.columns[field])
var column = me.columns[field](data);
else{
var column = document.createElement("SPAN");
column.className = "column";
if (data[field])
column.appendChild (document.createTextNode (data[field]));
}

row.appendChild(column);
}

return row;
};
