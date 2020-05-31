'html'='[loop{
if (!$first){]
<hr class="layer-separator">
[}
list{]
<div[inline_class:list-layout-$mod.list-layout??grid list-align-$mod.list-align col-sm-$mod.col-sm col-md-$mod.col-md col-lg-$mod.col-lg $col-xl-$mod.col-xl list-column-gap-$mod.list-column-gap list-row-gap-$mod.list-row-gap]>
[loop{]
<div class="detail">
[details]
</div>
[}]
</div>
[}}]
'
