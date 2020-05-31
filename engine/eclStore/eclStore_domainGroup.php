<?php

class eclStore_domainGroup
{ // class eclStore_domainGroup

public $name = 'domain_group';
public $fields = array (
'domain_id' => 'mediumint', 
'group_id' => 'mediumint', 
'user_id' => 'mediumint', 
'status' => 'tinyint'
);

// Index
public $index = array (
'group_find_user' => array ('user_id'), 
'domain_find_group' => array ('domain_id', 'group_id')
);

private $database = false;

private $groups = [];

public function __construct ()
{ // function __construct
global $io;
if ($io->database->tableEnabled ($this))
$this->database = $io->database;
} // function __construct

public function getStatus ($domainId, $groupId, $userId)
{ // function getStatus
$this->open ($domainId, $groupId);

if (isset ($this->groups[$domainId][$groupId][0][$userId]))
return $this->groups[$domainId][$groupId][0][$userId];

return 0;
} // function getStatus

public function &open ($domainId, $groupId)
{ // function &
if (isset ($this->groups[$domainId][$groupId][0]))
return $this->groups[$domainId][$groupId][0];

if (is_int ($domainId) and $this->database)
{ // from database
$this->groups[$domainId][$groupId][0] = [];
$group = &$this->groups[$domainId][$groupId][0];
foreach ($this->database->select ($this, array ('domain_id' => $domainId, 'group_id' => $groupId)) as $row)
{ // each row
$group[$row['user_id']] = $row['status'];
} // each row
$this->groups[$domainId][$groupId][1] = $this->groups[$domainId][$groupId][0];
} // from database

$return = &$this->groups[$domainId][$groupId][0];
return $return;
} // function &

public function getDomainUsers ($domainId)
{ // function getDomainUsers
$return = [];
if (is_int ($domainId) and $this->database)
{ // from database
foreach ($this->database->select ($this, array ('domain_id' => $domainId)) as $row)
{ // each row
$return[$row['user_id']] = $row['status'];
} // each row
} // from database
return $return;
} // function getDomainUsers

public function getDomainGroups ($domainId)
{ // function getDomainGroups
$groups = [];
if (is_int ($domainId) and $this->database)
{ // from database
foreach ($this->database->select ($this, array ('domain_id' => $domainId)) as $row)
{ // each row
$groups[$row['group_id']] = $row['group_id'];
} // each row
} // from database
return $groups;
} // function getDomainGroups

public function getUserLinks ($userId)
{ // function getUserLinks
if ($this->database)
return $this->database->select ($this, array ('user_id' => $userId));

return [];
} // function getUserLinks

public function getDomainSubscribedUsers ($domainId)
{ // function getDomainSubscribedUsers
$users = [];

if (is_int ($domainId) and $this->database)
{ // from database
foreach ($this->database->select ($this, array ('domain_id' => $domainId)) as $row)
{ // each row
if ($row['status'] == 5)
$users[$row['user_id']] = $row['group_id'];
} // each row
} // from database
return $users;
} // function getDomainSubscribedUsers

public function close ()
{ // function close
if (!$this->database)
return;

foreach ($this->groups as $domainId => $groups)
{ // each domain
foreach ($groups as $groupId => $group)
{ // each group
if (!$group[0] and $group[1])
$this->database->delete ($this, array ('domain_id' => $domainId, 'group_id' => $groupId));
elseif ($group[0] != $group[1])
{ // modify group
foreach ($group[0] as $userId => $status)
{ // each user
if (!isset ($group[1][$userId]))
{ // insert
$data = array ('domain_id' => $domainId, 'group_id' => $groupId, 'user_id' => $userId, 'status' => $status);
$this->database->insert ($this, $data);
} // insert
elseif ($group[1][$userId] != $status)
{ // change status
$originalData = $this->database->select ($this, array ('domain_id' => $domainId, 'group_id' => $groupId, 'user_id' => $userId));
$data = $originalData;
$data['status'] = $status;
$this->database->update ($this, $data, $originalData);
} // change status
else
unset ($group[1][$userId]);
} // each user
foreach ($group[1] as $userId => $status)
{ // delete remain users in group
$this->database->delete ($this, array ('domain_id' => $domainId, 'group_id' => $groupId, 'user_id' => $userId));
} // delete remain users in group
} // modify group
} // each group
} // each domain
$this->groups = [];
} // function close

} // class eclStore_domainGroup

?>