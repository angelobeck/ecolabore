<?php

class eclIo_cachedControls
{ // class eclIo_cachedControls

private $enabled = false;
private $file;
private $externalString2;
private $databaseString2;

public function __construct ()
{ // function __construct
global $dataMap;
if (!isset ($dataMap) or !$dataMap or !defined ('SYSTEM_COMPILER_HALT_OFFSET'))
return;

$this->file = fopen (SYSTEM_SCRIPT_PATH, 'rb');
$this->enabled = true;
} // function __construct

public function read ($mode, $path, $name)
{ // function read
global $dataMap;

if (!$this->enabled)
return [];

if (!isset ($dataMap[$mode][$path][$name]))
return [];

list ($offset, $length) = explode (':', $dataMap[$mode][$path][$name]);
fseek ($this->file, SYSTEM_COMPILER_HALT_OFFSET + $offset);
return unserialize ($this->stringFromDatabase (fread ($this->file, $length)));
} // function read

public function file ($path, $name, $mode='f')
{ // function file
global $dataMap;

if (!isset ($dataMap[$mode][$path][$name]))
return '';

list ($offset, $length) = explode (':', $dataMap[$mode][$path][$name]);
fseek ($this->file, SYSTEM_COMPILER_HALT_OFFSET + $offset);
return $this->stringFromDatabase (fread ($this->file, $length));
} // function file

public function stringFromDatabase ($string)
{ // function stringFromDatabase
if (!isset ($this->externalString2))
{ // set replaces
$this->externalString2 = array (
chr (0), 
chr (10), 
chr (13), 
chr (26), 
chr (39), 
chr (92)
);

$this->databaseString2 = array (
'#0', 
'#n', 
'#r', 
'#z', 
'#s', 
'#e'
);
} // set replacements
return str_replace ('#c', '#', str_replace ($this->databaseString2, $this->externalString2, $string));
} // function stringFromDatabase

public function close ()
{ // function close
if ($this->enabled)
fclose ($this->file);
} // function close

} // class eclIo_cachedControls

?>