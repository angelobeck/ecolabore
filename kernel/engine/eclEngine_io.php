<?php

class eclEngine_io
{
    public eclIo_database $database;
    public eclIo_request $request;
    public eclIo_sendFile $sendFile;
    public eclIo_session $session;

    public function __construct()
    {
        $this->request = new eclIo_request();
        $this->database = new eclIo_database($this);
        $this->sendFile = new eclIo_sendFile();
        $this->session = new eclIo_session();
    }

    public function close()
    {
        $this->database->close();
    }

}
