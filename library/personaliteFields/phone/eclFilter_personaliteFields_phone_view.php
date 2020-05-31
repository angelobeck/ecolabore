<?php

class eclFilter_personaliteFields_phone_view
{ // class eclFilter_personaliteFields_phone_view

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;

$control['name'] = $fieldName;

// type
$control['type'] = 'view';

if (!isset ($formulary->data['local']['phone']))
return;

if (isset ($formulary->data['local']['phone']['country']))
$tel[] = $formulary->data['local']['phone']['country'];
if (isset ($formulary->data['local']['phone']['area']))
$tel[] = $formulary->data['local']['phone']['area'];
if (isset ($formulary->data['local']['phone']['number']))
$tel[] = $formulary->data['local']['phone']['number'];

$control['content'] = $document->textMerge (implode (' ', $tel));
$control['url'] = 'tel:' . implode ('-', $tel);
$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function column ($data, $name, $field, $url, $document)
{ // function column
return [];
} // function column

} // class eclFilter_personaliteFields_phone_view

?>