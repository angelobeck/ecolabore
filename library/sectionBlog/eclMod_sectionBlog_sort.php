<?php

class eclMod_sectionBlog_sort
{ // class eclMod_sectionBlog_sort

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

// Procure o módulo dentro da aplicação
if (isset ($me->data['extras']['sort']))
$mod->data = $me->data['extras']['sort'];
else
{ // from preset
if (isset ($me->data['flags']['modList_preset']))
$preset = $me->data['flags']['modList_preset'];
else
$preset = 'blog';

$mod->data = $render->block ('modules/sort_' . $preset);
if (!$mod->data)
$mod->data = $render->block ('modules/sort_blog');
} // from preset

$mod->data['name'] = 'sort_' . $me->name;

// configurações de listagem
$local = isset ($me->data['extras']['list']['local']) ? $me->data['extras']['list']['local'] : [];
$sort = isset ($local['list_sort']) ? $local['list_sort'] : 'index';
$direction = isset ($local['list_direction']) ? $local['list_direction'] : 'asc';

if (isset ($document->actions['sort'][1]))
$value = $document->actions['sort'][1];
elseif ($sort == 'value' and $direction == 'asc')
$value = 'cheaper';
elseif ($sort == 'value' and $direction == 'desc')
$value = 'expensive';
elseif ($sort == 'hits')
$value = 'hits';
elseif ($sort == 'spotlight')
$value = 'ratings';
elseif ($sort == 'created' and $direction == 'desc')
$value = 'recents';
elseif ($sort == 'created' and $direction == 'asc')
$value = 'post';
elseif ($sort == 'event_start')
$value = 'event';
elseif ($sort == 'name')
$value = 'alphabetically';
else
$value = '';

foreach (array ('cheaper', 'expensive', 'hits', 'ratings', 'recents', 'post', 'event', 'alphabetically') as $mode)
{ // each mode
$mod->appendChild ('sectionBlog_sort' . ucfirst ($mode))
->set ('value', $document->url (true, true, '_sort-' . $mode))
->active ($value == $mode);
} // each mode

if ($document->templateEditable and $document->access (4))
{ // personalite reference
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'sort');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modSort_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionBlog_sort

?>