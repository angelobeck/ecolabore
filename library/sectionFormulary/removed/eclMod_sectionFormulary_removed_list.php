<?php

class eclMod_sectionFormulary_removed_list
{ // class eclMod_sectionFormulary_removed_list

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

$fieldsList = array (
'created' => 'created'
);
$children = $store->domainExtras->children ($me->domainId, MODE_FORM, $me->parent->id);
foreach ($children as $data)
{ // each child
if ($data['status'] < 730 or $data['status'] > 739)
continue;

$pathway = $me->pathway;
$pathway[] = $data['id'];

$row = $mod->appendChild ();

foreach ($fieldsList as $fieldName => $fieldType)
{ // each field
switch ($fieldType)
{ // switch type
case 'created':
$local['caption'] = $document->textMerge (date ('d-m-Y h:i', $data['created']));
$row->appendChild ($local)
->url ($pathway);
break;
} // switch type
} // each field
} // each child

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionFormulary_removed_list

?>