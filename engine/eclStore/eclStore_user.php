<?php

class eclStore_user
{ // class eclStore_user

public $name = 'user';

// Describe fields structure
public $fields = array (
// Indexing
'id' => 'primary_key', 
// Class identifiers
'name' => 'name', 
'mail' => 'hash', 
'phone' => 'hash', 
'document' => 'hash', 
'password' => 'password', 
'status' => 'tinyint'
);

public $insertedData = [];
private $rows = [];
private $originalRows = [];
private $indexByName = [];
private $database;

public function __construct ()
{ // function __construct
global $io;
if ($io->database->tableEnabled ($this))
$this->database = $io->database;
} // function __construct

public function insert ($data)
{ // function insert
global $io;
if (isset ($data['name'][0]) and !is_dir (FOLDER_PROFILES))
mkdir (FOLDER_PROFILES);
if (isset ($data['name'][0]) and !is_dir (FOLDER_PROFILES . $data['name']))
mkdir (FOLDER_PROFILES . $data['name']);

if ($this->database)
{ // insert into database
$id = $this->database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$id] = $data;
$this->originalRows[$id] = $data;
$this->indexByName[$data['name']] = $id;
return $id;
} // insert into database

return 0;
} // function insert

public function &open ($name)
{ // function &
global $io;
if (isset ($this->indexByName[$name]))
{ // row found
$id = $this->indexByName[$name];
$return = &$this->rows[$id];
return $return;
} // row found

if ($this->database)
{ // open from database
$rows = $this->database->select ($this, array ('name' => $name));
if ($rows)
{ // row found
$data = $rows[0];
$id = $data['id'];
$this->rows[$id] = $data;
$this->originalRows[$id] = $data;
$this->indexByName[$name] = $id;
$return = &$this->rows[$id];
return $return;
} // row found
} // open from database

$return = [];
return $return;
} // function &

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
$this->indexByName[$data['name']] = $id;
$return = &$this->rows[$id];
return $return;
} // row found
} // open from database
$row = [];
return $row;
} // function &

public function getId ($name)
{ // function getId
if (!isset ($this->indexByName[$name]))
$this->open ($name);
if (isset ($this->indexByName[$name]))
return $this->indexByName[$name];
else
return false;
} // function getId

public function getName ($id)
{ // function getName
if (!isset ($this->rows[$id]))
$this->openById ($id);
if (isset ($this->rows[$id]['name']))
return $this->rows[$id]['name'];
else
return false;
} // function getName

public function getStatus ($name)
{ // function getStatus
if (!isset ($this->indexByName[$name]))
$this->open ($name);
if (!isset ($this->indexByName[$name]))
return 0;

$id = $this->indexByName[$name];
$row = &$this->rows[$id];
if (!isset ($row['status']))
return 0;

return $row['status'];
} // function getStatus

public function childrenNames ()
{ // function childrenNames
$names = [];
if (!is_dir (FOLDER_PROFILES))
return [];
foreach (scandir (FOLDER_PROFILES) as $folder)
{ // each folder
if ($folder[0] == '.')
continue;

if (!is_dir (FOLDER_PROFILES . $folder))
continue;

if ($this->getStatus ($folder))
$names[] = $folder;
} // each folder

return $names;
} // function childrenNames

public function delete ($id)
{ // function delete
if (isset ($this->originalRows[$id]))
{ // user in database
$this->database->delete ($this, array ('id' => $id));
$this->rows[$id] = [];
unset ($this->originalRows[$id]);
} // user in database
} // function delete

public function close ()
{ // function close
foreach ($this->originalRows as $id => $originalRow)
{ // each row
if ($this->rows[$id] != $originalRow)
$this->database->update ($this, $this->rows[$id], $originalRow);
} // each row
} // function close

} // class eclStore_user

?>