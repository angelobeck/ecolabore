<?php

class eclFilter_personaliteFields_status
{ // class eclFilter_personaliteFields_status

static function create ($fieldName, $control, $formulary)
{ // function create
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
global $store;

if (!isset ($formulary->data['status']) or !$formulary->data['status'])
return;

$control['type'] = 'view';

$caption = $store->control->read ('labels/status/' . $formulary->data['status']);
if (!$caption)
return;

$control['content'] = $caption['text']['caption'];

$formulary->appendChild ($control);
} // function view

static function column ($document, $data, $name, $field, $url)
{ // function column
global $store;

if (!isset ($data['status']) or !$data['status'])
return array ('caption' => $document->textMerge ('&nbsp;'));

$caption = $store->control->read ('labels/status/' . $data['status']);
if (!isset ($caption['text']['caption']))
return array ('caption' => $document->textMerge ('&nbsp;'));

return $caption;
} // function column

} // class eclFilter_personaliteFields_status

?>