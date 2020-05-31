
me.unescapeString = function (input){
return input
.replace (/\\r/g, "")
.replace (/\\n/g, "\n")
.replace (/[#]b/g, "\\")
.replace (/[#]q/g, '"')
.replace (/[#]c/g, "#")
;
};
