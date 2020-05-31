<?php

class eclMod_personaliteFields_list
{ // class eclMod_personaliteFields_list

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
$mod->data = $store->control->read ('modules/list');
$row = $mod->appendChild ();

$list = array (
'text', 'textarea', 'checkbox', 'select', 'radio', 'separator', 'descriptive', 'mail', 'address', 'created', 'status'
);
foreach ($list as $type)
{ // each type
$pathway = $me->pathway;
$pathway[] = $type;
$row->appendChild ('personaliteFields_' . $type . '_content')
->url ($pathway, true, '_add');
} // each type

$mod->enabled = true;
} // function setModule

} // class eclMod_personaliteFields_list

?>