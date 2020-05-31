<?php

class eclIo_packager
{ // class eclIo_packager

private $databaseString;
private $externalString;
private $dataBuffer;
private $dataIndex;
private $clearFiles;
private $index_contents;
private $folder_engine, $folder_library, $folder_shared, $folder_templates;
private $packMediaFiles;

public $nlMode = CRLF;
public $indentLevel = 100;
public $indent = "\t";
public $outputFileExtension = '.ecl.php';

public function __construct ()
{ // function __construct
global $io;

defined ('SYSTEM_IS_PACKED') or define ('SYSTEM_IS_PACKED', false);

$this->externalString = array (
'#', 
chr (0), 
chr (10), 
chr (13), 
chr (26), 
chr (39), 
chr (92)
);

$this->databaseString = array (
'#c', 
'#0', 
'#n', 
'#r', 
'#z', 
'#s', 
'#e'
);

if ($io->systemConstants->check ('FOLDER_ENGINE'))
$this->folder_engine = $io->systemConstants->constants['FOLDER_ENGINE'];
else
$this->folder_engine = FOLDER_ENGINE;

if ($io->systemConstants->check ('FOLDER_LIBRARY'))
$this->folder_library = $io->systemConstants->constants['FOLDER_LIBRARY'];
else
$this->folder_library = FOLDER_LIBRARY;

if ($io->systemConstants->check ('FOLDER_SHARED'))
$this->folder_shared = $io->systemConstants->constants['FOLDER_SHARED'];
else
$this->folder_shared = FOLDER_SHARED;

if ($io->systemConstants->check ('FOLDER_TEMPLATES'))
$this->folder_templates = $io->systemConstants->constants['FOLDER_TEMPLATES'];
else
$this->folder_templates = FOLDER_TEMPLATES;
} // function __construct

public function pack ($params=[])
{ // function pack
if (defined ('SYSTEM_IS_PACKED') and SYSTEM_IS_PACKED)
return false;

set_time_limit (300);
ignore_user_abort (true);

isset ($params['pack_mode']) or $params['pack_mode'] = 'export';
isset ($params['filename']) or $params['filename'] = 'install.php';
isset ($params['clear_source_files']) or $params['clear_source_files'] = false;
isset ($params['pack_media_files']) or $params['pack_media_files'] = false;
$this->packMediaFiles = $params['pack_media_files'];

$this->dataBuffer = '';
$this->dataIndex = [];
$this->index_contents = file_get_contents (PATH_ROOT . SYSTEM_SCRIPT_NAME);
$this->clearFiles = [];

$this->pack_controls ();
$this->pack_shared ();
$this->pack_templates ();
$this->pack_embeded ();
$this->pack_shared_libraries ();

$settings = array (
'SYSTEM_IS_PACKED' => true, 
'SYSTEM_PACKED_DATE' => date ('r', TIME)
);

if ($params['pack_mode'] == 'export')
$fileName = PATH_ROOT . $params['filename'];
else
$fileName = PATH_ROOT . SYSTEM_SCRIPT_NAME;

$package = fopen ($fileName, 'a+b');
ftruncate ($package, 0);
fwrite ($package, $this->get_index_header ());
fwrite ($package, $this->set_packager_settings ($settings));
fwrite ($package, $this->pack_map ());
fwrite ($package, $this->pack_data_map ());
fwrite ($package, $this->pack_scripts ());
fwrite ($package, $this->get_index_footer ());
fwrite ($package, $this->dataBuffer);
fclose ($package);

if (!$params['clear_source_files'] or $params['pack_mode'] == 'export')
return;

// delete source files
foreach ($this->clearFiles as $file)
{ // clear each source file
unlink ($file);
} // clear each source file

// clear empty folders
$folders = array (PATH_ENGINE, PATH_LIBRARY, PATH_SHARED, PATH_TEMPLATES);
$list = [];
foreach ($folders as $level1)
{ // each level 1
if ($level1[0] == '.')
continue;

if (!is_dir ($level1))
continue;

$list[] = $level1;
foreach (scandir ($level1) as $level2)
{ // each level 2
if ($level2[0] == '.')
continue;

$level2 = $level1 . $level2 . '/';
if (!is_dir ($level2))
continue;

$list[] = $level2;
foreach (scandir ($level2) as $level3)
{ // each level 3
if ($level3[0] == '.')
continue;

$level3 = $level2 . $level3 . '/';
if (!is_dir ($level3))
continue;

$list[] = $level3;
foreach (scandir ($level3) as $level4)
{ // each level 4
if ($level4[0] == '.')
continue;

$level4 = $level3 . $level4 . '/';
if (!is_dir ($level4))
continue;

$list[] = $level4;
} // each level 4
} // each level 3
} // each level 2
} // each level 1

$list = array_reverse ($list);
foreach ($list as $dir)
{ // each dir
if (count (scandir ($dir)) == 2)
rmdir ($dir);
} // each dir
} // function pack

private function get_index_header ()
{ // function get_index_header
$indexFile = &$this->index_contents;
$start = strpos ($indexFile, '//!' . 'packager:start_of_files');
if (!$start)
exit ('markup "//!' . 'packager:start_of_files" not found in index.php');
$start += strlen ('//!' . 'packager:start_of_files');
$indexHeader = substr ($indexFile, 0, $start) . CRLF;
return $indexHeader;
} // function get_index_header

private function get_index_footer ()
{ // function get_index_footer
$indexFile = $this->index_contents;
$start = strpos ($indexFile, '//!' . 'packager:end_of_files');
if (!$start)
exit ('markup "//!' . 'packager:end_of_files" not found in index.php');

if (defined ('SYSTEM_COMPILER_HALT_OFFSET'))
$indexFooter = substr ($indexFile, $start, SYSTEM_COMPILER_HALT_OFFSET - $start);
else
$indexFooter = substr ($indexFile, $start) . CRLF . '__halt_' . 'compiler();';
return $indexFooter;
} // function get_index_footer

private function set_packager_settings ($settings)
{ // function set_packager_settings
$buffer = '//!' . 'start_of_packager_settings' . CRLF . CRLF;
foreach ($settings as $key => $value)
{ // each setting
$buffer .= 'define(' . "" . TIC . $key . "" . TIC . ', ';
if (is_string ($value))
$buffer .= "" . TIC . str_replace (TIC, "\'", $value) . TIC . "";
elseif (is_int ($value))
$buffer .= strval ($value);
elseif ($value === false)
$buffer .= 'false';
else
$buffer .= 'true';

$buffer .= ');' . CRLF;
} // each setting

$buffer .= CRLF . '//!' . 'end_of_packager_settings' . CRLF;
return $buffer;
} // function set_packager_settings

private function pack_map ()
{ // function pack_map
$map = [];

foreach (scandir (PATH_LIBRARY) as $module)
{ // scan library
if ($module[0] == '.')
continue;

foreach (scandir (PATH_LIBRARY . $module) as $name)
{ // each file
if (!is_file (PATH_LIBRARY . $module . '/' . $name))
continue;

if (substr ($name, 0, 4) != 'app_' or substr ($name,  - 4) != '.txt')
continue;

$this->clearFiles[] = PATH_LIBRARY . $module . '/' . $name;

if (substr ($name,  - 12) == '_prepend.txt')
{ // prepend map
$target = 'prepend';
$applicationName = substr ($name, 4,  - 12);
} // prepend map
else
{ // append
$target = 'append';
$applicationName = substr ($name, 4,  - 11);
} // append

$content = file_get_contents (PATH_LIBRARY . $module . '/' . $name);
$lines = [];
foreach (explode ("\n", $content) as $line)
{ // each line
$line = trim ($line);
if (strlen ($line) and preg_match ('/^[a-zA-Z0-9_]+$/', $line))
$map[$target][$applicationName][] = $line;
} // each line
} // each file
} // scan library

if (!$map)
return '';

$buffer = '//!' . 'packager:start_of_map' . CRLF;
$buffer .= CRLF;

if (isset ($map['prepend']))
{ // prepend
$buffer .= '$mapPrepend = array (' . CRLF;
foreach ($map['prepend'] as $module => $list)
{ // each module
$buffer .= "" . TIC . $module . TIC . " => array (" . TIC . implode ("', '", $list) . TIC . ")," . CRLF;
} // each module
$buffer .= ');' . CRLF . CRLF;
} // prepend

if (isset ($map['append']))
{ // append
$buffer .= '$mapAppend = array (' . CRLF;
foreach ($map['append'] as $module => $list)
{ // each module
$buffer .= "" . TIC . $module . TIC . " => array (" . TIC . implode ("', '", $list) . TIC . ")," . CRLF;
} // each module
$buffer .= ');' . CRLF . CRLF;
} // append

$buffer .= '//!' . 'packager:end_of_map' . CRLF;

return $buffer;
} // function pack_map

private function add ($mode, $path, $name, $content)
{ // function add
$content = str_replace ($this->externalString, $this->databaseString, $content);

$offset = strlen ($this->dataBuffer);
$length = strlen ($content);

$this->dataBuffer .= $content;
$this->dataIndex[$mode][$path][$name] = strval ($offset) . ':' . strval ($length);
} // function add

private function pack_controls ()
{ // function pack_controls
foreach ($this->getAllFilesNames (PATH_LIBRARY) as $file)
{ // each file

$parts = explode ('/', $file);
$name = array_pop ($parts);
$last = array_pop ($parts);

if ($last == '_controls')
$mode = 'c';
elseif ($last == '_help')
$mode = 'h';
else
continue;

if (substr ($file,  - 8) == '.ecl.php')
{ // control
$name = substr ($name, 0,  - 8);
$this->clearFiles[] = PATH_LIBRARY . $file;
$content = serialize (eclIo_file::string2array (file_get_contents (PATH_LIBRARY . $file)));
} // control
elseif (!$this->packMediaFiles)
continue;
else
{ // media
$this->clearFiles[] = PATH_LIBRARY . $file;
$content = file_get_contents (PATH_LIBRARY . $file);
} // media

$path = implode ('_', $parts);
$this->add ($mode, $path, $name, $content);
} // each file
} // function pack_controls

private function pack_shared ()
{ // function pack_shared
foreach ($this->getAllFilesNames (PATH_SHARED) as $file)
{ // each file
if (substr ($file,  - 8) != '.ecl.php')
continue;

$this->clearFiles[] = PATH_SHARED . $file;
$content = serialize (eclIo_file::string2array (file_get_contents (PATH_SHARED . $file)));
$parts = explode ('/', $file);
$name = array_pop ($parts);
$path = implode ('/', $parts);

$this->add ('s', $path, substr ($name, 0,  - 8), $content);
} // each file
} // function pack_shared

private function pack_templates ()
{ // function pack_templates
foreach ($this->getAllFilesNames (PATH_TEMPLATES) as $file)
{ // each file

$parts = explode ('/', $file);
$name = array_pop ($parts);

if (substr ($file,  - 8) != '.ecl.php')
continue;

$name = substr ($name, 0,  - 8);
$this->clearFiles[] = PATH_TEMPLATES . $file;
$content = serialize (eclIo_file::string2array (file_get_contents (PATH_TEMPLATES . $file)));

$path = implode ('/', $parts);
$this->add ('t', $path, $name, $content);
} // each file
} // function pack_templates

private function pack_embeded ()
{ // function pack_embeded
foreach ($this->getAllFilesNames (PATH_SHARED) as $file)
{ // each file
if (substr ($file,  - 8) == '.ecl.php')
continue;

if (!$this->packMediaFiles and substr ($file,  - 4) == '.mp3')
continue;

$this->clearFiles[] = PATH_SHARED . $file;
$content = file_get_contents (PATH_SHARED . $file);
$parts = explode ('/', $file);
$name = array_pop ($parts);
$path = implode ('/', $parts);

$this->add ('f', $path, $name, $content);
} // each file
} // function pack_embeded

private function pack_shared_libraries ()
{ // pack_shared_libraries
if (!is_dir (PATH_SHARED . 'libraries/'))
return;
foreach (scandir (PATH_SHARED . 'libraries/') as $folder)
{ // each folder
if ($folder[0] == '.')
continue;
$dir = PATH_SHARED . 'libraries/' . $folder;
$content = eclApp_systemShared::join_library ($dir);
$this->add ('f', 'scripts', $folder . '.js', $content);
} // each folder
} // pack_shared_libraries

private function pack_data_map ()
{ // function pack_data_map
$dataIndex = '//!' . 'packager:start_of_data_index' . CRLF;
$dataIndex .= '$dataMap = array (' . CRLF;
$dataIndex .= $this->array2php ($this->dataIndex) . CRLF;
$dataIndex .= ');' . CRLF;
$dataIndex .= '//!' . 'packager:end_of_data_index' . CRLF;
return $dataIndex;
} // function pack_data_map

private function pack_scripts ()
{ // function pack_scripts
$buffer = '';
$allFiles = [];
foreach ($this->getAllFilesNames (PATH_ENGINE) as $file)
{ // loop engine files
if (substr ($file,  - 8) == '.ecl.php')
continue;

if (substr ($file,  - 4) != '.php')
continue;

$allFiles[PATH_ENGINE . $file] = 'engine/' . $file;
} // loop engine files

foreach ($this->getAllFilesNames (PATH_LIBRARY) as $file)
{ // loop library files
if (substr ($file,  - 8) == '.ecl.php')
continue;

if (substr ($file,  - 4) != '.php')
continue;

$allFiles[PATH_LIBRARY . $file] = 'library/' . $file;
} // loop library files

foreach ($allFiles as $fromFileName => $toFileName)
{ // get all scripts
$this->clearFiles[] = $fromFileName;
$string = file_get_contents ($fromFileName);
if (substr ($string, 0, 5) == '<' . '?php')
$string = substr ($string, 5);
if (substr ($string,  - 2) == '?>')
$string = substr ($string, 0,  - 2);

$buffer .= '//!' . 'file:' . $toFileName . ';' . CRLF . $string . '//' . '!eof:' . $toFileName . ';' . CRLF;
} // get all files

return $buffer;
} // function pack_scripts

public static function array2php ($array)
{ // function array2php
if (!is_array ($array))
return '';

// a string resultante
$string = '';

foreach ($array as $key => $value)
{ // each item
if (is_int ($key))
$string .= strval ($key);
else
$string .= "" . TIC . $key . TIC . "";
$string .= ' => ';

if ($value === false)
$string .= 'false';
elseif ($value === true)
$stirng .= 'true';
elseif (is_int ($value) or is_float ($value))
$string .= strval ($value);
elseif (is_string ($value))
$string .= "" . TIC . $value . TIC . "";
elseif (is_array ($value))
$string .= 'array (' . CRLF . self::array2php ($value) . ')';
else
$string .= "''";

$string .= ',' . CRLF;
} // each item

return $string;
} // function array2php

public function extract ($params=[])
{ // function extract
if (!defined ('SYSTEM_IS_PACKED') or !SYSTEM_IS_PACKED)
return;

set_time_limit (3600);
ignore_user_abort (true);

$this->index_contents = file_get_contents (PATH_ROOT . SYSTEM_SCRIPT_NAME);

$this->extract_scripts ($params);
$this->extract_controls ($params);
$this->extract_map ();
$this->extract_shared ($params);
$this->extract_templates ($params);
$this->extract_embeded ();

$settings = array ('SYSTEM_IS_PACKED' => false);
if (isset ($params['output_data_file_extension']))
$settings['SYSTEM_DATA_FILE_EXTENSION'] = $params['output_data_file_extension'];

$buffer = $this->get_index_header ();
$buffer .= $this->set_packager_settings ($settings);
$buffer .= $this->get_index_footer ();

if (isset ($params['create_backup']))
{ // create beckup
if (isset ($params['filename']))
$fileName = $params['filename'];
else
$fileName = 'install.php';

$package = fopen (PATH_ROOT . $fileName, 'a+b');
ftruncate ($package, 0);
fwrite ($package, $this->index_contents);
fclose ($package);
} // create backup

$index = fopen (PATH_ROOT . SYSTEM_SCRIPT_NAME, 'a+b');
ftruncate ($index, 0);
fwrite ($index, $buffer);
fclose ($index);
} // function extract

private function extract_scripts ($params)
{ // function extract_scripts
global $io;

$string = $this->index_contents;

$pointer = 0;
$numFiles = 0;

for ($watchDog = 0; $watchDog < 2000; $watchDog++)
{ // loop
$pointer = strpos ($string, '//!' . 'file:', $pointer);
if ($pointer === false)
break;

$pointer += 8;
$end = strpos ($string, ";", $pointer);
$length = $end - $pointer;

$pathway = substr ($string, $pointer, $length);
$numFiles++;

$pointer = $end + 1;
$end = strpos ($string, '//!' . 'eof:' . $pathway, $pointer);
if ($end === false)
break;

$length = $end - $pointer;
$file = '<' . '?' . 'php' . CRLF . CRLF . trim (substr ($string, $pointer, $length))
 . CRLF . CRLF . '?' . '>';

// $file = $io->scriptFormat->format ($file, $params);
$folders = explode ('/', $pathway);

if ($folders[0] == 'engine')
$folders[0] = $this->folder_engine;
elseif ($folders[0] == 'library')
$folders[0] = $this->folder_library;
if (substr ($folders[0],  - 1) == '/')
$folders[0] = substr ($folders[0], 0,  - 1);
$fileName = implode ('/', $folders);
$this->filePutContents (PATH_ROOT, $fileName, $file);
} // loop
} // function extract_scripts

private function extract_map ()
{ // function extract_map
global $mapPrepend, $mapAppend;

// extract maps
foreach ($mapPrepend as $application => $names)
{ // each map
list ($module) = explode ('_', $application);

$content = implode (CRLF, $names);
$this->filePutContents (PATH_ROOT, $this->folder_library . $module . '/app_' . $application . '_prepend.txt', $content);
} // each map

foreach ($mapAppend as $application => $names)
{ // each map
list ($module) = explode ('_', $application);

$content = implode (CRLF, $names);
$this->filePutContents (PATH_ROOT, $this->folder_library . $module . '/app_' . $application . '_append.txt', $content);
} // each map
} // function extract_map

private function extract_controls ()
{ // function extract_controls
global $io, $dataMap;

// extract controls
foreach ($dataMap['c'] as $module => $names)
{ // each module controls
$path = str_replace ('_', '/', $module);
foreach ($names as $name => $pos)
{ // each name
$content = $io->file->array2string ($io->cachedControls->read ('c', $module, $name), '#');
$this->filePutContents (PATH_ROOT, $this->folder_library . $path . '/_controls/' . $name . '.ecl.php', $content);
} // each name
} // each module controls

foreach ($dataMap['h'] as $module => $names)
{ // each module help
$path = str_replace ('_', '/', $module);
foreach ($names as $name => $pos)
{ // each name
if (strpos ($name, '.') === false)
{ // control
$content = $io->file->array2string ($io->cachedControls->read ('h', $module, $name), '#');
$this->filePutContents (PATH_ROOT, $this->folder_library . $path . '/_help/' . $name . '.ecl.php', $content);
} // control
else
{ // media
$content = $io->cachedControls->file ($module, $name, 'h');
$this->filePutContents (PATH_ROOT, $this->folder_library . $path . '/_help/' . $name, $content);
} // media
} // each name
} // each module help
} // function extract_controls

private function extract_shared ()
{ // function extract_shared
global $io, $dataMap;

foreach ($dataMap['s'] as $path => $names)
{ // each module
foreach ($names as $name => $pos)
{ // each name
$content = $io->file->array2string ($io->cachedControls->read ('s', $path, $name));
$this->filePutContents (PATH_ROOT, $this->folder_shared . $path . '/' . $name . '.ecl.php', $content);
} // each name
} // each module
} // function extract_shared

private function extract_templates ()
{ // function extract_templates
global $io, $dataMap;

foreach ($dataMap['t'] as $path => $names)
{ // each module
foreach ($names as $name => $pos)
{ // each name
$content = $io->file->array2string ($io->cachedControls->read ('t', $path, $name));
$this->filePutContents (PATH_ROOT, $this->folder_templates . $path . '/' . $name . '.ecl.php', $content);
} // each name
} // each module
} // function extract_templates

private function extract_embeded ()
{ // function extract_embeded
global $io, $dataMap;
foreach ($dataMap['f'] as $path => $names)
{ // each module
foreach ($names as $name => $pos)
{ // each name
$content = $io->cachedControls->file ($path, $name);
$this->filePutContents (PATH_ROOT, $this->folder_shared . $path . '/' . $name, $content);
} // each name
} // each module
} // function extract_embeded

public function getAllFilesNames ($basedir, $path='', $files=[])
{ // function getAllFilesNames
foreach (scandir ($basedir . $path) as $name)
{ // loop scandir
if ($name[0] == '.')
continue;

if (is_dir ($basedir . $path . $name))
$files = $this->getAllFilesNames ($basedir, $path . $name . '/', $files);
elseif (is_file ($basedir . $path . $name))
$files[] = $path . $name;
} // loop scandir
return $files;
} // function getAllFilesNames

public function filePutContents ($basedir, $file, $string)
{ // function filePutContents
$parts = explode ('/', $file);
$name = array_pop ($parts);
$dir = $basedir;
foreach ($parts as $folder)
{ // each folder
$dir .= $folder . '/';
if (!is_dir ($dir))
mkdir ($dir);
} // each folder
file_put_contents ($basedir . $file, $string);
} // function filePutContents

public function close ()
{ // function close
} // function close

} // class eclIo_packager

?>