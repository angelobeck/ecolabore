<?php

class eclApp_sectionTeam_member
{ // class eclApp_sectionTeam_member

static function is_child ($me, $name)
{ // function is_child
global $store;
if (substr ($name, 0, 6) != '-user-')
$name = '-user-' . $name;

$data = $store->domainContent->open ($me->domainId, $name);
if ($data and $data['mode'] == MODE_SUBSCRIPTION and $data['marker'] == $me->data['marker'])
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
$names = [];

$where = array (
'domain_id' => $me->domainId, 
'mode' => MODE_SUBSCRIPTION, 
'marker' => $me->data['marker']
);
foreach ($store->domainContent->search ($where) as $data)
{ // each group
$names[] = $data['name'];
} // each group

asort ($names);
return $names;
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
switch ($me->parent->data['marker'])
{ // switch marker
case 30:
$me->data = $store->control->read ('sectionTeam_member_contentMember');
break;

case 31:
$me->data = $store->control->read ('sectionTeam_member_contentPartner');
break;

case 32:
$me->data = $store->control->read ('sectionTeam_member_contentStudant');
break;

case 33:
$me->data = $store->control->read ('sectionTeam_member_contentSubscription');
break;

case 34:
$me->data = $store->control->read ('sectionTeam_member_contentSuplier');
break;

case 35:
$me->data = $store->control->read ('sectionTeam_member_contentClient');
break;
} // switch marker
$me->access = 2;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

if (substr ($me->name, 0, 6) != '-user-')
$data = &$store->domainContent->open ($me->domainId, '-user-' . $me->name);
else
$data = &$store->domainContent->open ($me->domainId, $me->name);

if ($document->access (4))
{ // admin access
$formulary = $document->createFormulary ('sectionTeam_member_edit', $data);

if ($formulary->command ('remove') or $document->actions ('member', 'unlink'))
{ // remove
self::action_unlink ($document, $data);
return;
} // remove

if ($data['marker'] <= 245)
$document->mod->context->appendChild ('sectionTeam_member_unlink')
->url (true, true, '_member-unlink');

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
$document->dataReplace ('layouts/dialog_close');
return;
} // save
} // admin access
else
$formulary = $document->createFormulary ('sectionTeam_member_view', $data);

if ($formulary->command ('cancel') or $formulary->command ('close'))
{ // close
$document->dataReplace ('layouts/dialog_cancel');
return;
} // close

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->formulary = $formulary;
} // function dispatch

static function action_unlink ($document, &$data)
{ // function action_unlink
if ($data['marker'] == 0)
$document->mod->humperstilshen->alert ('sectionTeam_member_unlinkMember');
else
$document->mod->humperstilshen->alert ('sectionTeam_member_unlinkMessage');

$data['parent_id'] = 0;
$data['master_id'] = 0;
$data['marker'] = 246;
$data['updated'] = TIME;

$formulary = $document->createFormulary ('sectionTeam_member_view', $data, 'view');
$document->mod->formulary = $formulary;
} // function action_unlink

} // class eclApp_sectionTeam_member

?>