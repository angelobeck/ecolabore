<?php

class eclFilter_sectionFormulary_received_selectColumns
{ // class eclFilter_sectionFormulary_received_selectColumns

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;
$me = $document->application->parent;
$control['name'] = $fieldName;
$control['type'] = 'manager';
$item = $formulary->appendChild ($control);

// references
$pathway = $me->pathway;
$pathway[] = '-columns';
$item->data['url_add'] = $document->url ($pathway);
$item->data['move-enable'] = 1;
$item->data['remove-enable'] = 1;
$item->data['edit-enable'] = 0;

if (!isset ($me->data['extras']['formulary']))
return;

$sorted = [];
foreach ($me->data['extras']['formulary'] as $name => $field)
{ // each field
switch ($field['filter'])
{ // switch filter
case 'checkbox':
case 'created':
case 'mail':
case 'radio':
case 'select':
case 'text':
if (isset ($field['list_index']))
$sorted[$field['list_index']][] = array ($name, $field);
} // switch filter
} // each field
ksort ($sorted);

$buffer = '';
foreach ($sorted as $group)
{ // each group
foreach ($group as $column)
{ // each column
list ($name, $field) = $column;
$buffer .= $name . CRLF;

// $pathway = $me->pathway;
// $pathway[] = '-columns';
// $pathway[] = $name;

$local = array (
'value' => $name, 
'caption' => $field['caption'], 
// 'url' => $document->url ($pathway)
);
$item->appendChild ($local);
} // each column
} // each group

$item->data['serialized'] = $buffer;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$me = $formulary->document->application->parent;
if (!isset ($me->data['extras']['formulary']))
return;

if (!isset ($formulary->received[$fieldName . '_serialized']))
return $formulary->setRequiredMsg ($fieldName, $control);

foreach ($me->data['extras']['formulary'] as &$field)
{ // clear each field
unset ($field['list_index']);
} // clear each field
unset ($field);

$serialized = $formulary->received[$fieldName . '_serialized'];
$index = 0;
foreach (explode (LF, $serialized) as $name)
{ // each line
$index++;
$name = trim ($name);

if (isset ($me->data['extras']['formulary'][$name]))
$me->data['extras']['formulary'][$name]['list_index'] = $index;
} // each line
} // function save

} // class eclFilter_sectionFormulary_received_selectColumns

?>