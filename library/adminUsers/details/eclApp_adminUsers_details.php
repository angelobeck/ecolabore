<?php

class eclApp_adminUsers_details
{ // class eclApp_adminUsers_details

static function is_child ($me, $name)
{ // function is_child
global $store;
if (!strlen ($name))
return false;
if ($store->user->getId ($name))
return true;
return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->userId = $store->user->getId ($me->name);
$me->data = $store->control->read ('adminUsers_details_content');
$data = $store->userContent->open ($me->userId, '-register');
if (isset ($data['text']['caption']))
$me->data['text']['caption'] = $data['text']['caption'];
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;
$data = &$store->userContent->open ($me->userId, '-register');
if (isset ($data['local']['gender']) and $data['local']['gender'] == 'female')
$gender = 'F';
else
$gender = 'M';

$remove = $document->createFormulary ('adminUsers_details_removeConfirm' . $gender, [], 'userRemove');
if ($remove->save ())
return self::action_remove ($document);
elseif ($remove->errorMsg)
$document->mod->humperstilshen->alert ($remove->errorMsg);

$formulary = $document->createFormulary ('adminUsers_details_edit', $data, 'userEdit');
$formulary->data['name'] = $me->name;
$status = $store->user->getStatus ($me->name);
$formulary->data['status'] = $status;
if ($formulary->save ())
{ // save
$formulary->data['name'] = '-register';
$data = $formulary->data;
if ($formulary->data['status'] != $status or isset ($formulary->data['password']))
{ // update status
$user = &$store->user->open ($me->name);
$user['status'] = $formulary->data['status'];
if (isset ($formulary->data['password']))
{ // update password
$user['password'] = $formulary->data['password'];
unset ($data['password']);
} // update password
} // update status
$formulary->errorMsg = 'system_msg_alertDataUpdated';
} // save
$formulary->data['name'] = $me->name;

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->dataMerge ('adminUsers_details_content');

// Context Remove
$document->mod->context->appendChild ('adminUsers_details_remove' . $gender)
->confirm ('adminUsers_details_removeConfirm' . $gender, $remove);
} // function dispatch

static function action_remove ($document)
{ // function action_remove
global $io, $store;
$me = $document->application;
$id = $me->userId;
$io->database->delete ($store->userContent, array ('user_id' => $id));
$io->database->delete ($store->domainGroup, array ('user_id' => $id));
$io->database->delete ($store->userFriend, array ('user_id' => $id));
$io->database->delete ($store->userFriend, array ('friend_id' => $id));
$io->database->delete ($store->userExtras, array ('user_id' => $id));
$store->user->delete ($id);
foreach (scandir (PATH_PROFILES . $me->name) as $filename)
{ // each user file
if (is_file (PATH_PROFILES . $me->name . '/' . $filename))
unlink (PATH_PROFILES . $me->name . '/' . $filename);
} // each user file
rmdir (PATH_PROFILES . $me->name);

// returns to parent page
$parent = $me->parent;
$parent->reset ();
$document->application = $parent;
$document->application->dispatch ($document);
$document->reload = $document->url ();
} // function action_remove

} // class eclApp_adminUsers_details

?>