<?php

class eclEngine_io
{
    public eclIo_database $database;
    public eclIo_request $request;

    public function __construct()
    {
        $this->request = new eclIo_request();
        $this->database = new eclIo_database($this);
    }

    public function close()
    {
        $this->database->close();
    }

}
