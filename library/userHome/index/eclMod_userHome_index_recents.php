<?php

class eclMod_userHome_index_recents
{ // class eclMod_userHome_index_recents

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store, $system;
$document = $this->document;
$me = $document->application;
$mod->data = array_replace ($store->control->read ('modules/list_card'), $store->control->read ('userHome_index_recents'));

$row = $mod->appendChild ();

$where = array (
'mode' => MODE_POST, 
'owner_id' => $me->userId
);

$recents = $store->domainContent->search ($where);
$recents = array_reverse ($recents);

$index = 0;
foreach ($recents as $data)
{ // each recent post
$pathway = $store->domainContent->pathway ($data['domain_id'], $data['name']);
$levels = $pathway;

$post = $system;
TEST_NEXT_LEVEL:
if (!$levels)
continue;

$post = $post->child (array_shift ($levels));
if ($post->access)
continue;
if ($levels)
goto TEST_NEXT_LEVEL;

$row->appendChild ($data)
->url ($pathway);

$index++;
if ($index == 10)
break;
} // each recent post

if ($index)
$mod->enabled = true;
} // function setModule

} // class eclMod_userHome_index_recents

?>