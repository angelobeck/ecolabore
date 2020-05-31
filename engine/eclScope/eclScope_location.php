<?php

class eclScope_location
{ // class eclScope_location

static function get ($render, $arguments)
{ // function get
global $store, $system;

if (!isset ($render->data['domain_id']) or !isset ($render->data['name']))
return false;

$document = $render->document;
$domainId = $render->data['domain_id'];
$domainName = $store->domain->getName ($domainId);
$name = $render->data['name'];
$domain = $system->child ($domainName);
$me = $domain->findChild ($name);
if ($me == false)
return false;

$levels = [];
$levels[] = $me;
while (!$me->parent->isDomain)
{ // loop levels
$me = $me->parent;
$levels[] = $me;
} // loop levels

$data['children'] = [];
$length = count ($levels);
for ($i = $length - 1; $i > 0; $i--)
{ // each level
$data['children'][] = $document->createListItem ($levels[$i]->data)
->url ($levels[$i]->pathway);
} // each level

if (!count ($data['children']))
$data['children'][] = $document->createListItem ($levels[0]->data)
->url ($levels[0]->pathway);

return $data;
} // function get

} // class eclScope_location

?>