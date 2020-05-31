<?php

class eclIo_systemConstants
{ // class eclIo_systemConstants

public $constants;
public $originalRows;
public $components;
public $aliases;
public $map;

public function __construct ()
{ // function __construct
global $components, $aliases, $map;
$this->components = $components;
$this->aliases = $aliases;
$this->map = $map;

$this->constants = [];
if (!is_file (SYSTEM_CONFIG_FILE))
return;
$file = file_get_contents (SYSTEM_CONFIG_FILE);
foreach (explode (LF, $file) as $line)
{ // loop each line
if (!strlen ($line))
continue;

if (preg_match ('%define[ ]?[(]['  . TIC . QUOT . ']([a-zA-Z0-9_]+)[' . TIC . QUOT . '][,][ ]?([^)]+)%', $line, $results))
{ // is a definition line
list (, $key, $value) = $results;
if ($value == 'true')
$value = true;
elseif ($value == 'false')
$value = false;
elseif (is_numeric ($value))
$value = intval ($value);
elseif ($value[0] == TIC)
$value = substr ($value, 1,  - 1);
$this->constants[$key] = $value;
} // is a definition line
} // loop each line
$this->originalRows = $this->constants;
} // function __construct

public function set ($name, $value)
{ // function set
$this->constants[$name] = $value;
if (!defined ($name))
define ($name, $value);
} // function set

public function check ($name)
{ // function check
if (isset ($this->constants[$name]))
return true;
return false;
} // function check

public function drop ($name)
{ // function drop
if (isset ($this->constants[$name]))
unset ($this->constants[$name]);
} // function drop

public function get ($name)
{ // function get
if (isset ($this->constants[$name]))
return $this->constants[$name];
if (defined ($name))
return constant ($name);

return false;
} // function get

public function close ()
{ // function close
global $aliases, $components;

if ($this->constants == $this->originalRows and $this->aliases == $aliases and $this->components == $components)
return;

$grouped = [];

foreach ($this->constants as $key => $value)
{ // group constants
@list ($prefix) = explode ('_', $key);
$grouped[$prefix][$key] = $value;
} // group constants

$string = '<' . '?php

/*
** Do not change this file manualy.
** All changes must be made in the administration area,
** and the system will update this file properly.
*/

';
foreach ($grouped as $constants)
{ // each group
foreach ($constants as $key => $value)
{ // each constant
if ($value === false)
$string .= "define (" . TIC . $key . TIC . ", false);" . CRLF;
elseif ($value === true)
$string .= "define (" . TIC . $key . TIC . ", true);" . CRLF;
elseif (is_int ($value))
$string .= "define (" . TIC . $key . TIC . ", " . strval ($value) . ");" . CRLF;
elseif (is_string ($value))
$string .= "define (" . TIC . $key . TIC . ", " . TIC . str_replace (array (TIC, CHR_BSLASH, CR, LF), "", $value) . TIC . ");" . CRLF;
} // each constant
$string .= CRLF;
} // each group

$string .= '$components = array (';
$string .= eclIo_packager::array2php ($this->components) . CRLF;
$string .= ');' . CRLF . CRLF;

$string .= '$aliases = array (';
$string .= eclIo_packager::array2php ($this->aliases) . CRLF;
$string .= ');' . CRLF . CRLF;

$string .= '$map = array (';
$string .= eclIo_packager::array2php ($this->map) . CRLF;
$string .= ');' . CRLF . CRLF;

$string .= '?>';
file_put_contents (SYSTEM_CONFIG_FILE, $string);
} // function close

} // class eclIo_systemConstants

?>