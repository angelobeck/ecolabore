'text'={
'caption'={
'pt'={
1='Botões de submissão'
2=1
}
'en'={
1='Submission buttons'
}
}
}
'html'='<div class="form-control">
<div class="form-col-label"></div>
<div class="form-col-input">
[list{ loop{]
[if $reset{]
<input type="reset" id="[$name]" name="[$name]" value="[text]"[lang] [if $onclick{ `onclick="`; $onclick; `"`; }] class="button">
[}elseif $onclick{]
<button onclick="[$onclick]"[lang]>[text]</button class="button">
[}else{]
<input type="submit" id="[$name]" name="[$name]" value="[text]"[lang] class="button">
[}
}}]
</div>
</div>
'
