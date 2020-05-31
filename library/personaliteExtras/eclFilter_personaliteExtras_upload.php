<?php

class eclFilter_personaliteExtras_upload
{ // class eclFilter_personaliteExtras_upload

static function create ($fieldName, $control, $formulary)
{ // function create
$control['name'] = $fieldName;

// type
$control['type'] = 'file_big';

$control['url'] = $formulary->document->url ($formulary->document->pathway, true, '_upload-save', $formulary->protocol);

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_personaliteExtras_upload

?>