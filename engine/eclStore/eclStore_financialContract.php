<?php

class eclStore_financialContract
{ // class eclStore_financialContract

public $name = 'financial_contract';

public $fields = [
// Indexing
'domain_id' => 'mediumint', 
'parent_id' => 'mediumint', 
'master_id' => 'mediumint', 
'subscription_id' => 'mediumint', 
'user_id' => 'mediumint', 
'id' => 'primary_key', 
// Class identifiers
'marker' => 'tinyint', 
'status' => 'tinyint', 
'alert_domain' => 'tinyint', 
'alert_user' => 'tinyint', 
// Dates
'created' => 'time', 
'updated' => 'time', 
'contract_start' => 'time', 
'contract_end' => 'time', 
// values
'units' => 'mediumint', 
'value' => 'mediumint', 
// Contents
'text' => 'array', 
'local' => 'array', 
];

// Index
public $index = [
'financial_contract_find_parent' => ['domain_id', 'parent_id'],
'financial_contract_find_subscription' => ['domain_id', 'subscription_id'],
'financial_contract_find_user' => ['user_id'], 
];

private $rows = [];
private $originalRows = [];
private $deletedRows = [];
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
$domainId = $data['domain_id'];
$id = $data['id'];
if (isset ($this->deletedRows[$domainId][$id]))
continue;

if (!isset ($this->rows[$domainId][$id]))
{ // row not indexed
$this->rows[$domainId][$id] = $data;
$this->originalRows[$domainId][$id] = $data;
$found[] = $data;
} // row not indexed
else
$found[] = $this->rows[$domainId][$id];
} // each row
return $found;
} // function indexFoundRows

public function insert ($domainId, &$data)
{ // function insert
global $io;
if (is_int ($domainId) and $this->database)
$database = $this->database;
else
return 0;

$data['domain_id'] = $domainId;
$id = $database->insert ($this, $data);
$data['id'] = $id;
$this->rows[$domainId][$id] = $data;
$this->originalRows[$domainId][$id] = $data;
$this->lastInsertedData = $data;
return $id;
} // function insert

public function &openById ($domainId, $id)
{ // function &
if (!isset ($this->rows[$domainId][$id]))
{ // open
if (is_int ($domainId) and $this->database)
$this->indexFoundRows ($this->database->select ($this, ['domain_id' => $domainId, 'id' => $id]));
} // open

$found = [];
if (isset ($this->rows[$domainId][$id]))
$found = &$this->rows[$domainId][$id];

return $found;
} // function &

public function search ($where)
{ // function search
if ( (!isset ($where['domain_id']) or is_int ($where['domain_id'])) and is_object ($this->database))
return $this->indexFoundRows ($this->database->select ($this, $where));

return [];
} // function search

public function delete ($domainId, $id)
{ // function delete
if (isset ($this->rows[$domainId][$id]))
{ // remove from index
$data = $this->rows[$domainId][$id];
$this->deletedRows[$domainId][$id] = $id;
$this->rows[$domainId][$id] = [];
unset ($this->originalRows[$domainId][$id]);
} // remove from index
if (is_int ($domainId) and $this->database)
$this->database->delete ($this, ['id' => $id]);
} // function delete

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
} // data changed
} // each row
} // each domain

$this->rows = [];
$this->originalRows = [];
$this->deletedRows = [];
} // function close

} // class eclStore_financialContract

?>