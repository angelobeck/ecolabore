<?php

class eclApp_adminDomains_details
{ // class eclApp_adminDomains_details

static function is_child ($me, $name)
{ // function is_child
global $store;
if (!strlen ($name))
return false;
if ($store->domain->getId ($name))
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
global $store, $system;
$me->domainId = $store->domain->getId ($me->name);
$me->data = $store->control->read ('adminDomains_details_content');

$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store, $system;
$me = $document->application;

$data = &$store->domainContent->open ($me->domainId, '-register');
if (!$data)
{ // creates a register
$data = array ('domain_id' => $me->domainId, 'mode' => MODE_DOMAIN, 'parent_id' => 0, 'name' => '-register');
$store->domainContent->insert ($me->domainId, $data);
$document->reload = $document->url ();
$document->application = $me->parent;
$document->application->dispatch ($document);
return;
} // creates a register

$remove = $document->createFormulary ('adminDomains_details_contextRemoveConfirm', [], 'domainRemove');
if ($remove->save ())
return self::action_remove ($document);
elseif ($remove->errorMsg)
$document->mod->humperstilshen->alert ($remove->errorMsg);

$formulary = $document->createFormulary ('adminDomains_details_edit', $data, 'domainEdit');
$formulary->data['name'] = $me->name;
$status = $store->domain->getStatus ($me->name);
$formulary->data['status'] = $status;

if ($formulary->save ())
{ // formulary submited

if ($formulary->command ('admin_add') and $formulary->data['admin_id'])
{ // add a new administrator
$group = &$store->domainGroup->open ($me->domainId, 1);

$group[$formulary->data['admin_id']] = 4;
} // add a new administrator
elseif ($formulary->command ('admin_remove') and $formulary->data['admin_select'])
{ // remove an administrator
$group = &$store->domainGroup->open ($me->domainId, 1);
unset ($group[$formulary->data['admin_select']]);
} // remove an administrator
elseif ($formulary->command ('save') and !$formulary->errorMsg)
{ // save
$formulary->data['name'] = '-register';
if (!isset ($formulary->data['text']['caption']))
$formulary->data['text']['caption'] = $formulary->data['text']['title'];
$data = $formulary->data;
if ($formulary->data['status'] != $status)
{ // update status
$domain = &$store->domain->open ($me->name);
$domain['status'] = $formulary->data['status'];
} // update status
$formulary->errorMsg = 'system_msg_alertDataUpdated';
} // save
} // returned formulary

$formulary->data['name'] = $me->name;
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

// Context Remove
$document->mod->context->appendChild ('adminDomains_details_contextRemove')
->confirm ('adminDomains_details_contextRemoveConfirm', $remove);
} // function dispatch

static function action_remove ($document)
{ // function action_remove
global $io, $store;
$me = $document->application;
$id = $me->domainId;
$store->domain->delete ($id);
$io->database->delete ($store->domainContent, array ('domain_id' => $id));
$io->database->delete ($store->domainGroup, array ('domain_id' => $id));
$io->database->delete ($store->domainExtras, array ('domain_id' => $id));
$io->database->delete ($store->domainGroups, array ('domain_id' => $id));
foreach (scandir (PATH_DOMAINS . $me->name) as $filename)
{ // each domain file
if (is_file (PATH_DOMAINS . $me->name . '/' . $filename))
unlink (PATH_DOMAINS . $me->name . '/' . $filename);
} // each domain file
rmdir (PATH_DOMAINS . $me->name);

// returns to parent page
$parent = $me->parent;
$parent->reset ();
$document->application = $parent;
$document->application->dispatch ($document);
$document->reload = $document->url ();
} // function action_remove

} // class eclApp_adminDomains_details

?>