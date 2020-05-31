<?php

class eclStore_control
{ // class eclStore_control

public $name = 'unknown';

public $controls = [];
private $render;
public $php = '';

public function __construct ()
{ // function __construct
$this->render = new eclEngine_render ();
} // function __construct

public function read ($controlName)
{ // function read
global $aliases, $components, $io, $store;

if (isset ($this->controls[$controlName]))
return $this->controls[$controlName];

$folders = explode ('/', $controlName);
$count = count ($folders);
switch ($count)
{ // switch number of folders
case 1:
$parts = explode ('_', $controlName, 3);
if (count ($parts) < 2)
return [];

$name = array_pop ($parts);
if (isset ($aliases[$parts[0]]) and isset ($components[$aliases[$parts[0]]]))
$file = PATH_COMPONENTS . $aliases[$parts[0]] . '/' . $components[$aliases[$parts[0]]] . '/' . implode ('/', $parts) . '/_controls/' . $name . '.ecl.php';
else
$file = PATH_LIBRARY . implode ('/', $parts) . '/_controls/' . $name . '.ecl.php';
$path = implode ('_', $parts);
$prefix = $path . '_';
$mode = 'c';
break;

default:
$file = PATH_TEMPLATES . $controlName . '.ecl.php';
$name = array_pop ($folders);
$path = implode ('/', $folders);
$prefix = $path . '/';
$mode = 't';
} // switch number of folders

$control = $io->cachedControls->read ($mode, $path, $name);
if (!$control)
$control = $io->file->read ($file);

if (isset ($control['children']))
{ // children exists
foreach ($control['children'] as &$child)
{ // each child
if ($child[0] == '~')
$child = $prefix . substr ($child, 1);
} // each child
} // children exists

if (isset ($control['flags']))
{ // flags exists
foreach ($control['flags'] as &$value)
{ // each flag
if (is_string ($value) and strlen ($value) and $value[0] == '~')
$value = $prefix . substr ($value, 1);
} // each flags
} // flags exists

if (isset ($control['flags']['caption']))
{ // set caption
if (!isset ($control['text']))
$control['text'] = [];

$caption = $this->read ($control['flags']['caption']);
if (isset ($caption['text']))
$control['text'] = array_replace ($caption['text'], $control['text']);
} // set caption

if (isset ($control['html']))
$control['parsed'] = $this->render->render_tags ($control['html'], true);

$this->controls[$controlName] = $control;
return $control;
} // function read

public function scandir ($mode, $dir)
{ // function scandir
global $dataMap, $io;
if (SYSTEM_IS_PACKED)
{ // system packed
if (isset ($dataMap[$mode][$dir]))
{ // dir found
$names = [];
foreach ($dataMap[$mode][$dir] as $name => $value)
{ // each name
$names[] = $name;
} // each name
return $names;
} // dir found

// search subfolders
$dir .= '/';
$length = strlen ($dir);

$names = [];
foreach ($dataMap[$mode] as $name => $value)
{ // each dir
if (strlen ($name) <= $length)
continue;
if (substr ($name, 0, $length) == $dir)
$names[] = substr ($name, $length);
} // each dir
return $names;
} // system packed

if ($mode == 'c')
{ // control mode
if (!is_dir (PATH_LIBRARY . $dir . '/_controls'))
return [];

$names = [];
foreach (scandir (PATH_LIBRARY . $dir . '/_controls') as $name)
{ // each name
if ($name[0] == '.')
continue;

if (substr ($name,  - 8) == '.ecl.php')
$names[] = substr ($name, 0,  - 8);
} // each name
return $names;
} // control mode

if ($mode == 'f')
{ // embeded mode
if (!is_dir (PATH_SHARED . $dir))
return [];

$names = [];
foreach (scandir (PATH_SHARED . $dir) as $name)
{ // each name
if ($name[0] == '.')
continue;
$names[] = $name;
} // each name
return $names;
} // shared mode

if ($mode == 's')
{ // shared mode
if (!is_dir (PATH_SHARED . $dir))
return [];

$names = [];
foreach (scandir (PATH_SHARED . $dir) as $name)
{ // each name
if ($name[0] == '.')
continue;
$names[] = $name;
} // each name
return $names;
} // embeded mode

if ($mode == 't')
{ // template mode
if (!is_dir (PATH_TEMPLATES . $dir))
return [];

$names = [];
foreach (scandir (PATH_TEMPLATES . $dir) as $name)
{ // each name
if ($name[0] == '.')
continue;
if (substr ($name,  - 8) == '.ecl.php')
$name = substr ($name, 0,  - 8);
$names[] = $name;
} // each name
return $names;
} // templates mode

return [];
} // function scandir

public function readFile ($mode, $path, $name)
{ // function readFile
global $io;

if ($mode == 'c')
$base = PATH_LIBRARY;
elseif ($base = 's' or $base = 'f')
$base = PATH_SHARED;
elseif ($base = 't')
$base = PATH_TEMPLATES;

$content = $io->cachedControls->file ($path, $name, $mode);
if (!isset ($content[0]))
$content = $io->fileBinary->open ($base . $path . '/' . $name);

return $content;
} // function readFile

public function close ()
{ // function close
} // function close

public function renderToPhp ($array)
{ // function renderToPhp
$length = count ($array);
if (!$length)
return;
$index = 0;
goto LEVEL_NEXT_INSTRUCTION_1;

LEVEL_NEXT_INSTRUCTION:
$index++;
if ($index == $length)
return;

LEVEL_NEXT_INSTRUCTION_1:
$current = $array[$index];

if (!isset ($current[1]))
goto LEVEL_NEXT_INSTRUCTION;

if ($current[0] == 0)
{ // buffer
$this->php .= '$buffer .= ' . "" . TIC . str_replace (TIC, CHR_BSLASH . TIC . "", $current[1]) . TIC . ";" . CRLF;
goto LEVEL_NEXT_INSTRUCTION;
} // buffer

if (isset ($current[2]) and $current[2])
$arguments = $this->renderArgumentsToPhp ($current[2]);
else
$arguments = [];

switch ($current[1])
{ // switch function name

case 'if':
$this->php .= 'if (' . $arguments[0] . '){' . CRLF;
if (isset ($current[3]) and $current[3])
$this->renderToPhp ($current[3]);
$this->php .= '}' . CRLF;

while ($index + 1 != $length and isset ($array[$index + 1][1]) and $array[$index + 1][1] == 'elseif')
{ // loop elseif
$current = $array[$index + 1];
if (isset ($current[2]) and isset ($current[3]) and $current[3])
{ // conditions and block exists
$arguments = $this->renderArgumentsToPhp ($current[2]);
$this->php .= 'elseif (' . $arguments[0] . '){' . CRLF;
$this->renderToPhp ($current[3]);
$this->php .= '}' . CRLF;
} // conditions and block exists
$index++;
} // loop elseif

if ($index + 1 != $length and isset ($array[$index + 1][1]) and $array[$index + 1][1] == 'else')
{ // else
$current = $array[$index + 1];
if (isset ($current[3]) and $current[3])
{ // block exists
$this->php .= 'else {' . CRLF;
$this->renderToPhp ($current[3]);
$this->php .= '}' . CRLF;
} // block exists
$index++;
} // else

goto LEVEL_NEXT_INSTRUCTION;

case 'loop':
$this->php .= 'do {' . CRLF;

if (isset ($current[3]) and $current[3])
$this->renderToPhp ($current[3]);

$this->php .= '} while (eclTag_loop::render ($render));' . CRLF;
goto LEVEL_NEXT_INSTRUCTION;

case '':
$this->php .= '$buffer .= ' . implode (' . ', $arguments) . ';' . CRLF;
goto LEVEL_NEXT_INSTRUCTION;

default:
$class = 'eclTag_' . $current[1];
if (!preg_match ('/^[a-z][a-zA-Z0-9_]*$/', $class))
goto LEVEL_NEXT_INSTRUCTION;

if (isset ($current[3]) and $current[3])
{ // not empty
$this->php .= '$local = ' . $class . '::render ($render, array(' . implode (', ', $arguments) . '));' . CRLF;
$this->php .= '$render->levelUp ($local);' . CRLF;
$this->renderToPhp ($current[3]);
$this->php .= '$render->levelDown ();' . CRLF;
goto LEVEL_NEXT_INSTRUCTION;
} // not empty

switch ($current[1])
{ // switch return value tags functions
case 'nl':
$this->php .= '$buffer .= CRLF;' . CRLF;
break;

case 'details':
case 'field':
case 'help':
case 'interactive':
case 'lang':
case 'list':
case 'modlist':
case 'personalite':
case 'scope':

$this->php .= '$local = ' . $class . '::render ($render, array (' . implode (', ', $arguments) . '));' . CRLF;
$this->php .= 'if (isset ($local["parsed"]))
{ // parsed content
$render->levelUp ($local);
$render->render_tags_level ($local["parsed"]);
$render->levelDown ();
} // parsed content
elseif (isset ($local["html"]))
{ // render html
$render->levelUp ($local);
$render->render_tags ($local["html"]);
$render->levelDown ();
} // render html
';
break;

case 'box':
case 'img':
case 'mod':
case 'table':

$this->php .= '$local = ' . $class . '::render ($render, array (' . implode (', ', $arguments) . '));
if ($local){
$render->levelUp ($local);
if (isset ($local["parsed"]))
$render->render_tags_level ($local["parsed"]);
elseif (isset ($local["html"]))
$render->render_tags ($local["html"]);
else
eclRender_renderBorders_none::render ($render);
$render->levelDown ();
}
';
break;

default:
$this->php .= $class . '::render ($render, array (' . implode (', ', $arguments) . '));' . CRLF;
} // switch return tag functions
goto LEVEL_NEXT_INSTRUCTION;
} // switch function name
goto LEVEL_NEXT_INSTRUCTION;
} // function renderToPhp

private function renderArgumentsToPhp ($array)
{ // function renderArgumentsToPhp
$arguments = [];
foreach ($array as $current)
{ // each argument
switch ($current[0])
{ // switch argument type
case 0:
$arguments[] = "" . TIC . str_replace (TIC, CHR_BSLASH . TIC . "", $current[1]) . TIC . "";
break;

case 3:
$arguments[] = strval ($current[1]);
break;

case 1:
if (isset ($current[2]) and $current[2])
$sub = $this->renderArgumentsToPhp ($current[2]);
else
$sub = [];

if ($current[1] == '')
{ // anonimous function
if (isset ($sub[0]))
$arguments[] = $sub[0];
} // anonimous function
else
{ // sub function
$func = 'eclTag_' . $current[1];
$arguments[] = $func . '::render ($render, array (' . implode (', ', $sub) . '));' . CRLF;
} // sub function
break;

case 2:
$arguments[] = '$render->getVar (' . "" . TIC . $current[1] . "" . TIC . ')';
break;

case 4:
if (!isset ($current[2]) or !$current[2])
break;
$sub = $this->renderArgumentsToPhp ($current[2]);
if (count ($sub) < 2)
break;

switch ($current[1])
{ // switch operator
case 'and':
$arguments[] = '(' . $sub[0] . ' and ' . $sub[1] . ')';
break;

case 'or':
$arguments[] = 'eclTag_or::render ($render, array (' . $sub[0] . ', ' . $sub[1] . '))';
break;
} // switch operator
break;

case 5:
if (!isset ($current[2]) or !$current[2])
break;
$sub = $this->renderArgumentsToPhp ($current[2]);
if (!count ($sub))
break;
switch ($current[1])
{ // switch operator
case '!':
$arguments[] = '!' . $sub[0];
} // switch operator
break;
} // switch argument type
} // each argument
return $arguments;
} // function renderArgumentsToPhp

} // class eclStore_control

?>