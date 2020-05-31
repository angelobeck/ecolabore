<?php

class eclMod_adminUsers_list
{ // class eclMod_adminUsers_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$row = $mod->appendChild ();

if ($document->access (4))
{ // access
$pathway = $document->application->pathway;

// User add
$user_pathway = $pathway;
$user_pathway[] = '-new-user';
$row->appendChild ('adminUsers_add_content')
->virtual ()
->url ($user_pathway);
} // access
else
$pathway = array (SYSTEM_PROFILES_URI);

if (is_dir (FOLDER_PROFILES))
$names = $store->user->childrenNames ();
else
$names = [];

foreach ($names as $name)
{ // each name
$userId = $store->user->getId ($name);
$data = $store->userContent->open ($userId, '-register');
$user_pathway = $pathway;
$user_pathway[] = $name;
$row->appendChild ($data)
->swapTitle ()
->url ($user_pathway);
} // each name

$mod->data = $store->control->read ('modules/list');
$mod->enabled = true;
} // function setModule

} // class eclMod_adminUsers_list

?>