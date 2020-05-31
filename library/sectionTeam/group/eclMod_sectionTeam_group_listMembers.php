<?php

class eclMod_sectionTeam_group_listMembers
{ // class eclMod_sectionTeam_group_listMembers

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
$marker = $me->data['marker'];

if ($marker == 30)
$mod->data = $store->control->read ('sectionTeam_listAllMembers');
elseif ($marker == 31)
$mod->data = $store->control->read ('sectionTeam_listAllPartners');
elseif ($marker == 32)
$mod->data = $store->control->read ('sectionTeam_listAllStudents');
elseif ($marker == 33)
$mod->data = $store->control->read ('sectionTeam_listAllSubscriptions');
elseif ($marker == 34)
$mod->data = $store->control->read ('sectionTeam_listAllSuppliers');
elseif ($marker == 35)
$mod->data = $store->control->read ('sectionTeam_listAllClients');

$mod->data = array_replace_recursive ($store->control->read ('modules/list_card'), $mod->data);

$members = $store->domainContent->children ($me->domainId, MODE_SUBSCRIPTION, $me->id);;
$row = $mod->appendChild ();

foreach ($members as $member)
{ // each member
if ($member['access'] > 4)
{ // inactive
$inactive = true;
continue;
} // inactive

$pathway = $me->parent->pathway;
$pathway[] = $member['name'];
$row->appendChild ($member)
->swapTitle ()
->url ($pathway)
->popUpOpen ();
} // each member

if ($document->access (4))
{ // add new member
if ($row->children)
$row = $mod->appendChild ();

// inactive
if (isset ($inactive))
{ // inactive member
$pathway = $me->parent->pathway;
$pathway[] = '-inactive-member';
$row->appendChild ('sectionTeam_member_inactive')
->url ($pathway)
->popUpOpen ();
} // inactive member

// new
$pathway = $me->parent->pathway;
$pathway[] = '-new-member';
$row->appendChild ('sectionTeam_member_new')
->url ($pathway)
->popUpOpen ();

// import existing profile
$pathway = $me->parent->pathway;
$pathway[] = '-import-profile';
$row->appendChild ('sectionTeam_member_import')
->url ($pathway)
->popUpOpen ();
} // add new member

if ($row->children)
$mod->enabled = true;
} // function setModule

} // class eclMod_sectionTeam_group_listMembers

?>