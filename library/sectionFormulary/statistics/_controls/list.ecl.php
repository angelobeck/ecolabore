'text'={
'caption'={
'pt'={
1='Estatísticas'
2=1
}
'en'={
1='Statistics'
2=1
}
}
'units'={
'pt'={
1='Absoluto'
2=1
}
'en'={
1='Absolute'
}
}
'percent'={
'pt'={
1='Relativo'
}
'en'={
1='Relative'
}
}
}
'local'={
'caption-display'=1
'position'='-center'
}
'html'='
<table>
<tr><td>
</td><td>[text $units]</td>
<td>[text $percent]</td>
<td></td>
</tr>
[list{ loop{ if($header){]
<tr>
<td colspan="4"><span class="label">[text]</span></td>
</tr>
[list{ loop{]
<tr>
<td>[text]</td>
<td>[$units]</td>
<td>[$percent]%</td>
<td>
<div style="width:30vw">
<div style="width:[$percent]%; height:1em; background-color:#008; ">
</div>
</div>
</td>
</tr>
[}}}else{]
<td><span class="label">[text]</span></td>
<td>[$units]</td>
<td>[$percent]%</td>
<td>
<div style="width:30vw">
<div style="width:[$percent]%; height:1em; background-color:#008; ">
</div>
</div>
</td>
</tr>
[}}}]
</table>
'
