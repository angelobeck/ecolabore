<?php

class eclStore_systemExtras
{ // class eclStore_systemExtras

public $name = 'system_extras';

public $fields = [
// Indexing
'mode' => 'tinyint', 
'domain_id' => 'mediumint', 
'user_id' => 'mediumint', 
'parent_id' => 'mediumint', 
'master_id' => 'mediumint', 
'id' => 'primary_key', 
// Class identifiers
'folder' => 'name',
'name' => 'name', 
'status' => 'mediumint', 
'access' => 'tinyint', 
// Dates
'created' => 'time', 
'updated' => 'time', 
// Contents
'text' => 'array', 
'local' => 'array', 
'extras' => 'array', 
'html' => 'binary'
];

// Index
public $index = [
'system_find_extras' => ['mode', 'folder', 'name'], 
];

public $indexByMode = [];
public $chargedMode = [];
private $rows = [];
private $originalRows = [];
private $deletedRows = [];
private $notFound = [];
private $database = false;

public $lastInsertedData = [];

public function __construct ()
{ // function __construct
global $io;
if ($io->database->tableEnabled ($this))
$this->database = $io->database;
} // function __construct

public function indexFoundRows ($rows, $domainName=0)
{ // function indexFoundRows
$found = [];
foreach ($rows as $data)
{ // each row
if ($data['access'] == 255)
continue;

$mode = $data['mode'];
$id = $data['id'];
if (!isset ($this->deletedRows[$id]))
{ // row not deleted
if (!isset ($this->rows[$id]))
{ // row not indexed
$this->rows[$id] = $data;
$this->originalRows[$id] = $data;
$this->indexByMode[$mode][$data['folder']][$data['name']][$id] = $id;
$found[] = $data;
} // row not indexed
else
$found[] = $this->rows[$id];
} // row not deleted
} // each row
return $found;
} // function indexFoundRows

public function insert (&$data)
{ // function insert
if (!$this->database)
return 0;

if(!isset ($data['mode']))
return 0;

$mode = $data['mode'];

$id = $this->database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$id] = $data;
$this->originalRows[$id] = $data;
$this->indexByMode[$mode][$data['folder']][$data['name']][$id] = $id;
$this->lastInsertedData = $data;
return $id;
} // function insert

public function &openById ($id, $access=4)
{ // function &
if (!isset ($this->rows[$id]))
{ // open
if ($this->database)
$this->indexFoundRows ($this->database->select ($this, ['id' => $id]));
} // open

$found = [];
if (isset ($this->rows[$id]))
{ // found
$found = &$this->rows[$id];

if ($found['access'] <= $access)
return $found;
} // found
return $found;
} // function &

public function search ($where, $access=4)
{ // function search
if ($this->database)
return $this->indexFoundRows ($this->database->select ($this, $where));

return [];
} // function search

public function delete ($id)
{ // function delete
if (isset ($this->rows[$id]))
{ // remove from index
$data = $this->rows[$id];
$this->deletedRows[$id] = $id;
$this->rows[$id] = [];
unset ($this->originalRows[$id]);
unset ($this->indexByMode[$mode][$data['folder']][$data['name']][$id]);
} // remove from index
if ($this->database)
{ // delete from database
$this->database->delete ($this, array ('id' => $id));
} // delete from database
} // function delete

public function deleteMode ($mode)
{ // function deleteMode
if ($this->database)
$this->database->delete($this, ['mode' => $mode]);
} // function deleteMode

public function close ()
{ // function close
foreach ($this->rows as $id => $data)
{ // each row
if ($this->originalRows[$id] != $data)
$this->database->update ($this, $data, $this->originalRows[$id]);
} // each row
$this->indexByMode = [];
$this->rows = [];
$this->originalRows = [];
$this->deletedRows = [];
$this->notFound = [];
} // function close

} // class eclStore_systemExtras

?>