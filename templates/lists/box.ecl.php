'html'='
<span class="sr-only">[text:layout_spotlight_start]</span>
[if $mod.editable{]<div data-name="[$mod.id]_box_[$mod.number]" data-mode="stack">[text($content, $mod.editable)]</div>
[}else{
text($content);
}]
<span class="sr-only">[text:layout_spotlight_end]</span>
'
