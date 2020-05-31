<?php

class eclMod_sectionBlog_post_pages
{ // class eclMod_sectionBlog_post_pages

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application->parent;
$row = $mod->appendChild ();

if (isset ($me->data['extras']['list']))
$list = $me->data['extras']['list'];
elseif (isset ($me->data['flags']['modList_preset']))
$list = $document->render->block ('modules/list_' . $me->data['flags']['modList_preset']);
else
$list = $document->render->block ('modules/list_blog');

$sort = isset ($list['local']['list_sort']) ? $list['local']['list_sort'] : 'index';
$direction = isset ($list['local']['list_direction']) ? $list['local']['list_direction'] : 'asc';

switch ($sort)
{ // switch list sort
case 'name':
case 'created':
case 'updated':
case 'event_start':
case 'coments_last_update':
case 'hits':
case 'value':
case 'spotlight':
$names = $store->domainContent->childrenNames ($me->domainId, MODE_POST, $me->id, 4, 0, 0, $sort, $direction);
break;

default:
$names = $store->domainContent->childrenNames ($me->domainId, MODE_POST, $me->id, 4, 0, 0, 'index', $direction);
} // switch list sort

$index = array_search ($document->application->name, $names);

if ($index === false)
return;

$length = count ($names);
if ($length < 2)
return;

if ($index)
{ // previous
$pathway = $me->pathway;
$pathway[] = $names[$index - 1];
$row->appendChild ('labels/layout/previous')
->url ($pathway);
} // previous

if ($index < $length - 1)
{ // next
$pathway = $me->pathway;
$pathway[] = $names[$index + 1];
$row->appendChild ('labels/layout/next')
->url ($pathway);
} // next

if (isset ($me->data['extras']['post_pages']))
$mod->data = $me->data['extras']['post_pages'];
else
$mod->data = $document->render->block ('modules/pages_post');

if ($document->templateEditable and $document->access (4))
{ // personalite reference
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'post', 'pages');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modPages_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionBlog_post_pages

?>