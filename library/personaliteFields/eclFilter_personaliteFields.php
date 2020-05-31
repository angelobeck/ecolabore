<?php

class eclFilter_personaliteFields
{ // class eclFilter_personaliteFields

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$document = $formulary->document;
$me = $document->application;
$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'manager';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$item->data['help_msg'] = 'system_msg_filterDetailsHelp';

// references
$pathway = $me->pathway;
$pathway[] = '-fields';
$item->data['url_add'] = $document->url ($pathway);
$item->data['move-enable'] = 1;
$item->data['remove-enable'] = 1;
$item->data['edit-enable'] = 1;

if (!isset ($formulary->data['extras']['formulary']))
return;

$buffer = '';
foreach ($formulary->data['extras']['formulary'] as $name => $field)
{ // each field
$pathway = $me->pathway;
$pathway[] = '-fields';
$pathway[] = $name;

$local = array (
'value' => $name, 
'caption' => $field['caption'], 
'url' => $document->url ($pathway)
);
$item->appendChild ($local);
} // each field

$item->data['serialized'] = $buffer;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($formulary->data['extras']['formulary']))
$formulary->data['extras']['formulary'] = [];

$original = $formulary->data['extras']['formulary'];
unset ($formulary->data['extras']['formulary']);

$fields = [];

if (!isset ($formulary->received[$fieldName . '_serialized']))
return $formulary->setRequiredMsg ($fieldName, $control);

$index = 0;
$serialized = $formulary->received[$fieldName . '_serialized'];
foreach (explode (LF, $serialized) as $name)
{ // each line
$name = trim ($name);
if (isset ($original[$name]))
{ // exists
$fields[$name] = $original[$name];
$fields[$name]['index'] = $index;
$index++;
} // exists
} // each line

$formulary->data['extras']['formulary'] = $fields;
} // function save

} // class eclFilter_personaliteFields

?>