<?php

class eclStore_session extends eclStore
{

    public string $name = 'session';

    public array $fields = [
        'id' => 'primary_key',
        'name' => 'name/8',
        'iv' => 'name/16',
        'created' => 'time',
        'updated' => 'time',
        'expires' => 'int/1',
        'session' => 'binary'
    ];

    private $rows = [];
    private $indexByName = [];
    private $database;

    public function __construct()
    {
        global $io;
        if ($io->database->tableEnabled($this))
            $this->database = $io->database;
    }

    public function &create(): array
    {
        $ivlen = openssl_cipher_iv_length(ENCRYPTION_CYPHER);
        $iv = bin2hex(openssl_random_pseudo_bytes($ivlen));
        $name = eclIo_convert::generateRandomName();
        $key = bin2hex(openssl_random_pseudo_bytes(16));

        $data = [
            'name' => $name,
            'key' => $key,
            'iv' => $iv,
            'created' => TIME,
            'updated' => TIME,
            'expires' => 0,
            'session' => []
        ];
        $this->rows[0] = &$data;
        $this->indexByName[$name] = 0;
        return $data;
    }

    public function &open($name, $hexadecimalEncodedKey)
    {
        if (isset($this->indexByName[$name])) {
            $id = $this->indexByName[$name];
            $return = &$this->rows[$id];
            return $return;
        } else if ($this->database) {
            $rows = $this->database->select($this, ['name' => $name]);
            if ($rows) {
                $row = $rows[0];
                $id = $row['id'];
                $key = eclIo_convert::hex2bin($hexadecimalEncodedKey);
                $iv = eclIo_convert::hex2bin($row['iv']);

                $data = [
                    'id' => $id,
                    'name' => $name,
                    'created' => $row['created'],
                    'updated' => TIME,
                    'key' => $hexadecimalEncodedKey,
                    'iv' => $row['iv'],
                    'expires' => $row['expires'],
                    'session' => unserialize(openssl_decrypt($row['session'], ENCRYPTION_CYPHER, $key, 0, $iv))
                ];
                $this->rows[$id] = $data;
                $this->indexByName[$name] = $id;
                $return = &$this->rows[$id];
                return $return;
            }
        }

        $return = [];
        return $return;
    }

    public function delete($id)
    {
        if (isset($this->rows[$id])) {
            $name = $this->rows[$id]['name'];
            $this->database->delete($this, ['id' => $id]);
            unset($this->rows[$id]);
            unset($this->indexByName[$name]);
        }
    }

    public function clearOldSessions(int $lifetime = 3600)
    {
        if (!$this->database) {
            return;
        }

        $expires = TIME - $lifetime;
        $rows = $this->database->select($this, ['expires' => 1, 'updated' => '< ' . $expires]);
        foreach ($rows as $row) {
            $this->database->delete($this, ['id' => $row['id']]);
        }
    }

    public function close()
    {
        foreach ($this->rows as $id => $row) {
            $serialized = serialize($row['session']);
            $iv = eclIo_convert::hex2bin($row['iv']);
            $key = eclIo_convert::hex2bin($row['key']);
            $data = [
                'id' => $id,
                'name' => $row['name'],
                'iv' => $row['iv'],
                'created' => $row['created'],
                'updated' => TIME,
                'expires' => $row['expires'],
                'session' => openssl_encrypt($serialized, ENCRYPTION_CYPHER, $key, 0, $iv)
            ];
            if ($id === 0)
                $this->database->insert($this, $data);
            else {
                $this->database->update($this, $data, []);
            }
        }
    }

}
