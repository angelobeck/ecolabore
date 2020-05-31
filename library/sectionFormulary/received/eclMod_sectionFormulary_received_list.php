<?php

class eclMod_sectionFormulary_received_list
{ // class eclMod_sectionFormulary_received_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application;

$mod->data = $store->control->read ('sectionFormulary_received_list');

// Conditions
if (!isset ($me->parent->data['extras']['formulary']))
return;

$from = &$me->parent->data['extras']['formulary'];

// Get listable fields
$sorted = [];
foreach ($from as $name => $field)
{ // sort fields
if (isset ($field['list_index']))
$sorted[$field['list_index']] = $name;
} // sort fields
ksort ($sorted);

if (!$sorted)
{ // append created column
$from['created'] = $store->control->read ('personaliteFields_created_preset');
$sorted[] = 'created';
} // append created column

// Table headers
$row = $mod->appendChild ();
foreach ($sorted as $name)
{ // each field
$row->appendChild ($from[$name]);
} // each field

// list received forms
$children = $store->domainExtras->children ($me->domainId, MODE_FORM, $me->parent->id);
foreach ($children as $data)
{ // each child
if ($data['status'] != 0 and ($data['status'] < 720 or $data['status'] > 729))
continue;

$pathway = $me->pathway;
$pathway[] = $data['id'];
$url = $document->url ($pathway);

$row = $mod->appendChild ();

foreach ($sorted as $name)
{ // each column
list ($filter) = explode ('_', $name, 2);

if (!preg_match ('/[a-z]+/', $filter))
{ // error
$row->appendChild ();
continue;
} // error

$class = 'eclFilter_personaliteFields_' . $filter;
$local = $class::column ($document, $data, $name, $from[$name], $url);
if (!$local)
continue;

$row->appendChild ($local);
} // each column
} // each child

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionFormulary_received_list

?>