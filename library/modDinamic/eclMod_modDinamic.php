<?php

class eclMod_modDinamic
{ // class eclMod_modDinamic

public $document, $name;

public function __construct ($document, $name=false)
{ // function __construct
$this->document = $document;
$this->name = $name;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store, $system;
$document = $this->document;
$render = $document->render;

// Identifique o nome da seção e o número do painel
if (is_string ($this->name))
$arguments = array ($this->name, '0');

// Procure a aplicação
$me = $document->application->findModuleTarget ($document, $arguments);
if (!$me)
return;

$number = $arguments[1];

// Procure o módulo dentro da aplicação
if (isset ($me->data['extras']['dinamic_' . $number]))
$mod->data = $me->data['extras']['dinamic_' . $number];
else
{ // from preset
if (isset ($me->data['flags']['modList_preset']))
$preset = $me->data['flags']['modList_preset'];
else
$preset = 'blog';

$mod->data = $render->block ('modules/dinamic_' . $preset);
if (!$mod->data)
$mod->data = $render->block ('modules/dinamic_blog');
} // from preset

if ($document->access (4))
{ // personalite
$mod->enabled = true;

if (!$mod->data)
$mod->data = $store->control->read ('modDinamic_create');

$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'dinamic_' . $number);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modDinamic_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // personalite

// configurações de listagem
if (isset ($mod->data['local']))
$local = $mod->data['local'];
else
$local = [];
isset ($local['list_sort']) or $local['list_sort'] = 'index';
isset ($local['list_direction']) or $local['list_direction'] = 'asc';
isset ($local['list_max']) or $local['list_max'] = 5;
isset ($local['list_update']) or $local['list_update'] = 'auto';
isset ($local['list_offset']) or $local['list_offset'] = 0;
isset ($local['list_filter']) or $local['list_filter'] = '';

// Extract items
switch ($me->data['mode'])
{ // switch mode
case MODE_SECTION:
switch ($me->data['flags']['section_type'])
{ // switch section type
case 'folder':
$children = $store->domainContent->children ($me->domainId, MODE_SECTION, $me->id);
break;
case 'blog':
$children = $store->domainContent->children ($me->domainId, MODE_POST, $me->id);
} // switch section type
break;

case MODE_DOMAIN:
if ($me->data['name'] == '-index')
$children = $store->domainContent->children ($me->domainId, MODE_SECTION, 0);
elseif ($me->data['name'] == '-recents' and isset ($me->data['links']['pages']))
{ // recents
foreach ($me->data['links']['pages'] as $name)
{ // each page
$children[] = $store->domainContent->open ($me->domainId, $name);
} // each page
} // recents
break;

case MODE_CATEGORY:
if (isset ($me->data['links']['pages']))
{ // category pages
foreach ($me->data['links']['pages'] as $name)
{ // each page
$children[] = $store->domainContent->open ($me->domainId, $name);
} // each page
} // category pages
} // switch mode

if (!isset ($children) or !$children)
return;

$sort = $local['list_sort'];
$filter = $local['list_filter'];

switch ($sort)
{ // switch list sort
case 'name':
case 'index':
case 'created':
case 'updated':
case 'event_start':
case 'coments_last_update':
case 'hits':
case 'value':
case 'spotlight':
$sorted = [];
foreach ($children as $data)
{ // each child
if (!$data)
continue;

if ($filter == 'event_start' and $data['event_start'] < TIME)
continue;
elseif ($filter == 'spotlight' and !$data['spotlight'])
continue;

if (isset ($data[$sort]))
$sorted[$data[$sort]][] = $data;
else
$sorted['zzzzzzzzz'][] = $data;
} // each child

if ($local['list_direction'] == 'asc')
ksort ($sorted);
else
krsort ($sorted);

$children = [];
foreach ($sorted as $equal)
{ // each value
foreach ($equal as $data)
{ // each equal
$children[] = $data;
} // each equal
} // each value
break;

case 'shuffle':
shuffle ($children);
break;
} // switch list sort

switch ($local['list_update'])
{ // switch update mode
case 'auto':
$offset = 0;
break;

case 'hits':
$offset = mt_rand (0, count ($children));
break;

case 'hour':
$offset = (24 * intval (date ('z'))) + intval (date ('G'));
break;

case 'day':
$offset = intval (date ('z'));
break;

case 'week':
$offset = intval (date ('W'));
break;

case 'month':
$offset = intval (date ('n'));
break;

default:
$offset = 0;
} // switch update mode

$total = count ($children);
if (!$total)
return;

if ($local['list_max'] == 0 or $local['list_max'] > $total)
$max = $total;
else
$max = $local['list_max'];

$offset += $local['list_offset'];
$offset = $offset % $total;

// Itens da lista
$row = $mod->appendChild ();
for ($i = 0; $i < $max; $i++)
{ // each child
$data = $children[$offset];
$post = $me->findChild ($data['name']);
if ($post and $document->access ($post->access, $post->groups))
{ // access ok
$row->appendChild ($post)
->set ('editable', $document->contentEditable and $document->access (4, $post->groups))
->url ($post->pathway);
} // access ok

$offset++;
if ($offset >= $total)
$offset = 0;
} // each child

if ($row->children)
$mod->enabled = true;
} // function setModule

} // class eclMod_modDinamic

?>