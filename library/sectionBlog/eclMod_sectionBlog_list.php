<?php

class eclMod_sectionBlog_list
{ // class eclMod_sectionBlog_list

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

// Procure o módulo dentro da aplicação
if (isset ($me->data['extras']['list']))
$mod->data = $me->data['extras']['list'];
else
{ // from preset
if (isset ($me->data['flags']['modList_preset']))
$preset = $me->data['flags']['modList_preset'];
else
$preset = 'blog';

$mod->data = $render->block ('modules/list_' . $preset);
if (!$mod->data)
$mod->data = $render->block ('modules/list_blog');
} // from preset

$mod->data['name'] = 'section_' . $me->name;

// configurações de listagem
$local = $mod->data['local'];
isset ($local['list_sort']) or $local['list_sort'] = 'index';
isset ($local['list_direction']) or $local['list_direction'] = 'asc';
isset ($local['list_max']) or $local['list_max'] = 0;

if (isset ($document->actions['sort'][1]))
{ // force sorting mode
switch ($document->actions['sort'][1])
{ // switch sorgting mode
case 'cheaper':
$local['list_sort'] = 'value';
$local['list_direction'] = 'asc';
break;

case 'expensive':
$local['list_sort'] = 'value';
$local['list_direction'] = 'desc';
break;

case 'hits':
$local['list_sort'] = 'hits';
$local['list_direction'] = 'desc';
break;

case 'ratings':
$local['list_sort'] = 'spotlight';
$local['list_direction'] = 'desc';
break;

case 'recents':
$local['list_sort'] = 'created';
$local['list_direction'] = 'desc';
break;

case 'post':
$local['list_sort'] = 'created';
$local['list_direction'] = 'asc';
break;

case 'event':
$local['list_sort'] = 'event_start';
$local['list_direction'] = 'asc';
break;

case 'alphabetically':
$local['list_sort'] = 'name';
$local['list_direction'] = 'asc';
break;
} // switch sorting mode
} // force sorting mode

if ($local['list_max'] and isset ($document->actions['page'][1]))
{ // pagination
$page = intval ($document->actions['page'][1]);
if ($page <= 0)
$page = 1;
$offset = $local['list_max'] * intval ($document->actions['page'][1] - 1);
} // pagination
else
$offset = 0;

switch ($local['list_sort'])
{ // switch list sort
case 'name':
case 'created':
case 'updated':
case 'event_start':
case 'coments_last_update':
case 'hits':
case 'value':
case 'spotlight':
$children = $store->domainContent->children ($me->domainId, MODE_POST, $me->id, 4, $local['list_max'], $offset, $local['list_sort'], $local['list_direction']);
break;

default:
$children = $store->domainContent->children ($me->domainId, MODE_POST, $me->id, 4, $local['list_max'], $offset, 'index', $local['list_direction']);
} // switch list sort

$row = $mod->appendChild ();
foreach ($children as $data)
{ // each child
$row->appendChild ($me->child ($data['name']))
->set ('editable', $document->contentEditable)
->url ();
} // each child

if (!$row->children)
return;

if ($document->templateEditable and $document->access (4))
{ // personalite reference
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'list');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modList_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionBlog_list

?>