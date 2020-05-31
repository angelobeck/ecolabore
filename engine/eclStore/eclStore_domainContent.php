<?php

class eclStore_domainContent
{ // class eclStore_domainContent

public $name = 'domain_content';

public $fields = array (
// Indexing
'domain_id' => 'mediumint', 
'mode' => 'tinyint', 
'parent_id' => 'mediumint', 
'master_id' => 'mediumint', 
'id' => 'primary_key', 
// Class identifiers
'name' => 'name', 
'marker' => 'tinyint', 
'status' => 'tinyint', 
'access' => 'tinyint', 
'subscription_id' => 'mediumint', 
'owner_id' => 'mediumint', 
'place_id' => 'mediumint', 
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
'domain_find_children' => array ('domain_id', 'mode', 'parent_id'), 
'domain_find_name' => array ('domain_id', 'name'), 
'domain_find_owner' => array ('owner_id', 'mode')
);

private $indexByName = [];
public $indexByParent = [];
public $chargedParents = [];
public $chargedMode = [];
private $rows = [];
private $originalRows = [];
private $deletedRows = [];
private $notFound = [];
private $database = false;
private $openedDatabases = [];

public $lastInsertedData = [];

public function __construct ()
{ // function __construct
global $io;
if ($io->database->tableEnabled ($this))
$this->database = $io->database;
} // function __construct

public function getDatabase ($domainName)
{ // function getDatabase
global $io;
if (!isset ($this->openedDatabases[$domainName]))
{ // open database
$this->openedDatabases[$domainName] = $io->sqlite->connect (FOLDER_DOMAINS . $domainName . '/.domain.db');
} // open database
if ($this->openedDatabases[$domainName]->tableEnabled ($this))
return $this->openedDatabases[$domainName];

return false;
} // function getDatabase

public function indexFoundRows ($rows, $domainName=0)
{ // function indexFoundRows
$found = [];
foreach ($rows as $data)
{ // each row
if ($data['name'][0] == ':')
continue;

if (!$data['domain_id'])
$data['domain_id'] = $domainName;
$domainId = $data['domain_id'];
$id = $data['id'];
if (!isset ($this->deletedRows[$domainId][$id]))
{ // row not deleted
if (!isset ($this->rows[$domainId][$id]))
{ // row not indexed
$this->rows[$domainId][$id] = $data;
$this->originalRows[$domainId][$id] = $data;
$this->indexByName[$domainId][$data['name']] = $id;
$this->indexByParent[$domainId][$data['mode']][$data['parent_id']][$id] = $id;
$found[] = $data;
} // row not indexed
else
$found[] = $this->rows[$domainId][$id];
} // row not deleted
} // each row
return $found;
} // function indexFoundRows

public function insert ($domainId, &$data)
{ // function insert
global $io;
if (is_int ($domainId) and $this->database)
$database = $this->database;
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
null;
else
return 0;

$data['domain_id'] = $domainId;
if (!isset ($data['parent_id']))
$data['parent_id'] = 0;
$data['index'] = count ($this->children ($domainId, $data['mode'], $data['parent_id']));
if (!isset ($data['name']) or !strlen ($data['name']))
$data['name'] = 't' . strval (TIME);
$where = array ('domain_id' => $domainId, 'name' => $data['name']);

if ($database->select ($this, $where, ' LIMIT 1', array ('id')))
{ // prevent duplicated name
$test = 0;
do
{ // loop names
$test++;
$name = $data['name'] . '-' . str_pad (strval ($test), 3, '0', STR_PAD_LEFT);
$where = array ('domain_id' => $domainId, 'name' => $name);
} // loop names
while ($database->select ($this, $where, ' LIMIT 1', array ('id')));
$data['name'] = $name;
} // prevent duplicated names

$id = $database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$domainId][$id] = $data;
$this->originalRows[$domainId][$id] = $data;
$this->indexByName[$domainId][$data['name']] = $id;
$this->indexByParent[$domainId][$data['mode']][$data['parent_id']][$id] = $id;
$this->lastInsertedData = $data;
return $id;
} // function insert

public function &open ($domainId, $name, $access=4)
{ // function &
if (!isset ($this->indexByName[$domainId][$name]))
{ // open
if (isset ($this->notFound[$domainId][$name]))
{ // registered not found
$found = [];
return $found;
} // registered not found
if (is_int ($domainId) and $this->database)
$this->indexFoundRows ($this->database->select ($this, array ('domain_id' => $domainId, 'name' => $name), ' LIMIT 1'));
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
$this->indexFoundRows ($database->select ($this, array ('name' => $name), ' LIMIT 1'), $domainId);
} // open

$found = [];
if (isset ($this->indexByName[$domainId][$name]))
{ // row found
$id = $this->indexByName[$domainId][$name];
$found = &$this->rows[$domainId][$id];
if ($found['access'] <= $access)
return $found;
} // row found
else
$this->notFound[$domainId][$name] = true;
$empty = [];
return $empty;
} // function &

public function &openById ($domainId, $id, $access=4)
{ // function &
if (!isset ($this->rows[$domainId][$id]))
{ // open
if (is_int ($domainId) and $this->database)
$this->indexFoundRows ($this->database->select ($this, array ('domain_id' => $domainId, 'id' => $id)));
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
$this->indexFoundRows ($database->select ($this, array ('id' => $id)), $domainId);
} // open

$found = [];
if (isset ($this->rows[$domainId][$id]))
{ // found
$found = &$this->rows[$domainId][$id];

if ($found['access'] <= $access)
return $found;
} // found
$empty = [];
return $empty;
} // function &

public function &openChild ($domainId, $mode, $parentId, $name, $access=4)
{ // function &
if (!isset ($this->chargedParents[$domainId][$mode][$parentId]))
{ // open
if (is_int ($domainId) and $this->database)
{ // parents from database
$this->chargedParents[$domainId][$mode][$parentId] = true;
$this->indexFoundRows ($this->database->select ($this, array (
'domain_id' => $domainId, 
'mode' => $mode, 
'parent_id' => $parentId
)));
} // parents from database
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
{ // parents from file
$this->indexFoundRows ($database->select ($this, array (
'mode' => $mode, 
'parent_id' => $parentId
)), $domainId);
} // parents from file
} // open

if (isset ($this->indexByParent[$domainId][$mode][$parentId]))
{ // children exists
foreach ($this->indexByParent[$domainId][$mode][$parentId] as $id)
{ // each child
if ($this->rows[$domainId][$id]['name'] == $name)
{ // found
$found = &$this->rows[$domainId][$id];
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

public function children ($domainId, $mode, $parentId, $access=4, $max=0, $offset=0, $sort='index', $direction='asc')
{ // function children
if (!isset ($this->chargedParents[$domainId][$mode][$parentId]))
{ // open
if (isset ($this->chargedMode[$domainId][$mode]))
return [];

if (is_int ($domainId) and $this->database)
{ // parents from database
$this->chargedParents[$domainId][$mode][$parentId] = true;
$this->indexFoundRows ($this->database->select ($this, array (
'domain_id' => $domainId, 
'mode' => $mode, 
'parent_id' => $parentId
)));
} // parents from database
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
{ // parents from file
$this->chargedParents[$domainId][$mode][$parentId] = true;
$this->indexFoundRows ($database->select ($this, array (
'mode' => $mode, 
'parent_id' => $parentId
)), $domainId);
} // parents from database
} // open

if (isset ($this->indexByParent[$domainId][$mode][$parentId]))
{ // children exists
$sorted = [];
$rows = $this->rows[$domainId];
foreach ($this->indexByParent[$domainId][$mode][$parentId] as $id)
{ // each child
if ($rows[$id]['access'] <= $access)
$sorted[$rows[$id][$sort]][] = $rows[$id];
} // each child

if ($direction == 'desc')
krsort ($sorted);
else
ksort ($sorted);

if ($max == 0)
$max = count ($this->indexByParent[$domainId][$mode][$parentId]);
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

public function childrenNames ($domainId, $mode, $parentId, $access=4, $max=0, $offset=0, $sort='index', $direction='asc')
{ // function childrenNames
$children = $this->children ($domainId, $mode, $parentId, $access, $max, $offset, $sort, $direction);
$names = [];
foreach ($children as $child)
{ // each child
$names[] = $child['name'];
} // each child
return $names;
} // function childrenNames

public function mode ($domainId, $mode)
{ // function mode
if (!isset ($this->chargedMode[$domainId][$mode]))
{ // open
if (is_int ($domainId) and is_object ($this->database))
{ // mode from database
$this->chargedMode[$domainId][$mode] = $this->indexFoundRows ($this->database->select ($this, array (
'domain_id' => $domainId, 
'mode' => $mode
)));
} // mode from database
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
{ // mode from file
$this->chargedMode[$domainId][$mode] = $this->indexFoundRows ($database->select ($this, array (
'mode' => $mode
)), $domainId);
} // mode from file
else
$this->chargedMode[$domainId][$mode] = [];

// index chargedParents
if (!isset ($this->chargedParents[$domainId]))
$this->chargedParents[$domainId] = [];
if (!isset ($this->chargedParents[$domainId][$mode]))
$this->chargedParents[$domainId][$mode] = [];

foreach ($this->chargedMode[$domainId][$mode] as $row)
{ // each row
if (!$row or !isset ($row['id']) or !isset ($row['parent_id']))
continue;

if (!isset ($this->chargedParents[$domainId][$mode][$row['parent_id']]) or !is_array ($this->chargedParents[$domainId][$mode][$row['parent_id']]))
$this->chargedParents[$domainId][$mode][$row['parent_id']] = [];

$this->chargedParents[$domainId][$mode][$row['parent_id']][$row['id']] = true;
$this->indexByParent[$domainId][$mode][$row['parent_id']][$row['id']] = $row['id'];
} // each row
} // open

return $this->chargedMode[$domainId][$mode];
} // function mode

public function findMarker ($domainId, $marker, $mode=MODE_SECTION)
{ // function findMarker
static $markers = [];
if (!isset ($markers[$domainId][$mode]))
{ // charge mode
if (!isset ($markers[$domainId]))
$markers[$domainId] = [];
if (!isset ($markers[$domainId][$mode]))
$markers[$domainId][$mode] = [];
foreach ($this->mode ($domainId, $mode) as $data)
{ // each row
if ($data['access'] > 30)
continue;
if (!isset ($markers[$domainId][$mode][$data['marker']]))
$markers[$domainId][$mode][$data['marker']] = $data['id'];
} // each row
} // charge mode
if (isset ($markers[$domainId][$mode][$marker]))
return $markers[$domainId][$mode][$marker];

return false;
} // function findMarker

public function search ($where, $access=4, $max=0, $offset=0, $sort='created', $direction='desc')
{ // function search
if ( (!isset ($where['domain_id']) or is_int ($where['domain_id'])) and is_object ($this->database))
{ // search on database
$rows = $this->indexFoundRows ($this->database->select ($this, $where));
} // search on database
elseif (isset ($where['domain_id']) and is_string ($where['domain_id']) and $database = $this->getDatabase ($where['domain_id']))
{ // search on file
unset ($where['domain_id']);
$rows = $this->indexFoundRows ($database->select ($this, $where), $where['domain_id']);
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

public function childrenReindex ($domainId, $mode, $parentId)
{ // function childrenReindex
foreach ($this->children ($domainId, $mode, $parentId) as $index => $child)
{ // each child
$this->rows[$domainId][$child['id']]['index'] = $index;
} // each child
} // function childrenReindex

public function pathway ($domainId, $name)
{ // function pathway
global $store;
$pathway = [];

if (is_int ($name))
$data = $this->openById ($domainId, $name);
else
$data = $this->open ($domainId, $name);
if (!$data)
return false;

$id = $data['id'];
do
{ // loop levels
if (isset ($this->rows[$domainId][$id]))
$data = $this->rows[$domainId][$id];
else
$data = $this->openById ($domainId, $id);
if (!$data)
return false;

if ($data['parent_id'] != 0 or $data['mode'] != MODE_SECTION or !isset ($data['flags']['section_type']) or $data['flags']['section_type'] != 'menu')
array_unshift ($pathway, $data['name']);
$id = $data['parent_id'];
if ($id == 1)
$id = 0;
if (!$id and $data['mode'] != MODE_SECTION and $data['marker'])
{ // find special section
$id = $this->findMarker ($domainId, $data['marker']);
if (!$id)
return false;
} // find special section
} // loop levels
while ($id);
array_unshift ($pathway, $store->domain->getName ($domainId));

return $pathway;
} // function pathway

public function delete ($domainId, $id)
{ // function delete
if (defined ('TRACKING_REMOVED_PAGES') and TRACKING_REMOVED_PAGES)
{ // do not remove page
$data = &$this->openById ($domainId, $id);
$data['access'] = 255;
if (strlen ($data['name']) == 32)
$data['name'] = substr ($data['name'],  - 2);
$data['name'] = ':' . $data['name'];
return;
} // do not remove page

if (isset ($this->rows[$domainId][$id]))
{ // remove from index
$data = $this->rows[$domainId][$id];
$this->deletedRows[$domainId][$id] = $id;
$this->rows[$domainId][$id] = [];
unset ($this->originalRows[$domainId][$id]);
unset ($this->indexByParent[$domainId][$data['mode']][$data['parent_id']][$id]);
unset ($this->indexByName[$domainId][$data['name']][$id]);
} // remove from index
if (is_int ($domainId) and $this->database)
{ // delete from database
$this->database->delete ($this, array ('id' => $id));
} // delete from database
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
{ // delete from file
$database->delete ($this, array ('id' => $id));
} // delete from file
} // function delete

public function export ($domainId, $fileName)
{ // function export
$where = array ('domain_id' => $domainId);
$select = array ('id', 'name');
$rows = $this->database->select ($this, $where, '', $select);
$index = 0;
$names = [];
foreach ($rows as $row)
{ // each row
if ($row['name'])
$names[$row['id']] = $row['name'];
else
{ // create name
$index++;
$names[$row['id']] = '-untitled-' . $index;
} // create name
} // each row

unset ($rows);
$file = fopen ($fileName, 'w+b');
$header = true;
$rows = $this->database->select ($this, $where);
foreach ($rows as $row)
{ // each row
$data = [];
foreach ($row as $key => $value)
{ // each field
if (is_int ($key))
continue;

switch ($key)
{ // switch key
case 'id':
case 'domain_id':
break;

case 'parent_id':
case 'master_id':
case 'owner_id':
case 'place_id':
if ($value === 0)
$data[substr ($key, 0,  - 3)] = '""';
else
$data[substr ($key, 0,  - 3)] = QUOT . $names[$value] . QUOT;
break;

default:
if (is_int ($value))
$data[$key] = strval ($value);
elseif (is_string ($value))
$data[$key] = QUOT . $value . QUOT;
elseif (is_array ($value) and $value)
$data[$key] = QUOT . $this->database->stringToDatabase (serialize ($value)) . QUOT;
else
$data[$key] = '""';
} // switch key
} // each field
if ($header)
{ // create header
$buffer = implode ("\t", array_keys ($data)) . CRLF;
fwrite ($file, $buffer);
} // create header
$buffer = implode ("\t", $data) . CRLF;
fwrite ($file, $buffer);
$header = false;
} // each row
fclose ($file);
} // function export

public function import ($domainId, $fileName)
{ // function import
$string = file_get_contents ($fileName);
$rows = [];
$names = [];
$header = true;
foreach (explode (CRLF, $string) as $line)
{ // each row
if ($header)
{ // header
$headers = explode ("\t", $line);
$header = false;
continue;
} // header

foreach (explode ("\t", $line) as $index => $field)
{ // each field
$key = $headers[$index];
switch ($key)
{ // switch column name
case 'parent':
case 'master':
case 'owner':
case 'place':
break;
} // switch column name
} // each field
} // each row
} // function import

public function specialSelect ($domainId, $where, $limit='', $returnRows=false)
{ // function specialSelect
if ( (!isset ($where['domain_id']) or is_int ($where['domain_id'])) and is_object ($this->database))
return $this->database->select ($this, $where, $limit, $returnRows);

if (isset ($where['domain_id']) and is_string ($where['domain_id']) and $database = $this->getDatabase ($where['domain_id']))
{ // search on file
unset ($where['domain_id']);
return $database->select ($this, $where, $limit, $returnRows);
} // search on file
return [];
} // function specialSelect

public function getUserSubscription ($document)
{ // function getUserSubscription
if (!$document->domain->domainId)
return false;
if (!$document->user->userId)
return false;

$where = array (
'domain_id' => $document->domain->domainId, 
'mode' => MODE_SUBSCRIPTION, 
'owner_id' => $document->user->userId
);
$rows = $this->search ($where);
if (!$rows)
return false;

return $document->domain->child ('-subscriptions')->child ($rows[0]['name']);
} // function getUserSubscription

public function close ()
{ // function close
foreach ($this->originalRows as $domainId => $domainRows)
{ // each domain
foreach ($domainRows as $id => $originalRow)
{ // each row
if ($this->rows[$domainId][$id] != $originalRow)
{ // data changed
if (is_int ($domainId))
$this->database->update ($this, $this->rows[$domainId][$id], $originalRow);
elseif (is_string ($domainId) and $database = $this->getDatabase ($domainId))
$database->update ($this, $this->rows[$domainId][$id], $originalRow);
} // data changed
} // each row
} // each domain

$this->indexByName = [];
$this->indexByParent = [];
$this->chargedParents = [];
$this->rows = [];
$this->originalRows = [];
$this->deletedRows = [];
$this->notFound = [];
} // function close

} // class eclStore_domainContent

?>