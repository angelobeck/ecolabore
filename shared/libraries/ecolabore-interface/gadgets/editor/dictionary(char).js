
me.dictionary = function (char){

if (char == "\n")
return "Retorno";
if (char == "")
return "Fim do arquivo";
if (char == " ")
return "espaço";
if (char.toLowerCase() != char)
{
gadgets.message.progressBar.value = 0;
setTimeout (function (){ gadgets.message.progressBar.value = 50; }, 50);
return char;
}

return char;
};
