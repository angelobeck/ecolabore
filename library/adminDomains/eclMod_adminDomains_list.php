<?php

class eclMod_adminDomains_list
{ // class eclMod_adminDomains_list

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

// Domain add
$domain_pathway = $pathway;
$domain_pathway[] = '-new-domain';
$row->appendChild ('adminDomains_add_content')
->virtual ()
->url ($domain_pathway);
} // access
else
$pathway = [];

if (is_dir (PATH_DOMAINS))
$names = $store->domain->childrenNames ();
else
$names = [];

foreach ($names as $name)
{ // each name
$domainId = $store->domain->getId ($name);
$data = $store->domainContent->open ($domainId, '-register');
if (!isset ($data['text']['title']))
$data = $store->domainContent->open ($domainId, '-index');
if (isset ($data['text']['title']))
$caption = $data['text']['title'];
else
$caption = $document->textMerge ('! ' . $name);
$domain_pathway = $pathway;
$domain_pathway[] = $name;
$row->appendChild (false, array ('title' => $caption))
->url ($domain_pathway)
->swapTitle ();
} // each name

$mod->data = $store->control->read ('modules/list');
$mod->enabled = true;
} // function setModule

} // class eclMod_adminDomains_list

?>