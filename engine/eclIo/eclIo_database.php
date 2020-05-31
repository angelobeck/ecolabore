<?php

/*
* Fields types
* primary_key
* tinyint
* mediumint
* int
* time
* name
* password
* hash
* array
* keywords
* binary
*/

class eclIo_database
{ // class eclIo_database

private $tables;
private $batchQuery = [];
private $databasePrefix = '';
private $client;
private $database;
private $databaseString, $externalString, $databaseString2, $externalString2;

public $encryptEnable = false;
public $cipher;
public $key;
public $ivLength;

public $status = false;
private $pdo;
public $verbose = true;
public $performed_queries = '';
private $last_query;

public function __construct ($io, $database=false)
{ // function __construct
if (!$database and (!defined ('DATABASE_ENABLE') or !DATABASE_ENABLE))
return;

try
{ // try
if ($database)
{ // from file
$this->client = 'sqlite';
$this->database = $database;
$this->pdo = new PDO ('sqlite:' . $database);
} // from file
else
{ // from server
$this->client = DATABASE_CLIENT;
$this->database = DATABASE_DB;
if (DATABASE_CLIENT == 'sqlite')
{ // sqlite
if (!is_dir (PATH_DATABASE))
mkdir (PATH_DATABASE);

$this->pdo = new PDO ('sqlite:' . PATH_DATABASE . '.system.db');
} // sqlite
else
{ // shared database
$this->pdo = new PDO (DATABASE_CLIENT . ':host=' . DATABASE_HOST . ';dbname=' . DATABASE_DB, DATABASE_USER, DATABASE_PASSWORD);
$this->databasePrefix = DATABASE_PREFIX;
} // shared database
} // from server
$this->status = true;

if (defined ('DATABASE_ENCRYPT_ENABLE') and DATABASE_ENCRYPT_ENABLE)
{ // enable encryption
$this->encryptEnable = true;
$this->cipher = DATABASE_ENCRYPT_CIPHER;
$this->key  = base64_decode (DATABASE_ENCRYPT_KEY);
$this->ivLength = openssl_cipher_iv_length(DATABASE_ENCRYPT_CIPHER);
} // enable encryption
} // try
catch (PDOException$e)
{ // catch
if (defined ('DATABASE_DISPLAY_ERRORS') and DATABASE_DISPLAY_ERRORS)
print 'Database connection error: ' . $e->getMessage () . '<br>';
if (defined ('DATABASE_LOG_ERRORS') and DATABASE_LOG_ERRORS)
{ // log errors
$string = '#On: ' . date ('c s u') . CRLF
 . '#client: ' . $this->client . ' database:' . $this->database . CRLF
 . '#Database connection error: ' . $e->getMessage () . CRLF . CRLF;
file_put_contents (DATABASE_LOG_FILE, $string, FILE_APPEND);
} // log errors
$this->status = false;
} // catch
} // function __construct

public function reconnect ()
{ // function reconnect
global $io;

if (!$io->systemConstants->check ('DATABASE_ENABLE') or !$io->systemConstants->constants['DATABASE_ENABLE'])
return;

try
{ // try
$c = $io->systemConstants->constants;

if ($c['DATABASE_CLIENT'] == 'sqlite')
{ // sqlite
if (!is_dir (FOLDER_DATABASE))
mkdir (FOLDER_DATABASE);
$this->pdo = new PDO ('sqlite:' . FOLDER_DATABASE . '.system.db');
} // sqlite
else
$this->pdo = new PDO ($c['DATABASE_CLIENT'] . ':host=' . $c['DATABASE_HOST'] . ';dbname=' . $c['DATABASE_DB'], $c['DATABASE_USER'], $c['DATABASE_PASSWORD']);
$this->status = true;
} // try
catch (PDOException$e)
{ // catch
$io->log->addMessage ('Database connection error: ' . $e->getMessage (), __CLASS__);
$this->status = false;
} // catch
} // function reconnect

public function query ($query)
{ // function query
global $io;
if (!$this->status or !is_string ($query) or !isset ($query[0]))
return [];

$this->performed_queries .= $query . CRLF;
$result = $this->pdo->query ($query);
if ($this->pdo->errorCode () != '00000' and $this->verbose)
{ // error
$error = $this->pdo->errorCode ();
$info = $this->pdo->errorInfo ();

$string = '#Date: ' . date ('c s u') . CRLF
 . '#client: ' . $this->client . ' database:' . $this->database . CRLF
 . $query . CRLF
 . '#The error: ' . $error . CRLF;

if (isset ($info[2]))
$string .= '# ' . $info[2] . CRLF;

$string .= CRLF;

$this->performed_queries .= '# Error' . CRLF;
$this->performed_queries .= '# ' . $error . CRLF;
if (isset ($info[2]))
$this->performed_queries .= '# ' . $info[2] . CRLF;

if (defined ('DATABASE_DISPLAY_ERRORS') and DATABASE_DISPLAY_ERRORS)
print nl2br (eclEngine_formulary::htmlSpecialChars ($string));
if (defined ('DATABASE_LOG_ERRORS') and DATABASE_LOG_ERRORS)
{ // log errors
if (!is_dir (FOLDER_DATABASE))
mkdir (FOLDER_DATABASE);

file_put_contents (FOLDER_DATABASE . '.database.log', $string, FILE_APPEND);
} // log errors

return [];
} // error

if (!is_object ($result))
return [];

$return = [];
foreach ($result as $row)
{ // each row
$return[] = $row;
} // each row

return $return;
} // function query

public function batchQuery ($query)
{ // function batchQuery
if (!is_array ($query) or !count ($query))
return;

$this->last_query = implode (";" . CRLF, $query);
$this->performed_queries .= $this->last_query;
foreach ($query as $call)
{ // each query
$this->pdo->query ($call);
} // each query
} // function batchQuery

public function insertId ()
{ // function insertId
return intval ($this->pdo->lastInsertId ());
} // function insertId

public function affectedRows ()
{ // function affectedRows
if ($this->client == 'mysql')
{ // mysql
if (is_array ($this->pdo->query ('mysql_affected_rows()')))
{ // is array
foreach ($this->pdo->query ('mysql_affected_rows()') as $row)
{ // each row
return current ($row);
} // each row
} // is array
} // mysql
return 0;
} // function affectedRows

public function error ()
{ // function error
$error = $this->pdo->errorInfo ();
if ($error)
return $error[2];
return '';
} // function error

public function commit ()
{ // function commit
} // function commit

public function tableEnabled ($table)
{ // function tableEnabled
if (!$this->status)
return false;
if (!isset ($this->tables))
{ // list tables
if ($this->client == 'mysql')
$rows = $this->query ('SHOW TABLES');
else
$rows = $this->query ("SELECT `name` FROM sqlite_master WHERE `type`=" . TIC . "table" . TIC);
foreach ($rows as $row)
{ // each table
$this->tables[current ($row)] = true;
} // each table
} // list tables

$table_name = $this->databasePrefix . $table->name;
if (isset ($this->tables[$table_name]))
return true;

$this->create ($table);

$this->tables = [];
if ($this->client == 'mysql')
$rows = $this->query ('SHOW TABLES');
else
$rows = $this->query ("SELECT `name` FROM sqlite_master WHERE `type`=" . TIC . "table" . TIC);
foreach ($rows as $row)
{ // each table
$this->tables[current ($row)] = true;
} // each table

$table_name = $this->databasePrefix . $table->name;
if (isset ($this->tables[$table_name]))
return true;

return false;
} // function tableEnabled

public function create ($table)
{ // function create
if (!count ($table->fields))
return false;

// Create table description
$lines = [];
foreach ($table->fields as $fieldName => $fieldType)
{ // each field
switch ($fieldType)
{ // switch field type
case 'primary_key':
if ($this->client == 'mysql')
$lines[] = '`' . $fieldName . '` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';
else
$lines[] = '`' . $fieldName . '` INTEGER PRIMARY KEY';
break;

case 'tinyint':
$lines[] = '`' . $fieldName . '` TINYINT UNSIGNED NOT NULL';
break;

case 'mediumint':
$lines[] = '`' . $fieldName . '` MEDIUMINT UNSIGNED NOT NULL';
break;

case 'int':
case 'time':
$lines[] = '`' . $fieldName . '` INTEGER UNSIGNED NOT NULL';
break;

case 'name':
case 'password':
case 'hash':
if ($this->client == 'mysql')
$lines[] = '`' . $fieldName . '` CHAR(64) BINARY NOT NULL';
else
$lines[] = '`' . $fieldName . '` BLOB NOT NULL';
break;

case 'array':
case 'binary':
case 'keywords':
if ($this->client == 'mysql')
$lines[] = '`' . $fieldName . '` MEDIUMBLOB NOT NULL';
else
$lines[] = '`' . $fieldName . '` BLOB NOT NULL';
break;
} // switch type
} // each field

// The query
$this->query ('CREATE TABLE `' . $this->databasePrefix . $table->name . "` (" . CRLF
 . implode ("," . CRLF, $lines)
 . CRLF . ")" . CRLF);

// Index
if (isset ($table->index))
{ // index exists
foreach ($table->index as $index_name => $index_fields)
{ // each index
$this->query ('CREATE INDEX `' . $index_name . '` ON `' . $this->databasePrefix . $table->name . '` (`' . implode ('`, `', $index_fields) . '`)');
} // each index
} // index exists
} // function create

public function insert ($table, &$data)
{ // function insert
global $io;
$fields = [];
$values = [];

foreach ($table->fields as $fieldName => $fieldType)
{ // each field
switch ($fieldType)
{ // switch filter
case 'primary_key':
break;

case 'tinyint':
case 'mediumint':
case 'int':
if (!isset ($data[$fieldName]))
$data[$fieldName] = 0;

$fields[] = $fieldName;
$values[] = strval (intval ($data[$fieldName]));
break;

case 'name':
if (!isset ($data[$fieldName]) or !preg_match ('%^[a-zA-Z0-9@:./_-]*$%', $data[$fieldName]))
$data[$fieldName] = '';

$fields[] = $fieldName;
$values[] = "" . TIC . $data[$fieldName] . TIC . "";
break;

case 'time':
if (!isset ($data[$fieldName]) or !is_int ($data[$fieldName]) or $data[$fieldName] == 0)
$data[$fieldName] = TIME;

$fields[] = $fieldName;
$values[] = strval (intval ($data[$fieldName]));
break;

case 'array':
$fields[] = $fieldName;
if (!isset ($data[$fieldName]) or !is_array ($data[$fieldName]))
$data[$fieldName] = [];

if (!$data[$fieldName])
$values[] = "''";
elseif (isset ($data['encrypt']))
$values[] = "" . TIC . $this->encrypt (serialize ($data[$fieldName])) . TIC . "";
else
$values[] = "" . TIC . $this->stringToDatabase (serialize ($data[$fieldName])) . TIC . "";
break;

case 'keywords':
$fields[] = $fieldName;
if (!isset ($data[$fieldName]) or !is_string ($data[$fieldName]))
$data[$fieldName] = '';

if (!isset ($data[$fieldName][0]))
$values[] = "''";
else
$values[] = "" . TIC . implode (' ', $this->filterKeywords ($data[$fieldName])) . TIC . "";
break;

case 'hash':
if (!isset ($data[$fieldName]) or !is_string ($data[$fieldName]))
$data[$fieldName] = '';

$fields[] = $fieldName;
$values[] = "" . TIC . $this->hash ($data[$fieldName]) . TIC . "";
break;

case 'password':
if (!isset ($data[$fieldName]) or !is_string ($data[$fieldName]))
$data[$fieldName] = '';

$fields[] = $fieldName;
$values[] = "" . TIC . $this->password ($data[$fieldName]) . TIC . "";
break;

case 'binary':
$fields[] = $fieldName;
if (!isset ($data[$fieldName]) or !is_string ($data[$fieldName]))
$data[$fieldName] = '';

if (!isset ($data[$fieldName][0]))
$values[] = "''";
else
$values[] = "" . TIC . $this->stringToDatabase ($data[$fieldName]) . TIC . "";
break;

default:
break;
} // switch filter
} // each field

// The query
$this->query ('INSERT INTO `' . $this->databasePrefix . $table->name . '` (`'
 . implode ('`, `', $fields) . '`) VALUES ('
 . implode (', ', $values) . ')');
$data['id'] = $this->insertId ();
return $data['id'];
} // function insert

public function select ($table, $where, $limit='', $returnRows=false)
{ // function select
$results = [];

$conditions = [];
foreach ($where as $fieldName => $field_value)
{ // each where condition
if (isset ($table->fields[$fieldName]))
{ // valid field
switch ($table->fields[$fieldName])
{ // switch filter
case 'primary_key':
case 'tinyint':
case 'mediumint':
case 'int':
case 'time':
if (is_int ($field_value))
$conditions[] = '`' . $fieldName . '`=' . strval ($field_value);
elseif (is_string ($field_value) and is_numeric ($field_value))
$conditions[] = '`' . $fieldName . '`=' . $field_value;
elseif (is_string ($field_value) and preg_match ('%^[<=> ]*[0-9]+$%', trim ($field_value)))
$conditions[] = '`' . $fieldName . '` ' . $field_value;
elseif (is_array ($field_value))
{ // set
$field_value = implode (', ', $field_value);
if (preg_match ('%[0-9, ]+%', $field_value))
$conditions[] = '`' . $fieldName . '` IN(' . $field_value . ')';
} // set
break;

case 'name':
if (preg_match ('%^[a-zA-Z0-9@:/._-]+$%', $field_value))
$conditions[] = '`' . $fieldName . "`=" . TIC . $field_value . TIC . "";
break;

case 'hash':
$conditions[] = '`' . $fieldName . "`=" . TIC . $this->hash ($field_value) . TIC . "";
break;

case 'keywords':
foreach ($this->filterKeywords ($field_value) as $keyword)
{ // each keyword
$conditions[] = '`' . $fieldName . "` LIKE('%" . $keyword . "%')";
} // each keyword
break;
} // switch filter
} // valid field
} // each where condition

if ($conditions)
{ // conditions exists
// The query
if ($returnRows)
{ // select return columns
foreach ($returnRows as $fieldName)
{ // each column
if (!isset ($table->fields[$fieldName]))
continue;
$columns[] = '`' . $fieldName . '`';
$returnColumns[$fieldName] = $table->fields[$fieldName];
} // each column
} // select return columns
if (isset ($columns))
$columns = implode (', ', $columns);
else
$columns = '*';

$rows = $this->query ('SELECT ' . $columns . ' FROM `' . $this->databasePrefix . $table->name . '` WHERE '
 . implode (' AND ', $conditions) . $limit);

if (!isset ($returnColumns))
$returnColumns = $table->fields;

// Extract rows
foreach ($rows as $row)
{ // each row
$data = [];
foreach ($returnColumns as $fieldName => $fieldType)
{ // each field
switch ($fieldType)
{ // switch filter
case 'primary_key':
case 'tinyint':
case 'mediumint':
case 'int':
case 'time':
$data[$fieldName] = intval ($row[$fieldName]);
break;

case 'array':
if (isset ($row[$fieldName][0]))
{  // data exists
if ($row[$fieldName][0] == '-')
{ // encrypted
$data['encrypt'] = 1;
$data[$fieldName] = unserialize ($this->decrypt ($row[$fieldName]));
} // encrypted
else
$data[$fieldName] = unserialize ($this->stringFromDatabase ($row[$fieldName]));
} // data exists
else
$data[$fieldName] = [];
break;

default:
if (isset ($row[$fieldName][0]))
$data[$fieldName] = $this->stringFromDatabase ($row[$fieldName]);
else
$data[$fieldName] = '';
} // switch filter
} // each field
$results[] = $data;
} // each row

return $results;
} // conditions exists
return [];
} // function select

public function update ($table, $data, $originalData)
{ // function update
$id = intval ($data['id']);
if (!$id)
return;

$set = [];
foreach ($table->fields as $fieldName => $fieldType)
{ // each field
if (!isset ($data[$fieldName]))
$data[$fieldName] = false;
if (!isset ($originalData[$fieldName]) or $data[$fieldName] != $originalData[$fieldName])
{ // field changed
switch ($fieldType)
{ // switch filter

case 'tinyint':
case 'mediumint':
case 'int':
case 'time':
$set[] = '`' . $fieldName . '`=' . strval (intval ($data[$fieldName]));
break;

case 'name':
if (is_string ($data[$fieldName]) and preg_match ('%^[a-zA-Z0-9@:/._-]*$%', $data[$fieldName]))
$set[] = '`' . $fieldName . "`=" . TIC . $data[$fieldName] . TIC . "";
break;

case 'array':
if (!is_array ($data[$fieldName]))
$data[$fieldName] = [];
if (isset ($data['encrypt']))
$set[] = '`' . $fieldName . "`=" . TIC . $this->encrypt (serialize ($data[$fieldName])) . TIC . "";
else
$set[] = '`' . $fieldName . "`=" . TIC . $this->stringToDatabase (serialize ($data[$fieldName])) . TIC . "";
break;

case 'hash':
if ($data[$fieldName] != $originalData[$fieldName])
$set[] = '`' . $fieldName . "`=" . TIC . $this->hash ($data[$fieldName]) . TIC . "";
break;

case 'password':
if ($data[$fieldName] != $originalData[$fieldName])
$set[] = '`' . $fieldName . "`=" . TIC . $this->password ($data[$fieldName]) . TIC . "";
break;

case 'keywords':
if (isset ($data[$fieldName][0]))
{ // filter keywords
$keywords = implode (' ', $this->filterKeywords (strval ($data[$fieldName])));
if (!isset ($originalData[$fieldName]) or $keywords != $originalData[$fieldName])
$set[] = '`' . $fieldName . "`=" . TIC . $keywords . TIC . "";

break;
} // filter keywords

if (isset ($originalData[$fieldName][0]))
$set[] = '`' . $fieldName . "`=''";
break;

case 'binary':
if (!is_string ($data[$fieldName]) or !isset ($data[$fieldName][0]))
$set[] = '`' . $fieldName . "`=''";
else
$set[] = '`' . $fieldName . "`=" . TIC . $this->stringToDatabase ($data[$fieldName]) . TIC . "";
break;
} // switch filter
} // field changed
} // each field

if (count ($set))
{ // valid fields changed
$this->batchQuery[] = 'UPDATE `' . $this->databasePrefix . $table->name . '` SET '
 . implode (', ', $set) . ' WHERE `id`=' . $id;
} // valid fields changed
} // function update

public function delete ($table, $where)
{ // function delete
$conditions = [];
foreach ($where as $fieldName => $field_value)
{ // each where condition
if (isset ($table->fields[$fieldName]))
{ // valid field
switch ($table->fields[$fieldName])
{ // switch filter
case 'primary_key':
case 'tinyint':
case 'mediumint':
case 'int':
case 'time':
if (is_numeric ($field_value))
$conditions[] = '`' . $fieldName . '`=' . strval ($field_value);

break;

case 'name':
if (preg_match ('%^[a-zA-Z0-9@:/._-]*$%', $field_value))
$conditions[] = '`' . $fieldName . "`=" . TIC . $field_value . TIC . "";
break;
} // switch filter
} // valid field
} // each where condition

// The query
$this->query ('DELETE FROM `' . $this->databasePrefix . $table->name . '` WHERE '
 . implode (' AND ', $conditions));
} // function delete

public function drop ($table)
{ // function drop
$this->query ('DROP TABLE `' . $this->databasePrefix . $table->name . '`');
} // function drop

public function close ()
{ // function close
global $io;
$this->batchQuery ($this->batchQuery);
$this->batchQuery = [];
// $io->log->addMessage ($this->performed_queries, 'database');
} // function close

public function stringToDatabase ($string)
{ // function stringToDatabase
if (!isset ($this->externalString))
{ // set replace sequence
$this->externalString = array (
'#', 
chr (0), 
chr (26), 
// chr (34),
chr (39), 
chr (92)
);

$this->databaseString = array (
'#c', 
'#0', 
'#z', 
// '#q',
'#s', 
'#e'
);
} // set replace sequence

return str_replace ($this->externalString, $this->databaseString, $string);
} // function stringToDatabase

public function stringFromDatabase ($string)
{ // function stringFromDatabase
if (!isset ($this->externalString2))
{ // set replace sequence
$this->externalString2 = array (
chr (0), 
chr (26), 
chr (34), 
chr (39), 
chr (92)
);

$this->databaseString2 = array (
'#0', 
'#z', 
'#q', 
'#s', 
'#e'
);
} // set replace sequence
if (strpos ($string, CHR_BSLASH) === false)
return str_replace ('#c', '#', str_replace ($this->databaseString2, $this->externalString2, $string));
return stripslashes ($string);
} // function stringFromDatabase

public static function filterKeywords ($string)
{ // function filterKeywords
static $convert = array ('a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e', 'f' => 'f', 'g' => 'g', 'h' => 'h', 'i' => 'i', 'j' => 'j', 'k' => 'k', 'l' => 'l', 'm' => 'm', 'n' => 'n', 'o' => 'o', 'p' => 'p', 'q' => 'q', 'r' => 'r', 's' => 's', 't' => 't', 'u' => 'u', 'v' => 'v', 'w' => 'w', 'x' => 'x', 'y' => 'y', 'z' => 'z', 'A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e', 'F' => 'f', 'G' => 'g', 'H' => 'h', 'I' => 'i', 'J' => 'j', 'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o', 'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't', 'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y', 'Z' => 'z', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'a', '?' => 'c', '?' => 'c', '?' => 'e', '?' => 'e', '?' => 'e', '?' => 'e', '?' => 'e', '?' => 'e', '?' => 'i', '?' => 'i', '?' => 'i', '?' => 'i', '?' => 'i', '?' => 'i', '?' => 'o', '?' => 'o', '?' => 'o', '?' => 'o', '?' => 'o', '?' => 'o', '?' => 'o', '?' => 'o', '?' => 'u', '?' => 'u', '?' => 'u', '?' => 'u', '?' => 'u', '?' => 'u', '?' => 'y', '?' => 'y', '?' => 'y', '?' => 'y', ' ' => ' ', '-' => '-', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '0' => '0', '_' => ' ', ':' => ':', ';' => ';');

// The $last will remember the last character of the convertion
// to prevent duplicated spacing "__" or "--" in the name

$result = '';
$last = ' ';
foreach (str_split ($string) as $char)
{ // each char
if (!isset ($convert[$char]))
continue;
$valid = $convert[$char];
if ($valid != $last)
{ // not repeated separator
if ($valid == '-' or $valid == ' ')
$last = $valid;
else
$last = '';
$result .= $valid;
} // not repeated separator
} // each char
$words = explode (' ', $result);
$keywords = [];
$validWords = [];
foreach ($words as $word)
{ // each word
$validWords[strlen ($word)][] = $word;
} // each word
krsort ($validWords);
foreach ($validWords as $index => $group)
{ // each group
if ($index < 3)
break;

foreach ($group as $word)
{ // each word
$valid = true;
foreach ($keywords as $value)
{ // each keyword
if (is_int (strpos ($value, $word)))
{ // existing word
$valid = false;
} // existing word
} // each keyword
if ($valid)
$keywords[] = $word;
} // each word
} // each  group

sort ($keywords);
return $keywords;
} // function filterKeywords

private function encrypt ($string)
{ // function encrypt
if (!$this->encryptEnable)
return $string;

$iv = openssl_random_pseudo_bytes($this->ivLength);
$trash = openssl_random_pseudo_bytes(rand (16, 32));
$string = str_replace ('-', '', $trash) . '-' . $string;

$encoded = openssl_encrypt($string, $this->cipher, $this->key, 0, $iv);
return '-' . base64_encode ($iv) . '-' . $encoded;
} // function encrypt

private function decrypt ($string)
{ // decrypt
if (!$this->encryptEnable)
return '';

$pos = strpos ($string, '-', 1);
$iv = base64_decode (substr ($string, 1, $pos));
$encoded = substr ($string, $pos + 1);
$joint = openssl_decrypt($encoded, $this->cipher, $this->key, 0, $iv);
$pos = strpos ($joint, '-');
return substr ($joint, $pos + 1);
} // decrypt

public function hash ($string)
{ // function hash
if (!isset ($string[0]))
return '';

if (!$this->encryptEnable or !defined ('DATABASE_ENCRYPT_HASH'))
return $string;

return openssl_digest ($string . DATABASE_ENCRYPT_HASH, 'sha256', false); 
} // function hash

public static function password ($string)
{ // function password
return password_hash ($string, PASSWORD_DEFAULT); 
} // function password

public static function passwordCheck ($string, $password)
{ // passwordCheck
if (preg_match ('/^[a-zA-Z0-9]{32}$/', $password))
{ // md5 old style
if (md5 ($string) == $password)
return true;

return false;
} // md5 old style
return password_verify ($string, $password);
} // passwordCheck

} // class eclIo_database

?>