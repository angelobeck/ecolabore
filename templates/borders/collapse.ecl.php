'text'={
'caption'={
'pt'={
1='Bloco recolhido'
2=1
}
'en'={
1='Collapsed block'
}
}
}
'html'='
<!-- collapsed -->
<div role="button" onclick="this.setAttribute(''aria-expanded'', this.nextElementSibling.hidden); this.nextElementSibling.hidden = !this.nextElementSibling.hidden;" aria-expanded="false" class="caption" tabindex="0" style="display:block">[$label]</div>
<div class="collapsed" style="padding-left:2em" hidden>
[$value]
<div role="button" onclick="this.parentElement.hidden = true; this.parentElement.previousElementSibling.setAttribute(''aria-expanded'', false); this.parentElement.previousElementSibling.focus();" tabindex="0" style="display:block">[text:layout_collapse_content]</div>
</div>
<!-- /collapse -->
'
