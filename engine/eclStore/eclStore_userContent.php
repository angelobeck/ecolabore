<?php

class eclStore_userContent
{ // class eclStore_userContent

public $name = 'user_content';

public $fields = array (
// Indexing
'user_id' => 'mediumint', 
'mode' => 'tinyint', 
'parent_id' => 'mediumint', 
'master_id' => 'mediumint', 
'id' => 'primary_key', 
// Class identifiers
'name' => 'name', 
'marker' => 'tinyint', 
'access' => 'tinyint', 
// Dates
'created' => 'time', 
'updated' => 'time', 
'event_start' => 'time', 
'event_end' => 'time', 
'coments_last_update' => 'time', 
// More sort options
'index' => 'mediumint', 
'hits' => 'int', 
'value' => 'mediumint', 
'spotlight' => 'tinyint', 
// Contents
'text' => 'array', 
'local' => 'array', 
'flags' => 'array', 
'extras' => 'array', 
'links' => 'array', 
'keywords' => 'keywords'
);

// Index
public $index = array (
'user_find_children' => array ('user_id', 'mode', 'parent_id'), 
'user_find_name' => array ('user_id', 'name')
);

private $indexByName = [];
private $indexByParent = [];
private $chargedParents = [];
private $chargedMode = [];
private $rows = [];
private $originalRows = [];
private $deletedRows = [];
private $notFound = [];
private $database = false;
private $openedDatabases = [];

public function __construct ()
{ // function __construct
global $io;
if ($io->database->tableEnabled ($this))
$this->database = $io->database;
} // function __construct

public function getDatabase ($userName)
{ // function getDatabase
global $io;
if (!isset ($this->openedDatabases[$userName]))
{ // open database
$this->openedDatabases[$userName] = $io->sqlite->connect (FOLDER_PROFILES . $userName . '/.user.db');
} // open database
if ($this->openedDatabases[$userName]->tableEnabled ($this))
return $this->openedDatabases[$userName];

return false;
} // function getDatabase

public function indexFoundRows ($rows, $userName=0)
{ // function indexFoundRows
$found = [];
foreach ($rows as $data)
{ // each row
if (!$data['user_id'])
$data['user_id'] = $userName;
$userId = $data['user_id'];
$id = $data['id'];
if (!isset ($this->deletedRows[$userId][$id]))
{ // row not deleted
if (!isset ($this->rows[$userId][$id]))
{ // row not indexed
$this->rows[$userId][$id] = $data;
$this->originalRows[$userId][$id] = $data;
$this->indexByName[$userId][$data['name']] = $id;
$this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id] = $id;
$found[] = $data;
} // row not indexed
else
$found[] = $this->rows[$userId][$id];
} // row not deleted
} // each row
return $found;
} // function indexFoundRows

public function insert ($userId, &$data)
{ // function insert
global $io;
$data['user_id'] = $userId;
if (!isset ($data['parent_id']))
$data['parent_id'] = 0;
$data['index'] = count ($this->children ($userId, $data['mode'], $data['parent_id']));
if (!isset ($data['name']) or !strlen ($data['name']))
$data['name'] = 't' . strval (TIME);
$where = array ('user_id' => $userId, 'name' => $data['name']);

/*
* if ($this->database->select ($this, $where, ' LIMIT 1', array ('id')))
* { // prevent duplicated name
* $test = 0;
* do
* { // loop names
* $test ++;
* $name = $data['name'] . '-' . str_pad (strval ($test), 3, '0', STR_PAD_LEFT);
* $where = array ('user_id' => $userId, 'name' => $name);
* } // loop names
* while ($this->database->select ($this, $where, ' LIMIT 1', array ('id')));
* $data['name'] = $name;
* } // prevent duplicated names
*/

if (is_int ($userId) and $this->database)
{ // insert into database
$id = $this->database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$userId][$id] = $data;
$this->originalRows[$userId][$id] = $data;
$this->indexByName[$userId][$data['name']] = $id;
$this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id] = $id;
return $id;
} // insert into database
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
{ // from sqlite
$id = $database->insert ($this, $data);
$data['user_id'] = $userId;
$data['id'] = $id;
$this->rows[$userId][$id] = $data;
$this->originalRows[$userId][$id] = $data;
$this->indexByName[$userId][$data['name']] = $id;
$this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id] = $id;
return $id;
} // from sqlite
} // function insert

public function &open ($userId, $name, $access=4)
{ // function &
if (!isset ($this->indexByName[$userId][$name]))
{ // open
if (isset ($this->notFound[$userId][$name]))
{ // registered not found
$found = [];
return $found;
} // registered not found
if (is_int ($userId) and $this->database)
$this->indexFoundRows ($this->database->select ($this, array ('user_id' => $userId, 'name' => $name), ' LIMIT 1'));
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
$this->indexFoundRows ($database->select ($this, array ('name' => $name), ' LIMIT 1'), $userId);
} // open

$found = [];
if (isset ($this->indexByName[$userId][$name]))
{ // row found
$id = $this->indexByName[$userId][$name];
$found = &$this->rows[$userId][$id];
if ($found['access'] <= $access)
return $found;
} // row found
else
$this->notFound[$userId][$name] = true;
$empty = [];
return $empty;
} // function &

public function &openById ($userId, $id, $access=4)
{ // function &
if (!isset ($this->rows[$userId][$id]))
{ // open
if (is_int ($userId) and $this->database)
$this->indexFoundRows ($this->database->select ($this, array ('id' => $id)));
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
$this->indexFoundRows ($database->select ($this, array ('id' => $id)), $userId);
} // open

$found = [];
if (isset ($this->rows[$userId][$id]))
{ // found
$found = &$this->rows[$userId][$id];

if ($found['access'] <= $access)
return $found;
} // found
$empty = [];
return $empty;
} // function &

public function &openChild ($userId, $mode, $parentId, $name, $access=4)
{ // function &
if (!isset ($this->chargedParents[$userId][$mode][$parentId]))
{ // open
if (is_int ($userId) and $this->database)
{ // parents from database
$this->chargedParents[$userId][$mode][$parentId] = true;
$this->indexFoundRows ($this->database->select ($this, array (
'user_id' => $userId, 
'mode' => $mode, 
'parent_id' => $parentId
)));
} // parents from database
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
{ // parents from file
$this->indexFoundRows ($database->select ($this, array (
'mode' => $mode, 
'parent_id' => $parentId
)), $userId);
} // parents from file
} // open

if (isset ($this->indexByParent[$userId][$mode][$parentId]))
{ // children exists
foreach ($this->indexByParent[$userId][$mode][$parentId] as $id)
{ // each child
if ($this->rows[$userId][$id]['name'] == $name)
{ // found
$found = &$this->rows[$userId][$id];
if ($found['access'] <= $access)
return $found;
$empty = [];
return $empty;
} // found
} // each child
} // children exists

$found = [];
return $found;
} // function &

public function children ($userId, $mode, $parentId, $access=4, $max=0, $offset=0, $sort='index', $direction='asc')
{ // function children
if (!isset ($this->chargedParents[$userId][$mode][$parentId]))
{ // open
if (isset ($this->chargedMode[$userId][$mode]))
return [];

if (is_int ($userId) and $this->database)
{ // parents from database
$this->chargedParents[$userId][$mode][$parentId] = true;
$this->indexFoundRows ($this->database->select ($this, array (
'user_id' => $userId, 
'mode' => $mode, 
'parent_id' => $parentId
)));
} // parents from database
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
{ // parents from file
$this->chargedParents[$userId][$mode][$parentId] = true;
$this->indexFoundRows ($database->select ($this, array (
'mode' => $mode, 
'parent_id' => $parentId
)), $userId);
} // parents from database
} // open

if (isset ($this->indexByParent[$userId][$mode][$parentId]))
{ // children exists
$sorted = [];
$rows = $this->rows[$userId];
foreach ($this->indexByParent[$userId][$mode][$parentId] as $id)
{ // each child
if ($rows[$id]['access'] <= $access)
$sorted[$rows[$id][$sort]][] = $rows[$id];
} // each child

if ($direction == 'desc')
krsort ($sorted);
else
ksort ($sorted);

if ($max == 0)
$max = count ($this->indexByParent[$userId][$mode][$parentId]);
else
$max += $offset;

$list = [];
$i = - 1;
foreach ($sorted as $doubled)
{ // each doubled
foreach ($doubled as $item)
{ // each item
$i++;
if ($i >= $offset and $i < $max)
$list[] = $item;
elseif ($i > $max)
break 2;
} // each item
} // each doubled

return $list;
} // children exists
return [];
} // function children

public function childrenNames ($userId, $mode, $parentId, $access=4, $max=0, $offset=0, $sort='index', $direction='asc')
{ // function childrenNames
$children = $this->children ($userId, $mode, $parentId, $access, $max, $offset, $sort, $direction);
$names = [];
foreach ($children as $child)
{ // each child
$names[] = $child['name'];
} // each child
return $names;
} // function childrenNames

public function mode ($userId, $mode)
{ // function mode
if (!isset ($this->chargedMode[$userId][$mode]))
{ // open
if (is_int ($userId) and is_object ($this->database))
{ // mode from database
$this->chargedMode[$userId][$mode] = $this->indexFoundRows ($this->database->select ($this, array (
'user_id' => $userId, 
'mode' => $mode
)));
} // mode from database
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
{ // mode from file
$this->chargedMode[$userId][$mode] = $this->indexFoundRows ($database->select ($this, array (
'mode' => $mode
)), $userId);
} // mode from file
else
$this->chargedMode[$userId][$mode] = [];

// index chargedParents
if (!isset ($this->chargedParents[$userId]))
$this->chargedParents[$userId] = [];
if (!isset ($this->chargedParents[$userId][$mode]))
$this->chargedParents[$userId][$mode] = [];

foreach ($this->chargedMode[$userId][$mode] as $row)
{ // each row
if (!$row or !isset ($row['id']) or !isset ($row['parent_id']))
continue;

if (!isset ($this->chargedParents[$userId][$mode][$row['parent_id']]) or !is_array ($this->chargedParents[$userId][$mode][$row['parent_id']]))
$this->chargedParents[$userId][$mode][$row['parent_id']] = [];

$this->chargedParents[$userId][$mode][$row['parent_id']][$row['id']] = true;
$this->indexByParent[$userId][$mode][$row['parent_id']][$row['id']] = $row['id'];
} // each row
} // open

return $this->chargedMode[$userId][$mode];
} // function mode

public function findMarker ($userId, $marker, $mode=MODE_SECTION)
{ // function findMarker
static $markers = [];
if (!isset ($markers[$userId][$mode]))
{ // charge mode
if (!isset ($markers[$userId]))
$markers[$userId] = [];
if (!isset ($markers[$userId][$mode]))
$markers[$userId][$mode] = [];
foreach ($this->mode ($userId, $mode) as $data)
{ // each row
if (!isset ($markers[$userId][$mode][$data['marker']]))
$markers[$userId][$mode][$data['marker']] = $data['id'];
} // each row
} // charge mode
if (isset ($markers[$userId][$mode][$marker]))
return $markers[$userId][$mode][$marker];

return false;
} // function findMarker

public function search ($where, $access=4, $max=0, $offset=0, $sort='created', $direction='desc')
{ // function search
if ( (!isset ($where['user_id']) or is_int ($where['user_id'])) and is_object ($this->database))
{ // search on database
$rows = $this->indexFoundRows ($this->database->select ($this, $where));
} // search on database
elseif (isset ($where['user_id']) and is_string ($where['user_id']) and $database = $this->getDatabase ($where['user_id']))
{ // search on file
unset ($where['user_id']);
$rows = $this->indexFoundRows ($database->select ($this, $where), $where['user_id']);
} // search on file
else
$rows = [];

if ($rows)
{ // children exists
$sorted = [];
foreach ($rows as $row)
{ // each child
if ($row['access'] <= $access)
$sorted[$row[$sort]][] = $row;
} // each child

if ($direction == 'desc')
krsort ($sorted);
else
ksort ($sorted);

if ($max == 0)
$max = count ($rows);
else
$max += $offset;

$list = [];
$i = - 1;
foreach ($sorted as $doubled)
{ // each doubled
foreach ($doubled as $item)
{ // each item
$i++;
if ($i >= $offset and $i < $max)
$list[] = $item;
elseif ($i > $max)
break 2;
} // each item
} // each doubled

return $list;
} // children exists
return [];
} // function search

public function childrenReindex ($userId, $mode, $parentId)
{ // function childrenReindex
foreach ($this->children ($userId, $mode, $parentId) as $index => $child)
{ // each child
$this->rows[$userId][$child['id']]['index'] = $index;
} // each child
} // function childrenReindex

public function pathway ($userId, $name)
{ // function pathway
global $store;
$pathway = [];
if (intval ($name))
$data = $this->openById ($userId, $name);
else
$data = $this->open ($userId, $name);
if (!$data)
return false;

$id = $data['id'];
do
{ // loop levels
if (isset ($this->rows[$userId][$id]))
$data = $this->rows[$userId][$id];
else
$data = $this->openById ($userId, $id);
if (!$data)
return false;
array_unshift ($pathway, $data['name']);
$id = $data['parent_id'];
if (!$id and $data['mode'] != MODE_SECTION and $data['marker'])
{ // find special section
$id = $this->findMarker ($userId, $data['marker']);
if (!$id)
return false;
} // find special section
} // loop levels
while ($id);
array_unshift ($pathway, SYSTEM_PROFILES_URI, $store->user->getName ($userId));

return $pathway;
} // function pathway

public function delete ($userId, $id)
{ // function delete
if (isset ($this->rows[$userId][$id]))
{ // remove from index
$data = $this->rows[$userId][$id];
$this->deletedRows[$userId][$id] = $id;
$this->rows[$userId][$id] = [];
unset ($this->originalRows[$userId][$id]);
unset ($this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id]);
unset ($this->indexByName[$userId][$data['name']][$id]);
} // remove from index
if (is_int ($userId) and $this->database)
{ // delete from database
$this->database->delete ($this, array ('id' => $id));
} // delete from database
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
{ // delete from file
$database->delete ($this, array ('id' => $id));
} // delete from file
} // function delete

public function close ()
{ // function close
foreach ($this->originalRows as $userId => $userRows)
{ // each user
foreach ($userRows as $id => $originalRow)
{ // each row
if ($this->rows[$userId][$id] != $originalRow)
{ // data changed
if (is_int ($userId))
$this->database->update ($this, $this->rows[$userId][$id], $originalRow);
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
$database->update ($this, $this->rows[$userId][$id], $originalRow);
} // data changed
} // each row
} // each user

$this->indexByName = [];
$this->indexByParent = [];
$this->chargedParents = [];
$this->rows = [];
$this->originalRows = [];
$this->deletedRows = [];
$this->notFound = [];
} // function close

} // class eclStore_userContent

?>