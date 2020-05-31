<?php

class eclMod_sectionFormulary_selectColumns_list
{ // class eclMod_sectionFormulary_selectColumns_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application->parent;
$mod->data = $store->control->read ('modules/list');

if (!isset ($me->data['extras']['formulary']))
return;

foreach ($me->data['extras']['formulary'] as $name => $field)
{ // each field
if (!isset ($field['filter']))
continue;

switch ($field['filter'])
{ // switch filter
case 'address':
case 'checkbox':
case 'created':
case 'mail':
case 'phone':
case 'radio':
case 'select':
case 'text':
case 'user':
case 'status':

$mod->appendChild ($field, array ('name' => $name));
} // switch filter
} // each field

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionFormulary_selectColumns_list

?>