'html'='[loop{
if (!$first){]
<hr class="layer-separator">
[}
list{]
<div[inline_class:list list-layout-$mod.list-layout??grid list-align-$mod.list-align col-sm-$mod.col-sm col-md-$mod.col-md col-lg-$mod.col-lg col-xl-$mod.col-xl list-column-gap-$mod.list-column-gap list-row-gap-$mod.list-row-gap details-horizontal-align-$mod.details-hhorizontal-align details-vertical-align-$mod.details-vertical-align]>
[loop{]
<div class="details">
<div class="alignment">
[details]
</div>
</div>
[}]
</div>
[}}]
'
