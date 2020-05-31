<?php

class eclMod_userHome_index_domains
{ // class eclMod_userHome_index_domains

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $io, $store, $system;
$document = $this->document;
$me = $document->application;
$mod->data = array_replace ($store->control->read ('modules/list_card'), $store->control->read ('userHome_index_domains'));

$row = $mod->appendChild ();

$where = array (
'user_id' => $me->userId, 
'group_id' => 1
);

$groups = $io->database->select ($store->domainGroup, $where);

if (!$groups)
return;

$names = [];
foreach ($groups as $group)
{ // each group
$name = $store->domain->getName ($group['domain_id']);
$status = $store->domain->getStatus ($name);

if ($status == 2 or $status == 4)
$names[] = $name;
} // each group

if (!$names)
return;

asort ($names);

foreach ($names as $name)
{ // list domains
$domain = $system->child ($name);

$row->appendChild ($domain->data)
->swapTitle ()
->url ($domain->pathway);
} // list domains

$mod->enabled = true;
} // function setModule

} // class eclMod_userHome_index_domains

?>