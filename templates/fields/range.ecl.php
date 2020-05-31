'local'={
'filters'='numeric'
}
'text'={
'caption'={
'pt'={
1='Seletor deslizante'
2=1
}
'en'={
1='Sliding selector'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text]</label>
<input type="range" id="[$name]" name="[$name]" min="[$min]" max="[$max]" step="[$step]" value="[$value]" class="form-col-input input">
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
