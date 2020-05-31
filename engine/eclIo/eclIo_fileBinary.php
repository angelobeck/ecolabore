<?php

class eclIo_fileBinary
{ // class eclIo_fileBinary

private $openedFiles = [];

public function &open ($name)
{ // function &
if (!isset ($this->openedFiles[$name]))
{ // open file
if (is_file ($name))
$this->openedFiles[$name][0] = file_get_contents ($name);
else
$this->openedFiles[$name][0] = '';
$this->openedFiles[$name][1] = $this->openedFiles[$name][0];
} // open file
$data = &$this->openedFiles[$name][0];
return $data;
} // function &

public function close ()
{ // function close
foreach ($this->openedFiles as $name => $contents)
{ // each opened file
if (strlen ($contents[0]) and $contents[0] != $contents[1])
file_put_contents ($name, $contents[0]);
if (!strlen ($contents[0]) and is_file ($name))
unlink ($name);
} // each opened files
} // function close

} // class eclIo_fileBinary

?>