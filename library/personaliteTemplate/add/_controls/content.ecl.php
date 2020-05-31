'flags'={
'modLayout_base'='responsive'
'modLayout_from'='control'
'modLayout_name'='personaliteTemplate_add_content'
}
'local'={
'scheme'='system'
}
'text'={
'caption'={
'pt'={
1='Adicionar detalhe'
2=1
}
'en'={
1='Add detail'
}
}
}
'html'='

[mod:list{]
<select id="modules" name="modules" size="10" onkeypress="keyListener(event)" ondblclick="sendChoice()">
[list{ loop{]
<option value="[$name]" data-url="[$url]">[text]</option>
[}}]
</select>
<hr>
<button onclick="sendChoice()"[inline_lang:action_choose]>[text:action_choose]</button>
<button onclick="window.close()">[text:action_close]</button>
[}]
[style]
/* align window elements */
BODY { text-align:center; }
[/style]
[script]


function keyListener (event)
{ // key listener
if (event.key == ''Enter'')
sendChoice();
} // key listener
function sendChoice ()
{ // sendChoice
var select = document.getElementById ("modules");
if (select.selectedIndex >= 0)
{ // valid selection
window.location = select.options[]select.selectedIndex].dataset.url;
} // valid selection
} // sendChoice

select = document.getElementById ("modules");
if (select.length)
{
select.selectedIndex = 0;
select.focus();
}
[/script]
'
