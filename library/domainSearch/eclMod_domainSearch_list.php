<?php

class eclMod_domainSearch_list
{ // class eclMod_domainSearch_list

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

$keywords = '';
if (isset ($document->actions['tag'][1]))
{ // search tag
$search = $document->actions['tag'];
array_shift ($search);
$keywords = implode (' ', $search);
$where['keywords'] = ':' . implode (': :', $search) . ':';
} // search tag
elseif (isset ($document->actions['search'][1]))
{ // search keywords
$search = $document->actions['search'];
array_shift ($search);
$keywords = implode (' ', $search);
$where['keywords'] = implode (' ', $search);
} // search keywords
else
return;

if (!isset ($where['keywords'][0]))
return;

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

$mod->data = $document->render->block ('modules/list');

if (!$row->children)
{ // no results
$local['keywords'] = $keywords;
$row->appendChild ('domainSearch_msgNoResults', $local);
} // no results

$mod->data = $document->render->block ('modules/list_search_results');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'list_search_results');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modList_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_domainSearch_list

?>