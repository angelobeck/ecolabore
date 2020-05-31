<?php

class eclMod_financialProjects_list
{ // class eclMod_financialProjects_list

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

$mod->data = $store->control->read ('financialProjects_list');
$mod->data = array_replace_recursive ($store->control->read ('modules/list_card'), $mod->data);

$row = $mod->appendChild ();

$where = array (
'domain_id' => $me->domainId, 
'mode' => MODE_SECTION, 
'marker' => 60
);
$projects = $store->domainContent->search ($where);

foreach ($projects as $data)
{ // each data
$row->appendChild ($data)
->appendFolder ($data['name']);
} // each data

$mod->enabled = true;
} // function setModule

} // class eclMod_financialProjects_list

?>