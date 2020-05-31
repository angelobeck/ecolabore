<?php

class eclStore_userExtras
{ // class eclStore_userExtras

public $name = 'user_extras';

public $fields = array (
// Indexing
'user_id' => 'mediumint', 
'mode' => 'tinyint', 
'parent_id' => 'mediumint', 
'master_id' => 'mediumint', 
'id' => 'primary_key', 
// Class identifiers
'name' => 'name', 
'status' => 'tinyint', 
'access' => 'tinyint', 
'owner_id' => 'mediumint', 
// Dates
'created' => 'time', 
'updated' => 'time', 
// Contents
'text' => 'array', 
'local' => 'array', 
'extras' => 'array', 
'html' => 'binary'
);

// Index
public $index = array (
'user_find_extras' => array ('user_id', 'mode', 'parent_id'), 
'user_find_extras_owner' => array ('owner_id', 'mode')
);

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
if ($data['access'] == 255)
continue;

if (!$data['user_id'])
$data['user_id'] = $domainName;
$userId = $data['user_id'];
$id = $data['id'];
if (!isset ($this->deletedRows[$userId][$id]))
{ // row not deleted
if (!isset ($this->rows[$userId][$id]))
{ // row not indexed
$this->rows[$userId][$id] = $data;
$this->originalRows[$userId][$id] = $data;
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
if (is_int ($userId) and $this->database)
$database = $this->database;
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
null;
else
return 0;

$data['user_id'] = $userId;
if (!isset ($data['parent_id']))
$data['parent_id'] = 0;

$id = $database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$userId][$id] = $data;
$this->originalRows[$userId][$id] = $data;
$this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id] = $id;
$this->lastInsertedData = $data;
return $id;
} // function insert

public function &openById ($userId, $id, $access=4)
{ // function &
if (!isset ($this->rows[$userId][$id]))
{ // open
if (is_int ($userId) and $this->database)
$this->indexFoundRows ($this->database->select ($this, array ('user_id' => $userId, 'id' => $id)));
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

public function children ($userId, $mode, $parentId, $access=4)
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
{ // return found rows
$found = [];
foreach ($this->indexByParent[$userId][$mode][$parentId] as $id)
{ // each row
$found[] = $this->rows[$userId][$id];
} // each row
return $found;
} // return found rows
else
return [];
} // function children

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

public function search ($where, $access=4)
{ // function search
if ( (!isset ($where['user_id']) or is_int ($where['user_id'])) and is_object ($this->database))
{ // search on database
return $this->indexFoundRows ($this->database->select ($this, $where));
} // search on database
elseif (isset ($where['user_id']) and is_string ($where['user_id']) and $database = $this->getDatabase ($where['user_id']))
{ // search on file
unset ($where['user_id']);
return $this->indexFoundRows ($database->select ($this, $where), $where['user_id']);
} // search on file
else
return [];
} // function search

public function createVersion ($userId, &$from, $document)
{ // function createVersion
$lang = $document->lang;
$from['flags']['updated_' . $lang] = TIME;

$data = array (
'user_id' => $userId, 
'mode' => MODE_VERSION, 
'parent_id' => $from['id'], 
'name' => $document->lang, 
'owner_id' => $document->user->userId, 
'created' => TIME, 
'updated' => TIME, 
'text' => []
);
foreach (array_keys ($from['text']) as $field)
{ // each field
if (isset ($from['text'][$field][$lang]))
$data['text'][$field] = array ($lang => $from['text'][$field][$lang]);
} // each field

if (isset ($from['extras']))
{ // extras exists
$data['extras'] = [];
foreach ($from['extras'] as $target => $extras)
{ // each extra
list ($prefix) = explode ('_', $target);
switch ($prefix)
{ // switch prefix
case 'box':
if (isset ($from['extras'][$target]['text']))
{ // text exists
foreach ($from['extras'][$target]['text'] as $field => $value)
{ // each field
if (isset ($value[$lang]))
$data['extras'][$target]['text'][$field][$lang] = $value[$lang];
} // each field
} // text exists
if (isset ($from['extras'][$target]['local']))
$data['extras'][$target]['local'] = $from['extras'][$target]['local'];
} // switch prefix
} // each extra
} // extras exists

$this->insert ($userId, $data);
} // function createVersion

public function clearLanguage ($document)
{ // function clearLanguage
global $store;
$me = $document->application;
if (!isset ($me->data['text']['caption']) or count ($me->data['text']['caption']) < 2)
return;

if ($document->actions ('version', 'clear') and isset ($document->actions['version'][2]) and strlen ($document->actions['version'][2]) == 2)
{ // language clear
$lang = $document->actions['version'][2];

foreach (array_keys ($me->data['text']) as $field)
{ // each field
unset ($me->data['text'][$field][$lang]);
if (!$me->data['text'][$field])
unset ($me->data['text'][$field]);
} // each field

foreach (array_keys ($me->data['extras']) as $target)
{ // each target
list ($prefix) = explode ('_', $target, 2);
switch ($prefix)
{ // switch prefix
case 'box':
if (isset ($me->data['extras'][$field]['text']))
{ // text exists
foreach (array_keys ($me->data['extras'][$target]['text']) as $field)
{ // each field
unset ($me->data['extras'][$target]['text'][$field][$lang]);
if (!$me->data['extras'][$target]['text'][$field])
unset ($me->data['extras'][$target]['text'][$field]);
} // each field
if (!$me->data['extras'][$target]['text'])
unset ($me->data['extras'][$target]);
} // text exists
} // switch prefix
} // each target
} // language clear

if (count ($me->data['text']['caption']) > 1 and isset ($me->data['text']['caption'][$document->lang]))
{ // context clear language
$langCaption = $store->control->read ('labels/lang/' . $document->lang);
$document->mod->context->appendChild ('labels/action/remove_language')
->set ('lang_caption', $langCaption['text']['caption'])
->url (true, true, '_version-remove-' . $document->lang);
} // context clear language
} // function clearLanguage

public function versioning ($document)
{ // function versioning
$this->clearLanguage ($document);
$me = $document->application;
if (isset ($me->data['flags']['updated_' . $document->lang]))
$updated = $me->data['flags']['updated_' . $document->lang];
else
$updated = $me->data['updated'];
$versions = $this->children ($me->userId, MODE_VERSION, $me->id);

if (!$versions)
return;

$undo = [];
$redo = [];
$current = false;
foreach ($versions as $version)
{ // each version
if ($version['name'] != $document->lang)
continue;

if ($version['created'] < $updated)
$undo[] = $version;
elseif ($version['created'] == $updated)
$current = true;
elseif ($version['created'] > $updated)
$redo[] = $version;
} // each version

if ($document->actions ('version', 'undo') and $undo)
{ // undo
$update = array_pop ($undo);
if ($current)
$redo = true;
} // undo
elseif ($document->actions ('version', 'redo') and $redo)
{ // redo
$update = array_shift ($redo);
if ($current)
$undo = true;
} // redo

if (isset ($update))
{ // update
$lang = $document->lang;
$data = &$me->data;
$data['updated'] = $update['created'];
$data['flags']['updated_' . $lang] = $update['created'];
$fields = array_keys (array_replace ($data['text'], $update['text']));
foreach ($fields as $field)
{ // update each field
if (!isset ($data['text'][$field]))
$data['text'][$field] = $update['text'][$field];
elseif (isset ($update['text'][$field][$lang]))
$data['text'][$field][$lang] = $update['text'][$field][$lang];
else
{ // clear
unset ($data['text'][$field][$lang]);
if (!$data['text'][$field])
unset ($data['text'][$field]);
} // clear
} // update each field

if (isset ($data['extras']) and $data['extras'])
{ // filter extras
$modules = array_keys (array_replace ($data['extras'], $update['extras']));
foreach ($modules as $target)
{ // each target
list ($prefix) = explode ('_', $target);
switch ($prefix)
{ // switch prefix
case 'box':
if (!isset ($data['extras'][$target]))
{ // recreate
$data['extras'][$target] = $update['extras'][$target];
break;
} // recreate

$fields = array_keys (array_replace ($data['extras'][$target]['text'], $update['extras'][$field]['text']));

foreach ($fields as $field)
{ // each field
if (isset ($update['extras'][$target]['text'][$field][$lang]))
$data['extras'][$target]['text'][$field][$lang] = $update['extras'][$target]['text'][$field][$lang];
else
{ // clear language
unset ($update['extras'][$target]['text'][$field][$lang]);
if (!$data['extras'][$target]['text'][$field])
unset ($data['extras'][$target]['text'][$field]);
} // clear language
} // each field
if (!$data['extras'][$target]['text'])
unset ($data['extras'][$target]);
} // switch prefix
} // each target
} // filter extras
$document->dataReplace ($me->data);
} // update

if ($undo)
$document->mod->context->appendChild ('labels/action/undo')
->url (true, true, '_version-undo');

if ($redo)
$document->mod->context->appendChild ('labels/action/redo')
->url (true, true, '_version-redo');
} // function versioning

public function delete ($userId, $id)
{ // function delete
if (defined ('TRACKING_REMOVED_PAGES') and TRACKING_REMOVED_PAGES)
{ // do not remove page
$data = &$this->openById ($userId, $id);
$data['access'] = 255;
return;
} // do not remove page

if (isset ($this->rows[$userId][$id]))
{ // remove from index
$data = $this->rows[$userId][$id];
$this->deletedRows[$userId][$id] = $id;
$this->rows[$userId][$id] = [];
unset ($this->originalRows[$userId][$id]);
unset ($this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id]);
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

public function deleteAllChildren ($userId, $parentId)
{ // function deleteAllChildren
$where = array (
'user_id' => $userId, 
'parent_id' => $parentId
);
foreach ($this->search ($where) as $data)
{ // each row
$this->delete ($userId, $data['id']);
} // each row
} // function deleteAllChildren

public function close ()
{ // function close
foreach ($this->originalRows as $userId => $domainRows)
{ // each domain
foreach ($domainRows as $id => $originalRow)
{ // each row
if ($this->rows[$userId][$id] != $originalRow)
{ // data changed
if (is_int ($userId))
$this->database->update ($this, $this->rows[$userId][$id], $originalRow);
elseif (is_string ($userId) and $database = $this->getDatabase ($userId))
$database->update ($this, $this->rows[$userId][$id], $originalRow);
} // data changed
} // each row
} // each domain

$this->indexByParent = [];
$this->chargedParents = [];
$this->rows = [];
$this->originalRows = [];
$this->deletedRows = [];
$this->notFound = [];
} // function close

} // class eclStore_userExtras

?>