
me.columns.size = function (data){
var column = document.createElement ("SPAN");

if (data.type == "dir")
return column;
else if(data.size == 0)
column.appendChild (document.createTextNode ("0Bytes "));
else if (!data.size)
return column;
else if(data.size == 1)
column.appendChild (document.createTextNode (data.size + "Byte "));
else if (data.size <1024)
column.appendChild (document.createTextNode (data.size + "Bytes "));
else if (data.size < 1048576){
var size = Math.ceil (data.size / 102.4) / 10;
column.appendChild (document.createTextNode (size + "KB "));
}

return column;
};
