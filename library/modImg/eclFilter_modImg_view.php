<?php

class eclFilter_modImg_view
{ // class eclFilter_modImg_view

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;
if (!isset ($formulary->data['extras']['img_0']['files']['img']))
return;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'descriptive';
$local['text']['content']['pt'] = array (
TEXT_CONTENT => '<div class="position-center" style="padding-top:0.5rem; padding-bottom:0.5rem;">
<div class="relative-wd-sm-12 relative-wd-lg-6">
<div class="box">
<img src="'
 . $document->urlFiles ($formulary->data['extras']['img_0']['files']['img'])
 . QUOT . ' alt="" style="width:100%; height:auto">
</div>
</div>
</div>
', 
TEXT_HTML => 2
);

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_modImg_view

?>