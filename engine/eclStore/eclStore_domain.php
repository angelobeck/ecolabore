<?php

class eclStore_domain
{ // class eclStore_domain

public $name = 'domain';
// Describe fields structure
public $fields = array (
// Indexing
'id' => 'primary_key', 
// Class identifiers
'name' => 'name', 
'aliase' => 'name', 
'status' => 'tinyint'
);

public $insertedData = [];
private $rows = [];
private $originalRows = [];
private $indexByName = [];
private $database = false;

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
$this->indexByName[$data['name']] = $id;
return $id;
} // insert into database
return $data['name'];
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

$file = FOLDER_DOMAINS . $name . '/.domain.db';
if (is_file ($file))
{ // file found
$row = array (
'id' => $name, 
'status' => 2
);
$this->rows[$name] = &$row;
$this->indexByName[$name] = $name;
return $row;
} // file found

$row = [];
return $row;
} // function &

public function &openById ($id)
{ // function &
if (!isset ($this->rows[$id]))
{ // open
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

$this->rows[$id] = [];
$this->indexByName[$id] = $id;
} // open

$return = &$this->rows[$id];
return $return;
} // function &


public function &openByAliase ($aliase)
{ // function &
$found = [];
foreach ($this->rows as $id => $data)
{ // search rows in cache
if ($data['aliase'] != $aliase)
continue;

$found = &$this->rows[$data['id']];
return $found;
} // search rows in cache

if ($this->database)
{ // open from database
$rows = $this->database->select ($this, array ('aliase' => $aliase));
if ($rows)
{ // row found
$data = $rows[0];
$id = $data['id'];
$this->rows[$id] = $data;
$this->originalRows[$id] = $data;
$this->indexByName[$data['name']] = $id;
$return = &$this->rows[$id];
return $return;
} // row found
} // open from database

$return = [];
return $return;
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

return '';
} // function getName

public function getStatus ($name)
{ // function getStatus
if (!isset ($this->indexByName[$name]))
$this->open ($name);

if (!isset ($this->indexByName[$name]))
return 0;

$id = $this->indexByName[$name];
$row = &$this->rows[$id];

return $row['status'];
} // function getStatus

public function getAliase ($name)
{ // function getAliase
static $aliases = [];
if (isset ($aliases[$name]))
return $aliases[$name];

if (!isset ($this->indexByName[$name]))
$this->open ($name);

if (!isset ($this->indexByName[$name]) or $this->indexByName[$name] == $name)
{ // not found
$aliases[$name] = false;
return false;
} // not found

$id = $this->indexByName[$name];
$row = $this->rows[$id];

if (isset ($row['aliase']) and strlen ($row['aliase']))
$aliases[$name] = $row['aliase'];
else
$aliases[$name] = false;

return $aliases[$name];
} // function getAliase

public function childrenNames ()
{ // function childrenNames
$names = [];
foreach (scandir (FOLDER_DOMAINS) as $dirName)
{ // each directory
if ($dirName[0] != '.')
$names[] = $dirName;
} // each directory
return $names;
} // function childrenNames

public function delete ($id)
{ // function delete
if (isset ($this->originalRows[$id]))
{ // domain in database
$this->database->delete ($this, array ('id' => $id));
$this->rows[$id] = [];
unset ($this->originalRows[$id]);
} // domain in database
} // function delete

public function close ()
{ // function close
foreach ($this->originalRows as $id => $originalRow)
{ // each row
if ($this->rows[$id] != $originalRow)
$this->database->update ($this, $this->rows[$id], $originalRow);
} // each row
} // function close

} // class eclStore_domain

?>