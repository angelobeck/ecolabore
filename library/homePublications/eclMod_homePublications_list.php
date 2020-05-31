<?php

class eclMod_homePublications_list
{ // class eclMod_homePublications_list

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
$mod->data = $store->control->read ('userHome_index_recents');
$row = $mod->appendChild ();

$where = array (
'mode' => MODE_POST, 
'owner_id' => $me->userId
);

$recents = $store->domainContent->search ($where);

foreach ($recents as $data)
{ // each recent post
$pathway = $store->domainContent->pathway ($data['domain_id'], $data['name']);

$row->appendChild ($data)
->url ($pathway);
} // each recent post

if ($row->children)
$mod->enabled = true;
} // function setModule

} // class eclMod_homePublications_list

?>