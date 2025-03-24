<?php

class eclStore_user extends eclStore
{ // class eclStore_user

    public string $name = 'user';

    public array $fields = [
        'id' => 'primary_key',
        'name' => 'name/32',
        'mail' => 'hash',
        'phone' => 'hash',
        'document' => 'hash',
        'password' => 'password',
        'status' => 'name/8',
        'access' => 'name/8',
        'created' => 'time',
        'lastAccess' => 'time',
        'token' => 'hash'
    ];

    public $insertedData = [];
    private $rows = [];
    private $originalRows = [];
    private $indexByName = [];
    private $database;

    public function __construct()
    {
        global $io;
        if ($io->database->tableEnabled($this))
            $this->database = $io->database;
    }

    public function insert($data)
    {
        global $io;
        if (isset($data['name'][0]) and !is_dir(PATH_USERS))
            mkdir(PATH_USERS);
        if (isset($data['name'][0]) and !is_dir(PATH_USERS . $data['name']))
            mkdir(PATH_USERS . $data['name']);

        if ($this->database) { // insert into database
            $id = $this->database->insert($this, $data);
            $data['id'] = $id;
            $this->rows[$id] = $data;
            $this->originalRows[$id] = $data;
            $this->indexByName[$data['name']] = $id;
            return $id;
        }

        return 0;
    }

    public function &open($name)
    {
        global $io;
        if (isset($this->indexByName[$name])) { // row found
            $id = $this->indexByName[$name];
            $return = &$this->rows[$id];
            return $return;
        }

        if ($this->database) { // open from database
            $rows = $this->database->select($this, array('name' => $name));
            if ($rows) { // row found
                $data = $rows[0];
                $id = $data['id'];
                $this->rows[$id] = $data;
                $this->originalRows[$id] = $data;
                $this->indexByName[$name] = $id;
                $return = &$this->rows[$id];
                return $return;
            }
        }

        $return = [];
        return $return;
    }

    public function &openById($id)
    {
        if (isset($this->rows[$id])) { // row found
            $row = &$this->rows[$id];
            return $row;
        } // row found

        if (is_int($id) and $this->database) { // open from database
            $rows = $this->database->select($this, array('id' => $id));
            if ($rows) { // row found
                $data = $rows[0];
                $this->rows[$id] = $data;
                $this->originalRows[$id] = $data;
                $this->indexByName[$data['name']] = $id;
                $return = &$this->rows[$id];
                return $return;
            } // row found
        } // open from database
        $row = [];
        return $row;
    }

    public function getId($name)
    {
        if (!isset($this->indexByName[$name]))
            $this->open($name);
        if (isset($this->indexByName[$name]))
            return $this->indexByName[$name];
        else
            return false;
    }

    public function getName($id)
    {
        if (!isset($this->rows[$id]))
            $this->openById($id);
        if (isset($this->rows[$id]['name']))
            return $this->rows[$id]['name'];
        else
            return false;
    }

    public function getStatus($name)
    {
        if (!isset($this->indexByName[$name]))
            $this->open($name);
        if (!isset($this->indexByName[$name]))
            return 0;

        $id = $this->indexByName[$name];
        $row = &$this->rows[$id];
        if (!isset($row['status']))
            return 0;

        return $row['status'];
    }

    public function childrenNames()
    {
        $names = [];
        if (!is_dir(PATH_USERS))
            return [];
        foreach (scandir(PATH_USERS) as $folder) { // each folder
            if ($folder[0] == '.')
                continue;

            if (!is_dir(PATH_USERS . $folder))
                continue;

            if ($this->getStatus($folder))
                $names[] = $folder;
        }

        return $names;
    }

    public function delete($id)
    {
        if (isset($this->originalRows[$id])) { // user in database
            $this->database->delete($this, ['id' => $id]);
            $this->rows[$id] = [];
            unset($this->originalRows[$id]);
        }
    }

    public function close()
    {
        foreach ($this->originalRows as $id => $originalRow) { // each row
            if ($this->rows[$id] != $originalRow)
                $this->database->update($this, $this->rows[$id], $originalRow);
        }
    }

}
