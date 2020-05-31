<?php

class eclIo_mime
{ // class eclIo_mime

public function __get ($name)
{ // __get
$class = 'eclMime_' . $name;
$this->$name = new $class();
} // __get

public function close()
{ // close
foreach ($this as $driver)
{ // close each driver
$driver->close();
} // close each driver
} // close

} // class eclIo_mime

?>