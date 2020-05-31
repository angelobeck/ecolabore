<?php

class eclIo_sms
{ // class eclIo_sms

public function enabled ()
{ // function enabled
if (defined ('INTEGRATION_SMS_ENABLE') and INTEGRATION_SMS_ENABLE)
return true;

return false;
} // function enabled

public function __call ($name, $arguments)
{ // function __call
global $io;

if (!defined ('INTEGRATION_SMS_ENABLE') or !INTEGRATION_SMS_ENABLE)
return false;

if (!defined ('INTEGRATION_SMS_SERVER'))
return false;

$server = INTEGRATION_SMS_SERVER;
return $io->$server->$name ($arguments);
} // function __call

public function close ()
{ // function close
} // function close

} // class eclIo_sms

?>