<?php

class eclEngine_io
{ // class eclEngine_io

public $buffer = '';

public $log;

public function __construct ()
{ // function __construct
$this->log = new eclIo_log ();
} // function __construct

public function __get ($name)
{ // function __get
$class = 'eclIo_' . $name;
$this->$name = new $class ($this);
return $this->$name;
} // function __get

public function close ()
{ // function close
if (isset ($this->session))
$this->session->close ();
foreach ($this as $name => $driver)
{ // close each driver
if ($name == 'buffer' or $name == 'session' or $name == 'log' or $name == 'request')
continue;

if (!isset ($driver->isPhantom))
$driver->close ();
} // close each driver
// log é o penúltimo a ser fechado
$this->log->close ();
$this->buffer = $this->log->buffer;
$this->request->close ();
} // function close

} // class eclEngine_io

?>