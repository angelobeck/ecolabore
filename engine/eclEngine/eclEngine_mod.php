<?php

class eclEngine_mod
{ // class eclEngine_mod

private $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function __get ($name)
{ // function __get
$class = 'eclMod_mod' . ucfirst ($name);
$this->$name = new $class ($this->document);
return $this->$name;
} // function __get

public function __set ($name, $value)
{ // function __set
if (is_object ($value))
$this->$name = $value;
elseif (is_string ($value))
{ // new module
$class = 'eclMod_' . $value;
$this->$name = new $class ($this->document);
} // new module
} // function __set

} // class eclEngine_mod

?>