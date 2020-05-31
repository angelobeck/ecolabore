
me.escapeString = function (input){
var buffer = '';

for (var i = 0; i < input.length; i++){
if(input.charAt(i) == '\\')
buffer += "#b";
else if(input.charAt(i) == '"')
buffer += "#q";
else if(input.charAt(i) == '#')
buffer += "#c";
else
buffer += input.charAt(i);
}

return buffer;
};
