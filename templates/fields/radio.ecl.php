'local'={
'filters'='radio'
}
'text'={
'caption'={
'pt'={
1='Botões de rádio'
2=1
}
'en'={
1='Radio buttons'
}
}
}
'html'='
<fieldset>
<legend class="label">[text]</legend>
[list{ loop{]
<label class="form-control"><input type="radio" id="[$name]" name="[$name]" class="form-col-reverse-input input" value="[$value]" [if$active{ `checked `; }]><div class="form-col-reverse-label">[text]</div></label><br>
[}}]
</fieldset>
'
