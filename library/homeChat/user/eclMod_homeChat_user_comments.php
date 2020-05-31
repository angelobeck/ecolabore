<?php

class eclMod_homeChat_user_comments
{ // class eclMod_homeChat_user_comments

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store, $system;

$document = $this->document;
$user = $document->user;
$render = $document->render;
$friend = $system->child (SYSTEM_PROFILES_URI)->child ($document->application->name);

$userChats = $store->userExtras->search (array (
'user_id' => $user->userId, 
'mode' => MODE_GROUP, 
'owner_id' => $friend->userId
));
if (!isset ($userChats[0]['id']))
return;

$userChatId = $userChats[0]['id'];

$posts = $store->userExtras->children ($user->userId, MODE_COMMENT, $userChatId);
if (!$posts)
return;

$row = $mod->appendChild ();

foreach ($posts as $data)
{ // each comment
$row->appendChild ($data);
} // each comment
$row->sort ();
$mod->data = $render->block ('modules/comments');
$mod->enabled = true;
} // function setModule

} // class eclMod_homeChat_user_comments

?>