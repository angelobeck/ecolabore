<?php

class eclFilter_personaliteFields_created
{ // class eclFilter_personaliteFields_created

static function create ($fieldName, $control, $formulary)
{ // function create
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
global $store;

if (!isset ($formulary->data['created']))
return;

$control['type'] = 'view';

$caption = $store->control->read ('personaliteFields_created_value');
$control['content'] = $caption['text']['caption'];
$control['created'] = $formulary->data['created'];

$formulary->appendChild ($control);
} // function view

static function column ($document, $data, $name, $field, $url)
{ // function column
if ($document->printableLayout)
return array ('caption' => $document->textMerge (date ('d-m-Y h:i', $data['created'])));

return array (
'caption' => $document->textMerge (date ('d-m-Y H:i', $data['created'])), 
'url' => $url
);
} // function column

} // class eclFilter_personaliteFields_created

?>