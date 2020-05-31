<?php

class eclMod_homeChat_list
{ // class eclMod_homeChat_list

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
'owner_id' => $me->userId, 
'mode' => MODE_GROUP, 
);

$groups = $store->domainExtras->search ($where);
foreach ($groups as $group)
{ // each recent chat
$data = $store->domainContent->openById ($group['domain_id'], $group['parent_id']);

$row->appendChild ($data)
->set ('updated', $data['coments_last_update'])
->appendFolder ('group-' . $group['domain_id'] . '-' . $group['id']);
} // each recent chat

$chats = $store->userExtras->children ($me->userId, MODE_GROUP, 0);

foreach ($chats as $chat)
{ // each recent chat
$data = $store->userContent->open ($chat['owner_id'], '-register');

$row->appendChild ($data)
->set ('updated', $chat['updated'])
->appendFolder ($store->user->getName ($chat['owner_id']));
} // each recent chat

if (!$row->children)
return;

$row->sort ('updated', 'desc');
$mod->enabled = true;

$mod->data = $store->control->read ('modules/list');
} // function setModule

} // class eclMod_homeChat_list

?>