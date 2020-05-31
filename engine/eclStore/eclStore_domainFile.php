<?php

class eclStore_domainFile
{ // class eclStore_domainFile

public function unlink ($domainId, $name)
{ // function unlink
global $store;
$domainName = $store->domain->getName ($domainId);
if (!is_string ($name) or !strlen ($name))
return;

if (is_file (FOLDER_DOMAINS . $domainName . '/' . $name))
unlink (FOLDER_DOMAINS . $domainName . '/' . $name);
} // function unlink

public function scanPrefixedFiles ($domainId, $prefix)
{ // function scanPrefixedFiles
global $store;
$domainName = $store->domain->getName ($domainId);
$prefix .= CHR_FNS;
$prefixLength = strlen ($prefix);
$names = [];
$dir = scandir (FOLDER_DOMAINS . $domainName);
foreach ($dir as $fileName)
{ // each file
if (substr ($fileName, 0, $prefixLength) == $prefix)
$names[] = $fileName;
} // each file
return $names;
} // function scanPrefixedFiles

public function deletePrefixedFiles ($domainId, $prefix, $mode=false)
{ // function deletePrefixedFiles
global $store;
$domainName = $store->domain->getName ($domainId);
$prefix .= CHR_FNS;
$prefixLength = strlen ($prefix);
$dir = scandir (FOLDER_DOMAINS . $domainName);
foreach ($dir as $fileName)
{ // each file
if (substr ($fileName, 0, $prefixLength) == $prefix)
{ // valid file
if ($mode === false)
unlink (FOLDER_DOMAINS . $domainName . '/' . $fileName);
else
{ // select file type
$type = substr ($fileName,  - 4);
switch ($type)
{ // switch file type
case '.jpg':
case '.gif':
case '.png':
if ($mode == MODE_IMG)
unlink (FOLDER_DOMAINS . $domainName . '/' . $fileName);
break;
default:
if ($mode == MODE_FILE)
unlink (FOLDER_DOMAINS . $domainName . '/' . $fileName);
} // switch file type
} // select file type
} // valid file
} // each file
} // function deletePrefixedFiles

public function clonePrefixedFiles ($domainId, $prefix, $newPrefix)
{ // function clonePrefixedFiles
global $store;
$domainName = $store->domain->getName ($domainId);
$prefix .= CHR_FNS;
$newPrefix .= CHR_FNS;
$prefixLength = strlen ($prefix);
$dir = scandir (FOLDER_DOMAINS . $domainName);
foreach ($dir as $fileName)
{ // each file
if (substr ($fileName, 0, $prefixLength) == $prefix)
{ // valid file
$newFileName = $newPrefix . substr ($fileName, $prefixLength);
copy (FOLDER_DOMAINS . $domainName . '/' . $fileName, FOLDER_DOMAINS . $domainName . '/' . $newFileName);
} // valid file
} // each file
} // function clonePrefixedFiles

public function renamePrefixedFiles ($domainId, $prefix, $newPrefix)
{ // function renamePrefixedFiles
global $store;
$domainName = $store->domain->getName ($domainId);
$newPrefix .= CHR_FNS;
$prefix .= CHR_FNS;
$prefixLength = strlen ($prefix);
$dir = scandir (FOLDER_DOMAINS . $domainName);
foreach ($dir as $fileName)
{ // each file
if (substr ($fileName, 0, $prefixLength) == $prefix)
{ // valid file
$oldFileName = FOLDER_DOMAINS . $domainName . '/' . $fileName;
$newFileName = FOLDER_DOMAINS . $domainName . '/' . $newPrefix . substr ($fileName, $prefixLength);
rename ($oldFileName, $newFileName);
} // valid file
} // each file
} // function renamePrefixedFiles

public function close ()
{ // function close
} // function close

} // class eclStore_domainFile

?>