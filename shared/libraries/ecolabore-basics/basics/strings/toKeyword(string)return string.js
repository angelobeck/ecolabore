
me.toKeyword = function (input){
var buffer = "";
var a = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789ÁáÂâÃãÀàÄäÉéÈèÊêËëÍíÌìÎîÏïÓóÒòÔôÕõÖöÚúÙùÛûÜüÇçÑñ _-";
var b = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz0123456789aaaaaaaaaaeeeeeeeeiiiiiiiioooooooooouuuuuuuuccnn__-";
var length = input.length;
for(var i = 0; i < length; i++){
var char = input.charAt(i);
for(var j = 0; j < 114; j++){
if(char != a.charAt(j))
continue;

buffer += b.charAt(j);
break;
}
}
return buffer.replace(/^[_-]+|[_-]+$/g, '');
};
