<?php

class eclApp_sectionTeam
{ // class eclApp_sectionTeam

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('sectionTeam_group', 'sectionTeam_group_new', 'sectionTeam_member', 'sectionTeam_member_import', 'sectionTeam_member_inactive', 'sectionTeam_member_new');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->access (4))
$document->mod->editor->enable ();

$document->mod->panel->main = array ('content', 'listmembers', 'listgroups');
$document->mod->listmembers = new eclMod_sectionTeam_listAllMembers ($document);
$document->mod->listgroups = new eclMod_sectionTeam_listAllGroups ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionTeam

?>