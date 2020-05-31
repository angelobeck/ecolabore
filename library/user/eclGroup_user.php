<?php

class eclGroup_user
{ // class eclGroup_user

private $userId;

public function __construct ($userId)
{ // function __construct
$this->userId = $userId;
} // function __construct

public function check ($document)
{ // function check
if ($this->userId == $document->user->userId)
return 4;

if (defined ('ADMIN_IDENTIFIER') and $document->user->name == ADMIN_IDENTIFIER)
return 1;

elseif (!$document->user->userId)
return 0;

return 1;
} // function check

} // class eclGroup_user

?>