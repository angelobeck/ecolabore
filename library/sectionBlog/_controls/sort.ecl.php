'text'={
'caption'={
'pt'={
1='Ordenar por'
2=1
}
'en'={
1='Sort by'
2=1
}
}
}
'html'='
<div>
<label>[text]
<select id="[$name]_select">
[list{loop{]
<option value="[$value]"[if($active){ ` selected`; }]>[text]</option>
[}}]
</select>
</label>
<button onclick="window.location = document.getElementById (''[$name]_select'').value"> [text:action_go] </button>
[personalite]
</div>
'
