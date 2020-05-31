<?php

class eclGroup_domain_group
{ // class eclGroup_domain_group

private $domainId;
private $groupId;

public function __construct ($domainId, $groupId)
{ // function __construct
$this->domainId = $domainId;
$this->groupId = $groupId;
} // function __construct

public function check ($document)
{ // function check
global $store;
$user = $document->user;

static $opened = [];

if (!isset ($opened[$user->name]))
$opened[$user->name] = $store->domainContent->open ($this->domainId, '-user-' . $user->name);

if (isset ($opened[$user->name]['parent_id']) and $opened[$user->name]['parent_id'] == $this->groupId)
return 3;

return 0;
} // function check

} // class eclGroup_domain_group

?>