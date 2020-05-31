'local'={
'filters'='contactSite'
}
'text'={
'caption'={
'pt'={
1='Site (Criar link)'
2=1
}
'en'={
1='Site (create link)'
}
}
}
'html'='[if $site{]<div>
<a href="[$protocol]://[$site]">[$site]</a>
</div>
[}'
