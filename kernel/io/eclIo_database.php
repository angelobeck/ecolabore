<?php

/*
 * Fields types
 * primary_key
 * int/1 int/2 int/4 int/8
 * time
 * name/255
 * password
 * hash
 * array
 * keywords
 * binary
 */

class eclIo_database
{

    private array $tables;
    private array $batchQuery = [];
    private string $databasePrefix = '';
    private string $client;
    private string $database;
    private $databaseString, $externalString, $databaseString2, $externalString2;

    public bool $encryptEnable = false;
    public string $cipher;
    public string $key;
    public string $ivLength;

    public bool $status = false;
    private PDO $pdo;
    public bool $verbose = true;
    public string $performed_queries = '';
    private string $last_query;

    public function __construct(eclEngine_io $io, string $database = '')
    {
        if (!DATABASE_ENABLED)
            return;

        try {
            if ($database) {
                $this->client = 'sqlite';
                $this->database = $database;
                $this->pdo = new PDO('sqlite:' . $database);
            } else {
                $this->client = DATABASE_CLIENT;
                $this->database = DATABASE_DB;
                if (DATABASE_CLIENT == 'sqlite') {
                    if (!is_dir(PATH_DATABASE))
                        mkdir(PATH_DATABASE);

                    $this->pdo = new PDO('sqlite:' . PATH_DATABASE . DATABASE_DB . '.db');
                } else {
                    $this->pdo = new PDO(DATABASE_CLIENT . ':host=' . DATABASE_HOST . ';dbname=' . DATABASE_DB, DATABASE_USER, DATABASE_PASSWORD);
                    $this->databasePrefix = DATABASE_PREFIX;
                }
            }
            $this->status = true;

            if (ENCRYPTION_ENABLED) {
                $this->encryptEnable = true;
                $this->cipher = ENCRYPTION_CIPHER;
                $this->key = base64_decode(ENCRYPTION_KEY);
                $this->ivLength = openssl_cipher_iv_length(ENCRYPTION_CIPHER);
            }

        } catch (PDOException $e) {
            if (defined('DATABASE_DISPLAY_ERRORS') and DATABASE_DISPLAY_ERRORS)
                print 'Database connection error: ' . $e->getMessage() . '<br>';
            if (defined('DATABASE_LOG_ERRORS') and DATABASE_LOG_ERRORS) {
                $string = '#On: ' . date('c s u') . CRLF
                    . '#client: ' . $this->client . ' database:' . $this->database . CRLF
                    . '#Database connection error: ' . $e->getMessage() . CRLF . CRLF;
                file_put_contents(SERVER_DATABASE_LOG_FILE, $string, FILE_APPEND);
            }
            $this->status = false;
        }
    }

    /*
    public function reconnect(): void
    {
        global $io;

        if (!$io->systemConstants->check('DATABASE_ENABLE') or !$io->systemConstants->constants['DATABASE_ENABLE'])
            return;

        try {
            $c = $io->systemConstants->constants;

            if ($c['DATABASE_CLIENT'] == 'sqlite') {
                if (!is_dir(PATH_DATABASE))
                    mkdir(PATH_DATABASE);
                $this->pdo = new PDO('sqlite:' . PATH_DATABASE . '.system.db');
            } else {
                $this->pdo = new PDO($c['DATABASE_CLIENT'] . ':host=' . $c['DATABASE_HOST'] . ';dbname=' . $c['DATABASE_DB'], $c['DATABASE_USER'], $c['DATABASE_PASSWORD']);
            }
            $this->status = true;
        } catch (PDOException $e) {
            $io->log->addMessage('Database connection error: ' . $e->getMessage(), __CLASS__);
            $this->status = false;
        }
    }
    */

    public function query(string $query): array
    {
        global $io;
        if (!$this->status or !is_string($query) or !isset($query[0]))
            return [];

        $this->performed_queries .= $query . CRLF;
        $result = $this->pdo->query($query);
        if ($this->pdo->errorCode() != '00000' and $this->verbose) {
            $error = $this->pdo->errorCode();
            $info = $this->pdo->errorInfo();

            $string = '#Date: ' . date('c s u') . CRLF
                . '#client: ' . $this->client . ' database:' . $this->database . CRLF
                . $query . CRLF
                . '#The error: ' . $error . CRLF;

            if (isset($info[2]))
                $string .= '# ' . $info[2] . CRLF;

            $string .= CRLF;

            $this->performed_queries .= '# Error' . CRLF;
            $this->performed_queries .= '# ' . $error . CRLF;
            if (isset($info[2]))
                $this->performed_queries .= '# ' . $info[2] . CRLF;

            if (defined('DATABASE_DISPLAY_ERRORS') and DATABASE_DISPLAY_ERRORS)
                print nl2br(eclIo_convert::htmlSpecialChars($string));
            if (defined('DATABASE_LOG_ERRORS') and DATABASE_LOG_ERRORS) { // log errors
                if (!is_dir(PATH_DATABASE))
                    mkdir(PATH_DATABASE);

                file_put_contents(PATH_DATABASE . '.database.log', $string, FILE_APPEND);
            }

            return [];
        }

        if (!is_object($result))
            return [];

        $return = [];
        foreach ($result as $row) {
            $return[] = $row;
        }

        return $return;
    }

    public function batchQuery(array $query): void
    {
        if (!is_array($query) or !count($query))
            return;

        $this->last_query = implode(";" . CRLF, $query);
        $this->performed_queries .= $this->last_query;
        foreach ($query as $call) {
            $this->pdo->query($call);
        }
    }

    public function insertId(): int
    {
        return intval($this->pdo->lastInsertId());
    }

    public function affectedRows(): int
    {
        if ($this->client == 'mysql') {
            if (is_array($this->pdo->query('mysql_affected_rows()'))) {
                foreach ($this->pdo->query('mysql_affected_rows()') as $row) {
                    return current($row);
                }
            } // is array
        }
        return 0;
    }

    public function error(): string
    {
        $error = $this->pdo->errorInfo();
        if ($error)
            return $error[2];
        return '';
    }

    public function tableEnabled(eclStore $table): bool
    {
        if (!$this->status)
            return false;
        if (!isset($this->tables)) {
            if ($this->client == 'mysql')
                $rows = $this->query('SHOW TABLES');
            else
                $rows = $this->query("SELECT `name` FROM sqlite_master WHERE `type`='table'");
            foreach ($rows as $row) {
                $this->tables[current($row)] = true;
            }
        }

        $table_name = $this->databasePrefix . $table->name;
        if (isset($this->tables[$table_name]))
            return true;

        $this->create($table);

        $this->tables = [];
        if ($this->client == 'mysql')
            $rows = $this->query('SHOW TABLES');
        else
            $rows = $this->query("SELECT `name` FROM sqlite_master WHERE `type`='table'");
        foreach ($rows as $row) { // each table
            $this->tables[current($row)] = true;
        }

        $table_name = $this->databasePrefix . $table->name;
        if (isset($this->tables[$table_name]))
            return true;

        return false;
    }

    public function create(eclStore $table): void
    {
        if (!count($table->fields))
            return;

        // Create table description
        $lines = [];
        foreach ($table->fields as $fieldName => $fieldTypeComposed) {
            [$fieldType, $fieldLength] = explode('/', $fieldTypeComposed . '/');
            switch ($fieldType) {
                case 'primary_key':
                    if ($this->client == 'mysql')
                        $lines[] = '`' . $fieldName . '` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';
                    else
                        $lines[] = '`' . $fieldName . '` INTEGER PRIMARY KEY';
                    break;

                case 'tinyint':
                    $lines[] = '`' . $fieldName . '` TINYINT UNSIGNED NOT NULL';
                    break;

                case 'mediumint':
                    $lines[] = '`' . $fieldName . '` MEDIUMINT UNSIGNED NOT NULL';
                    break;

                case 'int':
                case 'time':
                    if ($this->client == 'sqlite') {
                        $lines[] = '`' . $fieldName . '` INT UNSIGNED NOT NULL';
                    } else {
                        switch ($fieldLength) {
                            case '1':
                                $lines[] = '`' . $fieldName . '` TINYINT UNSIGNED NOT NULL';
                                break;

                            case '2':
                                $lines[] = '`' . $fieldName . '` SMALLINT UNSIGNED NOT NULL';
                                break;

                            case '8':
                                $lines[] = '`' . $fieldName . '` BIGINT UNSIGNED NOT NULL';
                                break;

                            case '4':
                            default:
                                $lines[] = '`' . $fieldName . '` INT UNSIGNED NOT NULL';
                                break;
                        }
                    }
                    break;

                case 'name':
                case 'password':
                case 'hash':
                    if ($fieldLength === '')
                        $fieldLength = 64;
                    if ($this->client == 'mysql')
                        $lines[] = '`' . $fieldName . '` CHAR(' . $fieldLength . ') BINARY NOT NULL';
                    else
                        $lines[] = '`' . $fieldName . '` BLOB NOT NULL';
                    break;

                case 'array':
                case 'binary':
                case 'keywords':
                    if ($this->client == 'mysql')
                        $lines[] = '`' . $fieldName . '` MEDIUMBLOB NOT NULL';
                    else
                        $lines[] = '`' . $fieldName . '` BLOB NOT NULL';
                    break;
            }
        }

        // The query
        $this->query('CREATE TABLE `' . $this->databasePrefix . $table->name . "` (" . CRLF
            . implode("," . CRLF, $lines)
            . CRLF . ")" . CRLF);

        // Index
        if (isset($table->index) and $table->index) {
            foreach ($table->index as $index_name => $index_fields) {
                $this->query('CREATE INDEX `' . $index_name . '` ON `' . $this->databasePrefix . $table->name . '` (`' . implode('`, `', $index_fields) . '`)');
            }
        }
    }

    public function insert(eclStore $table, array &$data): int
    {
        global $io;
        $fields = [];
        $values = [];

        foreach ($table->fields as $fieldName => $fieldTypeComposed) {
            [$fieldType] = explode('/', $fieldTypeComposed);
            switch ($fieldType) {
                case 'primary_key':
                    break;

                case 'tinyint':
                case 'mediumint':
                case 'int':
                    if (!isset($data[$fieldName]))
                        $data[$fieldName] = 0;

                    $fields[] = $fieldName;
                    $values[] = strval(intval($data[$fieldName]));
                    break;

                case 'name':
                    if (!isset($data[$fieldName]) or !preg_match('%^[a-zA-Z0-9@:./_-]*$%', $data[$fieldName]))
                        $data[$fieldName] = '';

                    $fields[] = $fieldName;
                    $values[] = "'" . $data[$fieldName] . "'";
                    break;

                case 'time':
                    if (!isset($data[$fieldName]) or !is_int($data[$fieldName]) or $data[$fieldName] == 0)
                        $data[$fieldName] = TIME;

                    $fields[] = $fieldName;
                    $values[] = strval(intval($data[$fieldName]));
                    break;

                case 'array':
                    $fields[] = $fieldName;
                    if (!isset($data[$fieldName]) or !is_array($data[$fieldName]))
                        $data[$fieldName] = [];

                    if (!$data[$fieldName])
                        $values[] = "''";
                    elseif (isset($data['encrypt']))
                        $values[] = "'" . $this->encrypt(serialize($data[$fieldName])) . "'";
                    else
                        $values[] = "'" . $this->stringToDatabase(serialize($data[$fieldName])) . "'";
                    break;

                case 'keywords':
                    $fields[] = $fieldName;
                    if (!isset($data[$fieldName]) or !is_string($data[$fieldName]))
                        $data[$fieldName] = '';

                    if (!isset($data[$fieldName][0]))
                        $values[] = "''";
                    else
                        $values[] = "'" . $this->filterKeywords($data[$fieldName]) . "'";
                    break;

                case 'hash':
                    if (!isset($data[$fieldName]) or !is_string($data[$fieldName]))
                        $data[$fieldName] = '';

                    $fields[] = $fieldName;
                    $values[] = "'" . $this->hash($data[$fieldName]) . "'";
                    break;

                case 'password':
                    if (!isset($data[$fieldName]) or !is_string($data[$fieldName]))
                        $data[$fieldName] = '';

                    $fields[] = $fieldName;
                    $values[] = "'" . $this->password($data[$fieldName]) . "'";
                    break;

                case 'binary':
                    $fields[] = $fieldName;
                    if (!isset($data[$fieldName]) or !is_string($data[$fieldName]))
                        $data[$fieldName] = '';

                    if (!isset($data[$fieldName][0]))
                        $values[] = "''";
                    else
                        $values[] = "'" . $this->stringToDatabase($data[$fieldName]) . "'";
                    break;

                default:
                    break;
            }
        }

        // The query
        $this->query('INSERT INTO `' . $this->databasePrefix . $table->name . '` (`'
            . implode('`, `', $fields) . '`) VALUES ('
            . implode(', ', $values) . ')');
        $data['id'] = $this->insertId();
        return $data['id'];
    }

    public function select(eclStore $table, array $where, int $limit = 0, array $columnsNames = []): array
    {
        $results = [];

        $conditions = [];
        foreach ($where as $fieldName => $fieldValue) {
            if (isset($table->fields[$fieldName])) {
                [$fieldType] = explode('/', $table->fields[$fieldName]);
                switch ($fieldType) {
                    case 'primary_key':
                    case 'tinyint':
                    case 'mediumint':
                    case 'int':
                    case 'time':
                        if (is_int($fieldValue))
                            $conditions[] = '`' . $fieldName . '`=' . strval($fieldValue);
                        elseif (is_string($fieldValue) and is_numeric($fieldValue))
                            $conditions[] = '`' . $fieldName . '`=' . $fieldValue;
                        elseif (is_string($fieldValue) and preg_match('%^[<=> ]*[0-9]+$%', trim($fieldValue)))
                            $conditions[] = '`' . $fieldName . '` ' . $fieldValue;
                        elseif (is_array($fieldValue)) {
                            $fieldValue = implode(', ', $fieldValue);
                            if (preg_match('%^[0-9, ]+$%', $fieldValue))
                                $conditions[] = '`' . $fieldName . '` IN(' . $fieldValue . ')';
                        }
                        break;

                    case 'name':
                        if (preg_match('%^[a-zA-Z0-9@:/._-]+$%', $fieldValue))
                            $conditions[] = '`' . $fieldName . "`='" . $fieldValue . "'";
                        break;

                    case 'hash':
                        $conditions[] = '`' . $fieldName . "`='" . $this->hash($fieldValue) . "'";
                        break;

                    case 'keywords':
                        foreach (explode(' ', $this->filterKeywords($fieldValue)) as $keyword) {
                            $conditions[] = '`' . $fieldName . "` LIKE('%" . $keyword . "%')";
                        }
                        break;
                }
            }
        }

        if ($conditions) {
            // The query
            if ($columnsNames) {
                foreach ($columnsNames as $fieldName) {
                    if (!isset($table->fields[$fieldName]))
                        continue;
                    $columns[] = '`' . $fieldName . '`';
                    $returnColumns[$fieldName] = $table->fields[$fieldName];
                }
            }
            if (isset($columns))
                $queryColumns = implode(', ', $columns);
            else
                $queryColumns = '*';
            if ($limit) {
                $queryLimit = ' LIMIT ' . strval($limit);
            } else {
                $queryLimit = '';
            }

            $rows = $this->query('SELECT ' . $queryColumns . ' FROM `' . $this->databasePrefix . $table->name . '` WHERE '
                . implode(' AND ', $conditions) . $queryLimit);

            if (!isset($returnColumns))
                $returnColumns = $table->fields;

            // Extract rows
            foreach ($rows as $row) {
                $data = [];
                foreach ($returnColumns as $fieldName => $fieldTypeGroup) {
                    [$fieldType] = explode('/', $fieldTypeGroup);
                    switch ($fieldType) {
                        case 'primary_key':
                        case 'tinyint':
                        case 'mediumint':
                        case 'int':
                        case 'time':
                            $data[$fieldName] = intval($row[$fieldName]);
                            break;

                        case 'array':
                            if (isset($row[$fieldName][0])) {
                                if ($row[$fieldName][0] == '-') {
                                    $data['encrypt'] = true;
                                    $data[$fieldName] = unserialize($this->decrypt($row[$fieldName]));
                                } else
                                    $data[$fieldName] = unserialize($this->stringFromDatabase($row[$fieldName]));
                            } else {
                                $data[$fieldName] = [];
                            }
                            break;

                        default:
                            if (isset($row[$fieldName][0]))
                                $data[$fieldName] = $this->stringFromDatabase($row[$fieldName]);
                            else
                                $data[$fieldName] = '';
                    }
                }
                $results[] = $data;
            }

            return $results;
        }
        return [];
    }

    public function update(eclStore $table, array $data, array $originalData): void
    {
        $id = intval($data['id'] ?? 0);
        if (!$id)
            return;

        $set = [];
        foreach ($table->fields as $fieldName => $fieldTypeComposed) {
            [$fieldType] = explode('/', $fieldTypeComposed);
            if (!isset($data[$fieldName]))
                $data[$fieldName] = '';
            if (!isset($originalData[$fieldName]) or $data[$fieldName] != $originalData[$fieldName]) {
                switch ($fieldType) {

                    case 'tinyint':
                    case 'mediumint':
                    case 'int':
                    case 'time':
                        $set[] = '`' . $fieldName . '`=' . strval(intval($data[$fieldName]));
                        break;

                    case 'name':
                        if (is_string($data[$fieldName]) and preg_match('%^[a-zA-Z0-9@:/._-]*$%', $data[$fieldName]))
                            $set[] = '`' . $fieldName . "`='" . $data[$fieldName] . "'";
                        break;

                    case 'array':
                        if (!is_array($data[$fieldName]))
                            $data[$fieldName] = [];
                        if (isset($data['encrypt']))
                            $set[] = '`' . $fieldName . "`='" . $this->encrypt(serialize($data[$fieldName])) . "'";
                        else
                            $set[] = '`' . $fieldName . "`='" . $this->stringToDatabase(serialize($data[$fieldName])) . "'";
                        break;

                    case 'hash':
                        if ($data[$fieldName] != $originalData[$fieldName])
                            $set[] = '`' . $fieldName . "`='" . $this->hash($data[$fieldName]) . "'";
                        break;

                    case 'password':
                        if ($data[$fieldName] != $originalData[$fieldName])
                            $set[] = '`' . $fieldName . "`='" . $this->password($data[$fieldName]) . "'";
                        break;

                    case 'keywords':
                        if (isset($data[$fieldName][0])) {
                            $keywords = $this->filterKeywords(strval($data[$fieldName]));
                            if (!isset($originalData[$fieldName]) or $keywords != $originalData[$fieldName])
                                $set[] = '`' . $fieldName . "`='" . $keywords . "'";

                            break;
                        }

                        if (isset($originalData[$fieldName][0]))
                            $set[] = '`' . $fieldName . "`=''";
                        break;

                    case 'binary':
                        if (!is_string($data[$fieldName]) or !isset($data[$fieldName][0]))
                            $set[] = '`' . $fieldName . "`=''";
                        else
                            $set[] = '`' . $fieldName . "`='" . $this->stringToDatabase($data[$fieldName]) . "'";
                        break;
                }
            }
        }

        if (count($set)) {
            $this->batchQuery[] = 'UPDATE `' . $this->databasePrefix . $table->name . '` SET '
                . implode(', ', $set) . ' WHERE `id`=' . $id;
        }
    }

    public function delete(eclStore $table, array $where): void
    {
        $conditions = [];
        foreach ($where as $fieldName => $field_value) {
            if (isset($table->fields[$fieldName])) {
                switch ($table->fields[$fieldName]) {
                    case 'primary_key':
                    case 'tinyint':
                    case 'mediumint':
                    case 'int':
                    case 'time':
                        if (is_numeric($field_value))
                            $conditions[] = '`' . $fieldName . '`=' . strval($field_value);
                        break;

                    case 'name':
                        if (preg_match('%^[a-zA-Z0-9@:/._-]*$%', $field_value))
                            $conditions[] = '`' . $fieldName . "`='" . $field_value . "'";
                        break;
                }
            }
        }

        if (!$conditions) {
            return;
        }

        // The query
        $this->query('DELETE FROM `' . $this->databasePrefix . $table->name . '` WHERE '
            . implode(' AND ', $conditions));
    }

    public function drop(eclStore $table): void
    {
        $this->query('DROP TABLE `' . $this->databasePrefix . $table->name . '`');
    }

    public function close()
    {
        global $io;
        $this->batchQuery($this->batchQuery);
        $this->batchQuery = [];
        // $io->log->addMessage ($this->performed_queries, 'database');
    }

    public function stringToDatabase($string)
    {
        if (!isset($this->externalString)) { // set replace sequence
            $this->externalString = array(
                '#',
                chr(0),
                chr(26),
                // chr (34),
                chr(39),
                chr(92)
            );

            $this->databaseString = array(
                '#c',
                '#0',
                '#z',
                // '#q',
                '#s',
                '#e'
            );
        }

        return str_replace($this->externalString, $this->databaseString, $string);
    }

    public function stringFromDatabase($string)
    {
        if (!isset($this->externalString2)) { // set replace sequence
            $this->externalString2 = array(
                chr(0),
                chr(26),
                chr(34),
                chr(39),
                chr(92)
            );

            $this->databaseString2 = array(
                '#0',
                '#z',
                '#q',
                '#s',
                '#e'
            );
        }
        if (strpos($string, '\\') === false)
            return str_replace('#c', '#', str_replace($this->databaseString2, $this->externalString2, $string));
        return stripslashes($string);
    }

    public static function filterKeywords(string $string): string
    {
        $result = eclIo_convert::slug($string);
        $words = explode('_', $result);
        $keywords = [];
        $validWords = [];
        foreach ($words as $word) { // each word
            $validWords[strlen($word)][] = $word;
        } // each word
        krsort($validWords);
        foreach ($validWords as $index => $group) { // each group
            if ($index < 3)
                break;

            foreach ($group as $word) { // each word
                $valid = true;
                foreach ($keywords as $value) { // each keyword
                    if (is_int(strpos($value, $word))) { // existing word
                        $valid = false;
                    } // existing word
                } // each keyword
                if ($valid)
                    $keywords[] = $word;
            } // each word
        } // each  group

        sort($keywords);
        return implode(' ', $keywords);
    }

    private function encrypt(string $string): string
    {
        if (!$this->encryptEnable)
            return $string;

        $iv = openssl_random_pseudo_bytes($this->ivLength);
        $trash = openssl_random_pseudo_bytes(rand(16, 32));
        $string = str_replace('-', '', $trash) . '-' . $string;

        $encoded = openssl_encrypt($string, $this->cipher, $this->key, 0, $iv);
        return '-' . base64_encode($iv) . '-' . $encoded;
    }

    private function decrypt(string $string): string
    {
        if (!$this->encryptEnable)
            return '';

        $pos = strpos($string, '-', 1);
        $iv = base64_decode(substr($string, 1, $pos));
        $encoded = substr($string, $pos + 1);
        $joint = openssl_decrypt($encoded, $this->cipher, $this->key, 0, $iv);
        $pos = strpos($joint, '-');
        return substr($joint, $pos + 1);
    }

    public function hash(string $string): string
    {
        if (!isset($string[0]))
            return '';

        if (!defined('ENCRYPTION_HASH'))
            return $string;

        return openssl_digest($string . base64_decode(ENCRYPTION_HASH), 'sha256', false);
    }

    public static function password(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public static function passwordCheck(string $plainPassword, string $hashPassword): bool
    {
        return password_verify($plainPassword, $hashPassword);
    }

}
