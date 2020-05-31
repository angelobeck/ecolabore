'text'={
'caption'={
'pt'={
1='Iniciar relatório'
2=1
}
'en'={
1='Report start'
}
}
}
'html'='

[style]
.report { font-family:arial, sans-serif; }
.report-item { display:grid; grid-template-columns:auto 5em; }
.report-description {padding-left:3em; }
.report-ok, .report-alert, .report-fail { width:5em; height:5em; position:relative; border-radius:50%; overflow:hidden; text-align:center;  color:#fff; }
.report-ok > span, .report-alert > span, .report-fail > span { position:relative; top:50%; transform:translateY(-50%); }
.report-ok { background-color:#090;}
.report-alert { background-color:#c90; }
.report-fail { background-color:#c00; }
[/style]
<div class="report">
'
