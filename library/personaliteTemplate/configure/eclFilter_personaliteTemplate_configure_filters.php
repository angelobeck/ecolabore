<?php

class eclFilter_personaliteTemplate_configure_filters
{ // class eclFilter_personaliteTemplate_configure_filters

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$document = $formulary->document;
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
$pathway = array ($document->domain->name, '-personalite', 'template', '-filters');
$item->data['url_add'] = $document->url ($pathway);
$item->data['move-enable'] = 1;
$item->data['remove-enable'] = 1;
$item->data['edit-enable'] = 0;

$filters = $formulary->getField ('local/filters');
if (!isset ($filters[0]))
return;

$item->data['serialized'] = $filters;
foreach (explode (CRLF, $filters) as $name)
{ // each filter
$data = $store->control->read ('sectionBlog_post_edit' . ucfirst ($name));
if (!isset ($data['flags']['filters_manager']))
continue;

$item->appendChild (array (
'value' => $name, 
'caption' => $data['text']['caption'], 
));
} // each line
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName . '_serialized']))
$formulary->data['local']['filters'] = $formulary->received[$fieldName . '_serialized'];
else
unset ($formulary->data['local']['filters']);
} // function save

} // class eclFilter_personaliteTemplate_configure_filters

?>