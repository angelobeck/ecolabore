<?php

class eclFilter_adminTools_components_selectVersion
{ // class eclFilter_adminTools_components_selectVersion

static function create ($fieldName, $control, $formulary)
{ // function create
global $io;
$components = $io->systemConstants->components;
$document = $formulary->document;

foreach (scandir (PATH_COMPONENTS) as $component)
{ // each folder
if ($component[0] == '.')
continue;

if (!is_dir (PATH_COMPONENTS . $component))
continue;

$local = [];
$local['text']['caption'] = $document->textMerge ($component);
$local['name'] = $fieldName . '_' . $component;
$local['type'] = 'select';

$select = $formulary->appendChild ($local);

$select->appendChild ('adminTools_components_editComponentDisabled')
->active (!isset ($components[$component]) or !is_dir (PATH_COMPONENTS . $component . '/' . $components[$component]));

foreach (scandir (PATH_COMPONENTS . $component) as $version)
{ // each version
if ($version[0] == '.')
continue;

if (!is_dir (PATH_COMPONENTS . $component . '/' . $version))
continue;

$data['value'] = $version;
$data['caption'] = $document->textMerge ($version);
$select->appendChild ($data)
->active (isset ($components[$component]) and $version == $components[$component]);
} // each version
} // each folder
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io;
$received = $formulary->received;

$components = [];
$aliases = [];
$map = [];
foreach (scandir (PATH_COMPONENTS) as $component)
{ // each component

if ($component[0] == '.')
continue;

if (!is_dir (PATH_COMPONENTS . $component))
continue;

if (!isset ($received[$fieldName . '_' . $component][0]))
continue;

if (!is_dir (PATH_COMPONENTS . $component . '/' . $received[$fieldName . '_' . $component]))
continue;

$components[$component] = $received[$fieldName . '_' . $component];

foreach (scandir (PATH_COMPONENTS . $component . '/' . $components[$component]) as $folder)
{ // aliase each folder
if ($folder[0] == '.')
continue;

if ($folder == 'shared' or $folder == 'templates')
continue;

if (!is_dir (PATH_COMPONENTS . $component . '/' . $components[$component] . '/' . $folder))
continue;

$aliases[$folder] = $component;
} // aliase each folder

if (!is_file (PATH_COMPONENTS . $component . '/' . $components[$component] . '/about.ecl.php'))
continue;
$data = $io->file->open (PATH_COMPONENTS . $component . '/' . $components[$component] . '/about.ecl.php');
if (!isset ($data['map']))
continue;
foreach ($data['map'] as $module => $list)
{ // map each module
foreach ($list as $item)
{ // map each item
if (!isset ($map[$module]))
$map[$module] = [];
$map[$module][] = $item;
} // map each item
} // map each module
} // each component

ksort ($components);
ksort ($map);
ksort ($aliases);

$io->systemConstants->components = $components;
$io->systemConstants->aliases = $aliases;
$io->systemConstants->map = $map;
} // function save

} // class eclFilter_adminTools_components_selectVersion

?>