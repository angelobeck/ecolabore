<?php

class eclIo_sqlite
{ // class eclIo_sqlite

protected $databases = [];

public function connect ($database)
{ // function connect
if (!isset ($this->databases[$database]))
{ // open file
global $io;
$this->databases[$database] = new eclIo_database ($io, $database);
} // open file

return $this->databases[$database];
} // function connect

public function close ()
{ // function close
foreach ($this->databases as $database)
{ // each domain
$database->close ();
} // each domain
} // function close

} // class eclIo_sqlite

?>