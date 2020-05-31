<?php

class eclGroup_domain
{ // class eclGroup_domain

private $domainId;

public function __construct ($domainId)
{ // function __construct
$this->domainId = $domainId;
} // function __construct

public function check ($document)
{ // function check
global $store;
$user = $document->user;
static $opened;

if (!$user->userId)
return 0;

if (!isset ($opened))
$opened = $store->domainGroup->open ($this->domainId, 1);

if (isset ($opened[$user->userId]))
return $opened[$user->userId];

return 1;
} // function check

} // class eclGroup_domain

?>