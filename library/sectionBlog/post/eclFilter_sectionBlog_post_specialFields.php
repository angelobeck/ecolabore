<?php

class eclFilter_sectionBlog_post_specialFields
{ // class eclFilter_sectionBlog_post_specialFields

static function create ($fieldName, $control, $formulary)
{ // function create
$formulary->insertControlChildren (self::findSpecialFields ($formulary));
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$formulary->insertControlChildren (self::findSpecialFields ($formulary));
} // function save

static function findSpecialFields ($formulary)
{ // function findSpecialFields
global $store;
$render = $formulary->document->render;

$fields['title'] = true;

foreach (array ('list', 'post') as $from)
{ // loop post and list
$details = self::getDetails ($formulary->document, $from);
$details = str_replace (CRLF, ' ', $details);
foreach (explode (' ', $details) as $name)
{ // each detail
$detail = $render->block ('details/' . $name);
if (!isset ($detail['local']['filters']))
continue;
foreach (explode (' ', str_replace (CRLF, ' ', $detail['local']['filters'])) as $filter)
{ // each filter
$fields[$filter] = true;
} // each filter
} // each detail
} // loop post and list

unset ($fields['title'], $fields['caption'], $fields['name'], $fields['specialFields']);

$control = [];
foreach ($fields as $field => $notImportant)
{ // each field
$control['children'][] = 'post' . ucfirst ($field) . '_edit';
} // each field
return $control;
} // function findSpecialFields

static function getDetails ($document, $from)
{ // function getDetails
$render = $document->render;
$me = $document->application->parent;

if (isset ($me->data['extras'][$from]['local']['details']))
return $me->data['extras'][$from]['local']['details'];

if (isset ($me->data['flags']['modList_preset']))
$preset = $me->data['flags']['modList_preset'];
else
$preset = 'blog';

$post = $render->block ('modules/' . $from . '_' . $preset);
if (!$post)
$post = $render->block ('modules/' . $from . '_blog');

if (isset ($post['local']['details']))
return $post['local']['details'];

return [];
} // function getDetails

} // class eclFilter_sectionBlog_post_specialFields

?>