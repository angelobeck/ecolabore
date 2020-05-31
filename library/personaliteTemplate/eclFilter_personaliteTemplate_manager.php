<?php

class eclFilter_personaliteTemplate_manager
{ // class eclFilter_personaliteTemplate_manager

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$document = $formulary->document;
$me = $document->application;
$folder = $control['flags']['folder'];
$length = strlen ($folder);
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
$pathway = array ($document->domain->name, '-personalite', 'template', $control['flags']['folder']);
$item->data['url_add'] = $document->url ($pathway);
$item->data['move-enable'] = 0;
$item->data['remove-enable'] = 1;
$item->data['edit-enable'] = 1;

foreach ($store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0) as $data)
{ // each user detail
if (substr ($data['name'], 0, $length) != $folder)
continue;

if (isset ($data['text']['caption']))
$caption = $document->textMerge ($data['name'], ' ', $data['text']['caption']);
else
$caption = $document->textMerge ($data['name']);
$configure = $me->pathway;
$configure[] = $data['name'];

$item->appendChild (array (
'value' => $data['name'], 
'caption' => $caption, 
'url' => $document->url ($configure), 
'url_remove' => $document->url (true, true, '_remove-' . $data['id'])
));
} // each line
return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_personaliteTemplate_manager

?>