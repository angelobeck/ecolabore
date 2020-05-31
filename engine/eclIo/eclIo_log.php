<?php

class eclIo_log
{ // class eclIo_log

public $cache = '';
public $silent = false;
public $buffer = '';

private $messages = [];
private $startTime;

public function __construct ()
{ // function __construct
$this->startTime = microtime (true);
} // function __construct

public function addMessage ($message, $group='')
{ // function addMessage
$this->messages[$group][] = $message;
} // function addMessage

public function close ()
{ // function close
if ($this->silent)
return;

$this->buffer = "<!-- Ecolabore System Log" . CRLF;
foreach ($this->messages as $message_group)
{ // each group
foreach ($message_group as $message)
{ // eachh message
$this->buffer .= $message . CRLF;
} // each message
} // each group
$this->buffer .= round (1000 * (microtime (true) - $this->startTime), 1) . 'ms -->';
} // function close

} // class eclIo_log

?>