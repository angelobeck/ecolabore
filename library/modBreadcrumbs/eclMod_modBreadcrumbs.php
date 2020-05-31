<?php

class eclMod_modBreadcrumbs
{ // class eclMod_modBreadcrumbs

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;
$me = $document->application;

if ($document->printableLayout)
return;

if ($document->domain->domainId and !isset ($document->data['id']))
return;

if (count ($document->pathway) == 1)
return;

$list[] = $me;
while (!$me->parent->isDomain)
{ // parent is not a domain
$me = $me->parent;
$list[] = $me;
} // parent is not a domain

$length = count ($list);
if (!$length)
return;

$row = $mod->appendChild ();

// The index page
$row->appendChild ($document->domain->child ('')->data)
->url ($document->domain->pathway);

for ($i = $length - 1; $i >= 0; $i--)
{ // loop each breadcrumb
$row->appendChild ($list[$i]->data)
->url ($list[$i]->pathway)
->active ($i == 0);
} // loop each breadcrumb

// Configurações
$mod->data = $render->block ('modules/breadcrumbs');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'breadcrumbs');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modBreadcrumbs_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modBreadcrumbs

?>