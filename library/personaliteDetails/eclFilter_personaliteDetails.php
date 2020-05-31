<?php

/*
* Valid control flags
* control_type = manager
* control_filter = personaliteDetails
* control_field_name
* control_target = local/details
* control_help
* control_help_msg
*/

class eclFilter_personaliteDetails
{ // class eclFilter_personaliteDetails

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$document = $formulary->document;
$me = $document->application;
$item = $document->createListItem ($control);

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
$pathway = array ($document->domain->name, '-personalite', 'details');
$item->data['url_add'] = $document->url ($pathway);
$item->data['move-enable'] = 1;
$item->data['remove-enable'] = 1;
$item->data['edit-enable'] = 1;

$userDetails = [];
foreach ($store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0) as $data)
{ // each user detail
if (substr ($data['name'], 0, 8) != 'details/')
continue;

$userDetails[substr ($data['name'], 8)] = $data;
} // each user detail

$value = trim (strval ($formulary->getField ('local/details')));
$buffer = '';
foreach (explode (' ', str_replace (CRLF, ' ',  $value)) as $name)
{ // each line
$name = trim ($name);
if (!strlen ($name))
continue;

if (isset ($userDetails[$name]))
$data = $userDetails[$name];
else
$data = $store->control->read ('details/' . $name);

if (isset ($data['text']['caption']))
$caption = $data['text']['caption'];
else
$caption = $document->textMerge ($name);
$p = $pathway;
$p[] = $name;

$buffer .= $name . CRLF;
$item->appendChild (false, array ('value' => $name, 'caption' => $caption))
->url ($p);
} // each line
$item->data['serialized'] = $buffer;
return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

$buffer = [];
if (isset ($formulary->received[$fieldName . '_serialized']))
{ // received
foreach (explode (' ', $formulary->received[$fieldName . '_serialized']) as $line)
{ // each line
$line = trim ($line);
if (preg_match ('/^[a-z][a-z0-9_]*$/', $line))
$buffer[] = $line;
} // each line
} // received
if ($buffer)
$buffer = implode (' ', $buffer);
else
$buffer = false;

$formulary->setField ('local/details', $buffer);
} // function save

} // class eclFilter_personaliteDetails

?>