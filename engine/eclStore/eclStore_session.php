<?php

class eclStore_session
{ // class eclStore_session

public $name = 'session';

// Describe fields structure
public $fields = array (
// Indexing
'id' => 'primary_key', 
// Class identifiers
'token' => 'name', 
'fingerprint' => 'name',
'status' => 'tinyint',
// dates
'created' => 'time',
'updated' => 'time',
// data
'session' => 'binary',
);

public $insertedData = [];
private $rows = [];
private $originalRows = [];
private $database;

public function __construct ()
{ // function __construct
global $io;
if ($io->database->tableEnabled ($this))
$this->database = $io->database;
} // function __construct

public function insert ($data)
{ // function insert

if ($this->database)
{ // insert into database
$id = $this->database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$id] = $data;
$this->originalRows[$id] = $data;

return $id;
} // insert into database

return 0;
} // function insert

public function &openById ($id)
{ // function &
if (isset ($this->rows[$id]))
{ // row found
$row = &$this->rows[$id];
return $row;
} // row found

if (is_int ($id) and $this->database)
{ // open from database
$rows = $this->database->select ($this, array ('id' => $id));
if ($rows)
{ // row found
$data = $rows[0];
$this->rows[$id] = $data;
$this->originalRows[$id] = $data;
$return = &$this->rows[$id];
return $return;
} // row found
} // open from database
$row = [];
return $row;
} // function &

public function clear ()
{ // function clear
foreach ($this->database->select ($this, ['updated' => '< ' . strval(TIME - SYSTEM_SESSION_TTL)]) as $row)
{ // rows found
if (isset ($row['id']) and $row['id'])
$this->database->delete ($this, ['id' => $row['id']]);
} // rows found
} // function clear

public function delete ($id)
{ // function delete
if (isset ($this->originalRows[$id]))
{ // session in database
$this->database->delete ($this, array ('id' => $id));
$this->rows[$id] = [];
unset ($this->originalRows[$id]);
} // session in database
} // function delete

public function close ()
{ // function close
foreach ($this->originalRows as $id => $originalRow)
{ // each row
if ($this->rows[$id] != $originalRow)
$this->database->update ($this, $this->rows[$id], $originalRow);
} // each row
} // function close

} // class eclStore_session

?>