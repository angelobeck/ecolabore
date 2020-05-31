<?php

class eclEngine_store
{ // class eclEngine_store

public function __get ($name)
{ // function __get
$class = 'eclStore_' . $name;
$this->$name = new $class;
return $this->$name;
} // function __get

public function close ()
{ // function close
foreach ($this as $name => $driver)
{ // close each driver
if (!isset ($driver->isPhantom))
$driver->close ();
} // close each driver
} // function close

} // class eclEngine_store

?>