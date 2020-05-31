<?php

class eclMod_domainGlossary_keyword_list
{ // class eclMod_domainGlossary_keyword_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $io, $store;
$document = $this->document;
$me = $document->application;
$row = $mod->appendChild ();

$where['domain_id'] = $me->domainId;
$where['keywords'] = ':' . $me->name . ':';

$results = $store->domainContent->search ($where);

foreach ($results as $data)
{ // each result
$post = $me->findChild ($data['name']);
if (!$post)
continue;

if (!$document->access ($post->access, $post->groups))
continue;

$row->appendChild ($post->data)
->url ($post->pathway);
} // each result

if (!$row->children)
return;

$mod->data = $document->render->block ('modules/list_related');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'list_related');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modList_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_domainGlossary_keyword_list

?>