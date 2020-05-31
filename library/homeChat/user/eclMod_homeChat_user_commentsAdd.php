<?php

class eclMod_homeChat_user_commentsAdd
{ // class eclMod_homeChat_user_commentsAdd

public $document;
private $children;

public function __construct ($document)
{ // function __construct
global $store, $system;
$this->document = $document;

$formulary = $document->createFormulary ('homeChat_user_form', [], 'chat');

if ($formulary->command ('save') and $formulary->save ())
{ // save
$friend = $system->child (SYSTEM_PROFILES_URI)->child ($document->application->name);
$user = $document->user;

$friendChats = $store->userExtras->search (array (
'user_id' => $friend->userId, 
'mode' => MODE_GROUP, 
'owner_id' => $user->userId
));
if (isset ($friendChats[0]['id']))
{ // existing friend chat
$friendChatId = $friendChats[0]['id'];
$friendChat = &$store->userExtras->openById ($friend->userId, $friendChatId);
$friendChat['updated'] = TIME;
} // existing friend chat
else
{ // create friend chat id
$data = array (
'user_id' => $friend->userId, 
'mode' => MODE_GROUP, 
'owner_id' => $user->userId
);
$friendChatId = $store->userExtras->insert ($friend->userId, $data);
} // create friend chat id

$userChats = $store->userExtras->search (array (
'user_id' => $user->userId, 
'mode' => MODE_GROUP, 
'owner_id' => $friend->userId
));
if (isset ($userChats[0]['id']))
{ // existing user chat
$userChatId = $userChats[0]['id'];
$userChat = &$store->userExtras->openById ($user->userId, $userChatId);
$userChat['updated'] = TIME;
} // existing user chat
else
{ // create user chat id
$data = array (
'user_id' => $user->userId, 
'mode' => MODE_GROUP, 
'owner_id' => $friend->userId
);
$userChatId = $store->userExtras->insert ($user->userId, $data);
} // create user chat id

$toUserData = $formulary->data;
$toUserData['mode'] = MODE_COMMENT;
$toUserData['parent_id'] = $userChatId;
$toUserData['owner_id'] = $user->userId;
$masterId = $store->userExtras->insert ($user->userId, $toUserData);

$data = $formulary->data;
$data['mode'] = MODE_COMMENT;
$data['parent_id'] = $friendChatId;
$data['master_id'] = $masterId;
$data['owner_id'] = $user->userId;
$store->userExtras->insert ($friend->userId, $data);
} // save

$formulary->data = [];
$this->children = $formulary->create ()->children;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;

$mod->children = $this->children;
$mod->data = $render->block ('modules/comments_add');
$mod->enabled = true;
} // function setModule

} // class eclMod_homeChat_user_commentsAdd

?>