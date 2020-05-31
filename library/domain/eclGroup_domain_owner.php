<?php

class eclGroup_domain_owner
{ // class eclGroup_domain_owner

private $me;

public function __construct ($me)
{ // function __construct
$this->me = $me;
} // function __construct

public function check ($document)
{ // function check
if (isset ($me->data['owner_id']) and $me->data['owner_id'] == $document->subscription->id)
return 4;

return 0;
} // function check

} // class eclGroup_domain_owner

?>