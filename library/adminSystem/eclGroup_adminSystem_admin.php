<?php

class eclGroup_adminSystem_admin
{ // class eclGroup_adminSystem_admin

public function check ($document)
{ // function check
if (!defined ('ADMIN_IDENTIFIER'))
return 0;

if ($document->user->name == ADMIN_IDENTIFIER)
return 4;

if ($document->user->userId)
return 1;

return 0;
} // function check

} // class eclGroup_adminSystem_admin

?>