<?php

class eclStore_domain extends eclStore
{

    public string $name = 'domain';

    public array $fields = [
        // Indexing
        'id' => 'primary_key',
        // Class identifiers
        'name' => 'name/32',
        'aliase' => 'name/32',
        'status' => 'name/8'
    ];

    public $insertedData = [];
    private $rows = [];
    private $originalRows = [];
    private $indexByName = [];
    private $database = false;

    public function __construct()
    {
        global $io;
        if ($io->database->tableEnabled($this))
            $this->database = $io->database;
    }

    public function insert($data)
    {
        if ($this->database) {
            $id = $this->database->insert($this, $data);
            $data['id'] = $id;
            $this->rows[$id] = $data;
            $this->originalRows[$id] = $data;
            $this->indexByName[$data['name']] = $id;
            return $id;
        }
        return $data['name'];
    }

    public function &open($name)
    {
        global $io;
        if (isset($this->indexByName[$name])) {
            $id = $this->indexByName[$name];
            $return = &$this->rows[$id];
            return $return;
        }

        if ($this->database) {
            $rows = $this->database->select($this, ['name' => $name]);
            if ($rows) {
                $data = $rows[0];
                $id = $data['id'];
                $this->rows[$id] = $data;
                $this->originalRows[$id] = $data;
                $this->indexByName[$name] = $id;
                $return = &$this->rows[$id];
                return $return;
            }
        }
        $row = [];
        return $row;
    }

    public function &openById($id)
    {
        if (!isset($this->rows[$id])) {
            $rows = $this->database->select($this, ['id' => $id]);
            if ($rows) {
                $data = $rows[0];
                $this->rows[$id] = $data;
                $this->originalRows[$id] = $data;
                $this->indexByName[$data['name']] = $id;
                $return = &$this->rows[$id];
                return $return;
            }

            $this->rows[$id] = [];
            $this->indexByName[$id] = $id;
        }

        $return = &$this->rows[$id];
        return $return;
    }

    public function &openByAliase($aliase)
    { // function &
        $found = [];
        foreach ($this->rows as $id => $data) { // search rows in cache
            if ($data['aliase'] != $aliase)
                continue;

            $found = &$this->rows[$data['id']];
            return $found;
        } // search rows in cache

        if ($this->database) { // open from database
            $rows = $this->database->select($this, array('aliase' => $aliase));
            if ($rows) { // row found
                $data = $rows[0];
                $id = $data['id'];
                $this->rows[$id] = $data;
                $this->originalRows[$id] = $data;
                $this->indexByName[$data['name']] = $id;
                $return = &$this->rows[$id];
                return $return;
            } // row found
        } // open from database

        $return = [];
        return $return;
    } // function &

    public function getId($name)
    { // function getId
        if (!isset($this->indexByName[$name]))
            $this->open($name);
        if (isset($this->indexByName[$name]))
            return $this->indexByName[$name];
        else
            return false;
    } // function getId

    public function getName($id)
    { // function getName
        if (!isset($this->rows[$id]))
            $this->openById($id);
        if (isset($this->rows[$id]['name']))
            return $this->rows[$id]['name'];

        return '';
    } // function getName

    public function getStatus($name)
    { // function getStatus
        if (!isset($this->indexByName[$name]))
            $this->open($name);

        if (!isset($this->indexByName[$name]))
            return 0;

        $id = $this->indexByName[$name];
        $row = &$this->rows[$id];

        return $row['status'];
    } // function getStatus

    public function getAliase($name)
    { // function getAliase
        static $aliases = [];
        if (isset($aliases[$name]))
            return $aliases[$name];

        if (!isset($this->indexByName[$name]))
            $this->open($name);

        if (!isset($this->indexByName[$name]) or $this->indexByName[$name] == $name) { // not found
            $aliases[$name] = false;
            return false;
        } // not found

        $id = $this->indexByName[$name];
        $row = $this->rows[$id];

        if (isset($row['aliase']) and strlen($row['aliase']))
            $aliases[$name] = $row['aliase'];
        else
            $aliases[$name] = false;

        return $aliases[$name];
    } // function getAliase

    public function childrenNames()
    {
        $names = [];
        if($this->database){
            foreach ($this->database->select($this, ['status' => ' > 0']) as $row){
                $names[] = $row['name'];
        }
    }
        return $names;
    }

    public function delete($id)
    { // function delete
        if (isset($this->originalRows[$id])) { // domain in database
            $this->database->delete($this, array('id' => $id));
            $this->rows[$id] = [];
            unset($this->originalRows[$id]);
        } // domain in database
    } // function delete

    public function close()
    { // function close
        foreach ($this->originalRows as $id => $originalRow) { // each row
            if ($this->rows[$id] != $originalRow)
                $this->database->update($this, $this->rows[$id], $originalRow);
        } // each row
    } // function close

}