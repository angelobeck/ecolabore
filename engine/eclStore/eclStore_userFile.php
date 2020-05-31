<?php

class eclStore_userFile
{ // class eclStore_userFile

public function unlink ($userId, $name)
{ // function unlink
global $store;
$userName = $store->user->getName ($userId);
if (!is_string ($name) or !strlen ($name))
return;

if (is_file (FOLDER_PROFILES . $userName . '/' . $name))
unlink (FOLDER_PROFILES . $userName . '/' . $name);
} // function unlink

public function deletePrefixedFiles ($userId, $prefix, $mode=false)
{ // function deletePrefixedFiles
global $store;
$userName = $store->user->getName ($userId);
$prefix .= CHR_FNS;
$prefixLength = strlen ($prefix);
$dir = scandir (FOLDER_PROFILES . $userName);
foreach ($dir as $fileName)
{ // each file
if (substr ($fileName, 0, $prefixLength) == $prefix)
{ // valid file
if ($mode === false)
unlink (FOLDER_PROFILES . $userName . '/' . $fileName);
else
{ // select file type
$type = substr ($fileName,  - 4);
switch ($type)
{ // switch file type
case '.jpg':
case '.gif':
case '.png':
if ($mode == MODE_IMG)
unlink (FOLDER_PROFILES . $userName . '/' . $fileName);
break;
default:
if ($mode == MODE_FILE)
unlink (FOLDER_PROFILES . $userName . '/' . $fileName);
} // switch file type
} // select file type
} // valid file
} // each file
} // function deletePrefixedFiles

public function clonePrefixedFiles ($userId, $prefix, $newPrefix)
{ // function clonePrefixedFiles
global $store;
$userName = $store->user->getName ($userId);
$prefix .= CHR_FNS;
$newPrefix .= CHR_FNS;
$prefixLength = strlen ($prefix);
$dir = scandir (FOLDER_PROFILES . $userName);
foreach ($dir as $fileName)
{ // each file
if (substr ($fileName, 0, $prefixLength) == $prefix)
{ // valid file
$newFileName = $newPrefix . substr ($fileName, $prefixLength);
copy (FOLDER_PROFILES . $userName . '/' . $fileName, FOLDER_PROFILES . $userName . '/' . $newFileName);
} // valid file
} // each file
} // function clonePrefixedFiles

public function renamePrefixedFiles ($userId, $prefix, $newPrefix)
{ // function renamePrefixedFiles
global $store;
$userName = $store->user->getName ($userId);
$newPrefix .= CHR_FNS;
$prefix .= CHR_FNS;
$prefixLength = strlen ($prefix);
$dir = scandir (FOLDER_PROFILES . $userName);
foreach ($dir as $fileName)
{ // each file
if (substr ($fileName, 0, $prefixLength) == $prefix)
{ // valid file
$oldFileName = FOLDER_PROFILES . $userName . '/' . $fileName;
$newFileName = FOLDER_PROFILES . $userName . '/' . $newPrefix . substr ($fileName, $prefixLength);
rename ($oldFileName, $newFileName);
} // valid file
} // each file
} // function renamePrefixedFiles

public function close ()
{ // function close
} // function close

} // class eclStore_userFile

?>