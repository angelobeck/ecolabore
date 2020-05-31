<?php

class eclFilter_personaliteExport_components
{ // class eclFilter_personaliteExport_components

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;
$me = $document->application;

$children = $store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0);

foreach ($children as $data)
{ // each child
$local = array (
'name' => $fieldName . '_' . $data['id'], 
'type' => 'checkbox', 
'caption' => $document->textMerge ($data['name'] . ' ', $data['text']['caption'])
);
$formulary->appendChild ($local);
} // each child

$formulary->appendChild ('system_form_separator');

$sections = $store->domainContent->mode ($me->domainId, MODE_SECTION);
array_unshift ($sections, $store->domainContent->open ($me->domainId, '-index'));

foreach ($sections as $data)
{ // each section

$local = array (
'name' => $fieldName . '_' . $data['id'], 
'type' => 'checkbox', 
'caption' => $document->textMerge ($data['name'] . ' ', $data['text']['caption'])
);
$formulary->appendChild ($local);
} // each section
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io, $store;
$document = $formulary->document;
$me = $formulary->document->application;
$received = $formulary->received;

$zip = new ZipArchive ();
$filename = PATH_DOMAINS . $document->domain->name . '/-export.zip';

if ($zip->open ($filename, ZIPARCHIVE::CREATE) !== TRUE)
return;

$children = $store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0);

foreach ($children as $data)
{ // each child template
if (!isset ($received[$fieldName . '_' . $data['id']]))
continue;

$component = [];
$component['name'] = $data['name'];
if ($data['text'])
$component['text'] = $data['text'];
if ($data['local'])
$component['local'] = $data['local'];
if (isset ($data['html'][0]))
$component['html'] = $data['html'];

if (count ($component) == 1)
continue;

$name = '-' . str_replace ('/', '+', $data['name']) . '.json';
$content = eclIo_webservice::array2json ($component);
$zip->addFromString ($name, $content);
} // each child template

$sections = $store->domainContent->mode ($me->domainId, MODE_SECTION);
array_unshift ($sections, $store->domainContent->open ($me->domainId, '-index'));
foreach ($sections as $data)
{ // each child section
if (!isset ($received[$fieldName . '_' . $data['id']]))
continue;

$component = [];
foreach (array ('name', 'marker', 'access', 'text', 'local', 'flags', 'extras', 'keywords') as $field)
{ // copy each field
if (isset ($data[$field]) and $data[$field])
$component[$field] = $data[$field];
} // copy each field

if ($data['parent_id'] == 1)
$component['parent_id'] = 1;
elseif ($data['parent_id'])
{ // find parent
$parent = $store->domainContent->openById ($me->domainId, $data['parent_id']);
if (!$parent)
continue;

$data['parent_name'] = $parent['name'];
} // find parent

$name = $data['name'] . '.json';
$content = eclIo_webservice::array2json ($component);
$zip->addFromString ($name, $content);

foreach ($store->domainFile->scanPrefixedFiles ($me->domainId, $data['name']) as $name)
{ // each file
$zip->addFromString ($name, file_get_contents (PATH_DOMAINS . $document->domain->name . '/' . $name));
} // each file
} // each child section

$zip->close ();

$headers = array (
'Content-Disposition' => 'attachment', 
'Cache-Control' => 'no-cache', 
'Filename' => $formulary->data['name']
);

$io->sendFile->send ($filename, $headers);
} // function save

} // class eclFilter_personaliteExport_components

?>