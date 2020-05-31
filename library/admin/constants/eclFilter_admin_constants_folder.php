<?php

/*
* control_type
* control_filter
* control_target
* control_field_name
*/

class eclFilter_admin_constants_folder
{ // class eclFilter_admin_constants_folder

static function create ($fieldName, $control, $formulary)
{ // function create
global $io;

// target
if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

// name
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_small';

// get value
$value = '';

if (defined ('SYSTEM_INSTALLATION_PROGRESS'))
$value = $formulary->getField ($target);

if ($value === false)
$value = '';

if ($value == '')
{ // get value from constant
if ($io->systemConstants->check ($target))
$value = $io->systemConstants->constants[$target];
elseif (defined ($target))
$value = constant ($target);
} // from constant

$local['value'] = $value;

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io;

// target
if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

if (defined ('SYSTEM_INSTALLATION_PROGRESS'))
$old = $formulary->getField ($target);
else
{ // from constant

// Old value
if ($io->systemConstants->check ($target))
$old = $io->systemConstants->constants[$target];
elseif (defined ($target))
$old = constant ($target);
} // from constant

if (!isset ($formulary->received[$fieldName]))
return $formulary->setErrorMsg ($control, $fieldName, 'admin_constants_filterFolderError');

$value = $formulary->received[$fieldName];

if (substr ($value,  - 1) != '/')
$value .= '/';

if (defined ('SYSTEM_INSTALLATION_PROGRESS'))
{ // installation in progress
if ($value === $old)
return;
} // installation in progress
else
{ // normal
if ($value == $old and is_dir (PATH_ROOT . $old))
return;
} // normal

if (!preg_match ('%^[.]?[a-zA-Z0-9_-]+\/?$%', $value))
return $formulary->setErrorMsg ($control, $fieldName, 'admin_constants_filterFolderError', $value);

// check for name conflict
$folders = array ('FOLDER_DATABASE', 'FOLDER_DOMAINS', 'FOLDER_ENGINE', 'FOLDER_LIBRARY', 'FOLDER_PROFILES', 'FOLDER_SHARED');
foreach ($folders as $folder)
{ // check folder
if ($folder == $target)
continue;

if (defined ('SYSTEM_INSTALLATION_PROGRESS'))
{ // installation in progress
if (!isset ($formulary->data[$folder]))
continue;

if ($formulary->data[$folder] != $value)
continue;

return $formulary->setErrorMsg ($control, $fieldName, 'admin_constants_filterFolderExists', $value);
} // installation in progress

if (!$io->systemConstants->check ($folder) and constant ($folder) != $value)
continue;

if ($io->systemConstants->check ($folder) and $io->systemConstants->constants[$folder] != $value)
continue;

return $formulary->setErrorMsg ($control, $fieldName, 'admin_constants_filterFolderExists', $value);
} // check folder

if (defined ('SYSTEM_INSTALLATION_PROGRESS'))
return $formulary->setField ($target, $value);

if (is_dir (PATH_ROOT . $value))
return $formulary->setErrorMsg ($control, $fieldName, 'admin_constants_filterFolderExists', $value);

if (is_dir (PATH_ROOT . $old))
rename (PATH_ROOT . $old, PATH_ROOT . $value);
elseif (!is_dir (PATH_ROOT . $value))
mkdir (PATH_ROOT . $value);

$io->systemConstants->set ($target, $value);
} // function save

} // class eclFilter_admin_constants_folder

?>