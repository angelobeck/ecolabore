<?php

class eclStore_mail
{ // class eclStore_mail

public $name = 'mail';

public $fields = array (
// Indexing
'mode' => 'tinyint', 
'parent_id' => 'mediumint', 
'id' => 'primary_key', 
// Class identifiers
'marker' => 'tinyint', 
'status' => 'tinyint', 
'access' => 'tinyint', 
// Dates
'created' => 'time', 
'updated' => 'time', 
// More sort options
'index' => 'mediumint', 
'spotlight' => 'tinyint', 
// Contents
'from' => 'name', 
'to' => 'name', 
'text' => 'array', 
'local' => 'array', 
'keywords' => 'keywords'
);

// Index
public $index = array (
'mail_find_children' => array ('mode', 'parent_id')
);

public $indexByParent = [];
public $chargedParents = [];
public $chargedMode = [];
private $rows = [];
private $originalRows = [];
private $deletedRows = [];
private $database = false;
private $openedDatabases = [];

public $lastInsertedData = [];

public function getDatabase ($userName)
{ // function getDatabase
global $io;
if (!isset ($this->openedDatabases[$userName]))
{ // open database
$this->openedDatabases[$userName] = $io->sqlite->connect (PATH_PROFILES . $userName . '/.mail/.mail.db');
} // open database
if ($this->openedDatabases[$userName]->tableEnabled ($this))
return $this->openedDatabases[$userName];

return false;
} // function getDatabase

public function indexFoundRows ($userName, $rows)
{ // function indexFoundRows
$found = [];
foreach ($rows as $data)
{ // each row
$id = $data['id'];
if (!isset ($this->deletedRows[$userName][$id]))
{ // row not deleted
if (!isset ($this->rows[$userName][$id]))
{ // row not indexed
$this->rows[$userName][$id] = $data;
$this->originalRows[$userName][$id] = $data;
$this->indexByParent[$userName][$data['mode']][$data['parent_id']][$id] = $id;
$found[] = $data;
} // row not indexed
else
$found[] = $this->rows[$userName][$id];
} // row not deleted
} // each row
return $found;
} // function indexFoundRows

public function insert ($userName, &$data)
{ // function insert
global $io;
if (is_string ($userName) and $database = $this->getDatabase ($userName))
null;
else
return 0;

if (!isset ($data['parent_id']))
$data['parent_id'] = 0;
$id = $database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$userName][$id] = $data;
$this->originalRows[$userName][$id] = $data;
$this->indexByParent[$userName][$data['mode']][$data['parent_id']][$id] = $id;
$this->lastInsertedData = $data;
return $id;
} // function insert

public function &openById ($userName, $id, $access=4)
{ // function &
if (!isset ($this->rows[$userName][$id]))
{ // open
if (is_string ($userName) and $database = $this->getDatabase ($userName))
$this->indexFoundRows ($userName, $database->select ($this, array ('id' => $id)));
} // open

$found = [];
if (isset ($this->rows[$userName][$id]))
{ // found
$found = &$this->rows[$userName][$id];

if ($found['access'] <= $access)
return $found;
} // found
$empty = [];
return $empty;
} // function &

public function children ($userName, $mode, $parentId, $access=4)
{ // function children
if (!isset ($this->chargedParents[$userName][$mode][$parentId]))
{ // open
if (isset ($this->chargedMode[$userName][$mode]))
return [];

if (is_string ($userName) and $database = $this->getDatabase ($userName))
{ // parents from file
$this->chargedParents[$userName][$mode][$parentId] = true;
$this->indexFoundRows ($userName, $database->select ($this, array (
'mode' => $mode, 
'parent_id' => $parentId
)));
} // parents from database
} // open

$children = [];
if (isset ($this->indexByParent[$userName][$mode][$parentId]))
{ // children exists
foreach ($this->indexByParent[$userName][$mode][$parentId] as $id)
{ // each child
if ($this->rows[$userName][$id]['access'] <= $access)
$children[] = $this->rows[$userName][$id];
} // each child
} // children exists
return $children;
} // function children

public function mode ($userName, $mode)
{ // function mode
if (!isset ($this->chargedMode[$userName][$mode]))
{ // open
if (is_string ($userName) and $database = $this->getDatabase ($userName))
{ // mode from file
$this->chargedMode[$userName][$mode] = $this->indexFoundRows ($userName, $database->select ($this, array (
'mode' => $mode
)));
} // mode from file
else
$this->chargedMode[$userName][$mode] = [];

// index chargedParents
if (!isset ($this->chargedParents[$userName]))
$this->chargedParents[$userName] = [];
if (!isset ($this->chargedParents[$userName][$mode]))
$this->chargedParents[$userName][$mode] = [];

foreach ($this->chargedMode[$userName][$mode] as $row)
{ // each row
if (!$row or !isset ($row['id']) or !isset ($row['parent_id']))
continue;

if (!isset ($this->chargedParents[$userName][$mode][$row['parent_id']]) or !is_array ($this->chargedParents[$userName][$mode][$row['parent_id']]))
$this->chargedParents[$userName][$mode][$row['parent_id']] = [];

$this->chargedParents[$userName][$mode][$row['parent_id']][$row['id']] = true;
$this->indexByParent[$userName][$mode][$row['parent_id']][$row['id']] = $row['id'];
} // each row
} // open

return $this->chargedMode[$userName][$mode];
} // function mode

public function findMarker ($userName, $marker, $mode=MODE_SECTION)
{ // function findMarker
static $markers = [];
if (!isset ($markers[$userName][$mode]))
{ // charge mode
if (!isset ($markers[$userName]))
$markers[$userName] = [];
if (!isset ($markers[$userName][$mode]))
$markers[$userName][$mode] = [];
foreach ($this->mode ($userName, $mode) as $data)
{ // each row
if ($data['access'] > 30)
continue;
if (!isset ($markers[$userName][$mode][$data['marker']]))
$markers[$userName][$mode][$data['marker']] = $data['id'];
} // each row
} // charge mode
if (isset ($markers[$userName][$mode][$marker]))
return $markers[$userName][$mode][$marker];

return false;
} // function findMarker

public function search ($userName, $where, $access=4)
{ // function search
if ($database = $this->getDatabase ($userName))
return $this->indexFoundRows ($userName, $database->select ($this, $where));

return [];
} // function search

public function delete ($userName, $id)
{ // function delete
if (isset ($this->rows[$userName][$id]))
{ // remove from index
$data = $this->rows[$userName][$id];
$this->deletedRows[$userName][$id] = $id;
$this->rows[$userName][$id] = [];
unset ($this->originalRows[$userName][$id]);
unset ($this->indexByParent[$userName][$data['mode']][$data['parent_id']][$id]);
} // remove from index
if (is_string ($userName) and $database = $this->getDatabase ($userName))
{ // delete from file
$database->delete ($this, array ('id' => $id));
} // delete from file
} // function delete

public function close ()
{ // function close
foreach ($this->originalRows as $userName => $domainRows)
{ // each domain
foreach ($domainRows as $id => $originalRow)
{ // each row
if ($this->rows[$userName][$id] != $originalRow)
{ // data changed
if (is_string ($userName) and $database = $this->getDatabase ($userName))
$database->update ($this, $this->rows[$userName][$id], $originalRow);
} // data changed
} // each row
} // each domain

$this->indexByParent = [];
$this->chargedParents = [];
$this->rows = [];
$this->originalRows = [];
$this->deletedRows = [];
} // function close

} // class eclStore_mail

?>