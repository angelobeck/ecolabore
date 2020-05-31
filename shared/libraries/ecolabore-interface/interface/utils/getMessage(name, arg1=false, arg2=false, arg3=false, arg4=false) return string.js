
me.getMessage = function (name, arg1=false, arg2=false, arg3=false, arg4=false){
if (!interface.messages[name])
return "";
var message = interface.messages[name];
var lang = interface.config.lang;
if (message[lang])
message = message[lang];
else
message = message["en"];

if (arg1 == false)
return message;
message = message.replace(/[%][1]/g, arg1);

if (arg2 == false)
return message;
message = message.replace(/[%][2]/g, arg2);

if (arg3 == false)
return message;
message = message.replace(/[%][3]/g, arg3);

if (arg4 == false)
return message;
message = message.replace(/[%][4]/g, arg4);

return message;
};
