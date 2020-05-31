
me.enabled = function (){
if (interface.clipboard && interface.clipboard.command && (interface.clipboard.command == "copy" || interface.clipboard.command == "move"))
return true;

return false;
};
