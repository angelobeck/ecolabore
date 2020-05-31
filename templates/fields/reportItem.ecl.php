'text'={
'caption'={
'pt'={
1='Item de relatório'
2=1
}
'en'={
1='Report item'
}
}
}
'html'='
<div class="report-item">
<h3>[text]</h3>
[if($alert){]
<div class="report-alert"><span>[text:field_report_alert]</span></div>
[}elseif($fail){]
<div class="report-fail"><span>[text:field_report_fail]</span></div>
[}elseif($ok){]
<div class="report-ok"><span>[text:field_report_ok]</span></div>
[}]
</div>

<div class="report-description">
[
if($ok){ text($content_ok); }
elseif($alert){ text($content_alert); }
elseif($fail){ text($content_fail); }
else{ text($content); }
]
</div>
'
