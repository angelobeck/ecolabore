<?php

class eclGroup_system
{ // class eclGroup_system

public function check ($document)
{ // function check
if (!defined ('ADMIN_IDENTIFIER'))
return 0;

if ($document->user->name == ADMIN_IDENTIFIER)
return 4;

if (defined ('ADMIN_HELPERS') and strlen (ADMIN_HELPERS) and in_array ($document->user->name, explode (' ', ADMIN_HELPERS)))
return 4;

return 0;
} // function check

} // class eclGroup_system

?>