<?php

class eclScope_categories
{ // class eclScope_categories

static function get ($render, $arguments)
{ // function get
global $store;

if (!isset ($render->data['domain_id']) or !isset ($render->data['name']))
return false;

$document = $render->document;
$domainId = $render->data['domain_id'];
$name = $render->data['name'];
$data = [];
$sections = $store->domainContent->mode ($domainId, MODE_SECTION);

foreach ($sections as $section)
{ // each section
if (!isset ($section['flags']['section_type']) or $section['flags']['section_type'] != 'categories')
continue;

$categories = $store->domainContent->children ($document->domain->domainId, MODE_CATEGORY, $section['id']);
if (!$categories)
continue;

$group = $document->createListItem ($section);
foreach ($categories as $category)
{ // each category
if (!isset ($category['links']['pages']) or !in_array ($name, $category['links']['pages']))
continue;

$group->appendChild ($category)
->swapTitle ()
->url ($store->domainContent->pathway ($domainId, $category['id']));
} // each category
if ($group->children)
$data['children'][] = $group;
} // each section
return $data;
} // function get

} // class eclScope_categories

?>