<?php

class eclMod_financialResources_list
{ // class eclMod_financialResources_list

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

$mod->data = $store->control->read ('financialResources_list');
$mod->data = array_replace_recursive ($store->control->read ('modules/list_card'), $mod->data);

$row = $mod->appendChild ();
$children = $store->domainContent->children ($me->domainId, MODE_ACCOUNT, 0);
foreach ($children as $data)
{ // each data
$row->appendChild ($data)
->appendFolder ($data['name']);
} // each data

$mod->enabled = true;
} // function setModule

} // class eclMod_financialResources_list

?>