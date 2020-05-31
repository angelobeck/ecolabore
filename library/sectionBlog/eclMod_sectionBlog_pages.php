<?php

class eclMod_sectionBlog_pages
{ // class eclMod_sectionBlog_pages

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

isset ($me->data['extras']['list']['local']) ? $local = $me->data['extras']['list']['local'] : $local = [];

isset ($local['list_sort']) or $local['list_sort'] = 'index';
isset ($local['list_direction']) or $local['list_direction'] = 'asc';
isset ($local['list_max']) or $local['list_max'] = 0;

$action = '';
if (isset ($document->actions['sort'][1]))
{ // force sorting mode
$action = '_sort-' . $document->actions['sort'][1];
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

default:
$action = '';
} // switch sorting mode
} // force sorting mode

if (!$local['list_max'])
return;

if (isset ($document->actions['page'][1]))
$page = intval ($document->actions['page'][1]);
else
$page = 1;

if (!$page)
$page = 1;

$names = $store->domainContent->childrenNames ($me->domainId, MODE_POST, $me->id, true, 0, 0, $local['list_sort'], $local['list_direction']);
$length = count ($names);

if ($length <= $local['list_max'])
return;

$numPages = ceil ($length / $local['list_max']);

$row = $mod->appendChild ();

// First
$row->appendChild ('labels/navigation/first')
->active ($page == 1)
->url (true, true, $action);

for ($i = 1; $i <= $numPages; $i++)
{ // loop pages
$caption['local']['caption'] = $document->textMerge (strval ($i));
$row->appendChild (false, $caption)
->active ($page == $i)
->url (true, true, $action . '_page-' . $i);
} // loop pages

// last
$row->appendChild ('labels/navigation/last')
->active ($page == $numPages)
->url (true, true, $action . '_page-' . $numPages);

if (isset ($me->data['extras']['pages']))
$mod->data = $me->data['extras']['pages'];
else
$mod->data = $document->render->block ('modules/pages');

if ($document->templateEditable and $document->access (4))
{ // personalite reference
$pathway = array ($document->domain->name, '-dialog', 'section_pages_pages_' . $me->name);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modPages_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionBlog_pages

?>