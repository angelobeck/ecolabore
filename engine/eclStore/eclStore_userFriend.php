<?php

class eclStore_userFriend
{ // class eclStore_userFriend

public $name = 'user_friend';
public $fields = array (
'user_id' => 'mediumint', 
'friend_id' => 'mediumint', 
'created' => 'time', 
'updated' => 'time', 
'status' => 'tinyint'
);

public $insertedData = [];
private $database = false;

private $groups = [];

public function __construct ()
{ // function __construct
global $io;
if ($io->database->tableEnabled ($this))
$this->database = $io->database;
} // function __construct

public function close ()
{ // function close
} // function close

} // class eclStore_userFriend

?>