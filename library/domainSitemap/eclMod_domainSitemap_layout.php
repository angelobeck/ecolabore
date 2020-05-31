<?php

class eclMod_domainSitemap_layout
{ // class eclMod_domainSitemap_layout

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$domain = $document->domain;
$mod->enabled = true;
$mod->data = $store->control->read ('domainSitemap_layout');

foreach ($domain->children () as $child)
{ // each child
$this->indexPath ($child, $mod);
} // each child

$domain->reset ();

$names = $store->domainContent->childrenNames ($domain->domainId, MODE_SECTION, 1);
foreach ($names as $name)
{ // each child
$child = $domain->child ($name);
if ($child)
$this->indexPath ($child, $mod);
} // each child
} // function setModule

private function indexPath ($me, $mod)
{ // function indexPath
if ($me->access)
return;

if ($me->menuType != 'section' and $me->menuType != 'post')
return;

$mode = isset ($me->data['mode']) ? $me->data['mode'] : 0;
switch ($mode)
{ // switch mode
case MODE_SECTION:
$priority = '0.4';
break;

case MODE_POST:
$priority = '0.9';
break;

case MODE_DOMAIN:
if ($me->data['name'] == '-index')
$priority = '1';
else
$priority = '0';
break;

default:
$priority = '0.6';
} // switch mode

if (isset ($me->data['updated']) and intval ($me->data['updated']))
$updated = date ('c', intval ($me->data['updated']));
elseif (defined ('SYSTEM_PACKED_DATE'))
$updated = date ('c', intval (SYSTEM_PACKED_DATE));
else
$updated = date ('c', TIME);

$mod->appendChild (array (
'url' => $this->document->url ($me->pathway), 
'date' => $updated, 
'priority' => $priority
));
foreach ($me->children () as $child)
{ // each child
$this->indexPath ($child, $mod);
} // each child
} // function indexPath

} // class eclMod_domainSitemap_layout

?>