<?php

class eclMod_sectionTeam_listAllGroups
{ // class eclMod_sectionTeam_listAllGroups

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

$mod->data = array_replace_recursive (
$store->control->read ('modules/list_card'), 
$store->control->read ('sectionTeam_listAllGroups')
);

$row = $mod->appendChild ();
$sectors = $store->domainContent->children ($me->domainId, MODE_GROUP, 0);
foreach ($sectors as $sector)
{ // each sector
if ($sector['marker'] != $marker)
continue;

$pathway = $me->pathway;
$pathway[] = $sector['name'];
$row->appendChild ($sector)
->url ($pathway);
} // each sector

if ($document->access (4))
{ // admin access
if ($row->children)
$row = $mod->appendChild ();
$pathway = $me->pathway;
$pathway[] = '-new-group';
$row->appendChild ('sectionTeam_group_new')
->url ($pathway);
} // admin access

if ($row->children)
$mod->enabled = true;
} // function setModule

} // class eclMod_sectionTeam_listAllGroups

?>