<?php

class eclFilter_personaliteInstall_selectComponents
{ // class eclFilter_personaliteInstall_selectComponents

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;
$me = $document->application;

$templates = [];
$sections = [];
$all = [];

$filename = PATH_DOMAINS . $document->domain->name . '/-install.zip';
$zip = zip_open ($filename);

if (!$zip)
return;

while ($zip_entry = zip_read ($zip))
{ // each file
$name = zip_entry_name ($zip_entry);
if (substr ($name,  - 5) != '.json')
{ // cancel
zip_entry_close ($zip_entry);
continue;
} // cancel
$buffer = zip_entry_read ($zip_entry, zip_entry_filesize ($zip_entry));
$data = eclIo_webservice::json2array ($buffer);

if (!isset ($data['name']))
{ // cancel
zip_entry_close ($zip_entry);
continue;
} // cancel

$all[$data['name']] = $data;

if (strpos ($data['name'], '/') === false)
{ // section
if ($store->domainContent->open ($me->domainId, $data['name']))
$sections[$data['name']] = 0;
elseif (isset ($data['marker']) and $store->domainContent->findMarker ($me->domainId, $data['marker']))
$sections[$data['name']] = 0;
else
$sections[$data['name']] = 1;
} // section
else
$templates[$data['name']] = 1;
zip_entry_close ($zip_entry);
} // each entry
zip_close ($zip);

if ($templates)
{ // create components checkboxes
foreach ($templates as $name => $value)
{ // loop each component
$data = $all[$name];
$local = array (
'type' => 'checkbox', 
'name' => $fieldName . '_' . md5 ($data['name']), 
'value' => $value, 
'caption' => $document->textMerge ($data['name'] . ' ', $data['text']['caption'])
);
$formulary->appendChild ($local);
} // loop each component
} // create components checkboxes

$formulary->appendChild (array ('type' => 'separator'));

if ($sections)
{ // create sections checkboxes
foreach ($sections as $name => $value)
{ // loop each section
$data = $all[$name];
$local = array (
'type' => 'checkbox', 
'name' => $fieldName . '_' . md5 ($data['name']), 
'value' => $value, 
'caption' => $data['text']['caption']
);
$formulary->appendChild ($local);
} // loop each section
} // create section checkboxes
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$document = $formulary->document;
$me = $formulary->document->application;
$received = $formulary->received;
$names = [];
$saved = [];

$filename = PATH_DOMAINS . $document->domain->name . '/-install.zip';
$zip = zip_open ($filename);

if (!$zip)
return;

while ($zip_entry = zip_read ($zip))
{ // each file
$name = zip_entry_name ($zip_entry);
if (substr ($name,  - 5) == '.json')
{ // read json file
$buffer = zip_entry_read ($zip_entry, zip_entry_filesize ($zip_entry));
$data = eclIo_webservice::json2array ($buffer);
if (isset ($data['name']) and isset ($received[$fieldName . '_' . md5 ($data['name'])][0]))
{ // save file
$names[$data['name']] = true;
$saved[] = $data['name'];

if (strpos ($data['name'], '/'))
{ // save template component
$component = &$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, $name);
if ($component)
{ // components exists
$component = array_replace ($component, $data);
unset ($component);
} // components exists
else
{ // create component
$data['mode'] = MODE_TEMPLATE;
$store->domainExtras->insert ($me->domainId, $data);
} // create component
} // save template component
else
{ // save section
if (isset ($data['marker']) and $id = $store->domainContent->findMarker ($me->domainId, $data['marker']))
{ // marker found
$section = $me->findChild ($id);
if ($section)
$section->remove ();
} // marker found
if ($sectionData = $store->domainContent->open ($me->domainId, $data['name']))
{ // same name
$section = $me->findChild ($sectionData['id']);
$section->remove ();
} // same name
$data['mode'] = MODE_SECTION;
$store->domainContent->insert ($me->domainId, $data);
} // save section
} // save file
} // read json file
zip_entry_close ($zip_entry);
} // each entry

zip_close ($zip);

// second turn to save extra files
$zip = zip_open ($filename);

while ($zip_entry = zip_read ($zip))
{ // each file
$name = zip_entry_name ($zip_entry);
list ($prefix) = explode ('.', $name);
list ($prefix) = explode (CHR_FNS, $prefix);
if (isset ($names[$prefix]))
{ // save extras file
$saved[] = $name;
$buffer = zip_entry_read ($zip_entry, zip_entry_filesize ($zip_entry));
file_put_contents (PATH_DOMAINS . $document->domain->name . '/' . $name, $buffer);
} // save extras file
zip_entry_close ($zip_entry);
} // each entry

zip_close ($zip);
$formulary->data['saved'] = implode (CRLF, $saved);
} // function save

} // class eclFilter_personaliteInstall_selectComponents

?>