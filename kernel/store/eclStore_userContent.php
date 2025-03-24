<?php

class eclStore_userContent extends eclStore
{

    public string $name = 'user_content';

    public array $fields = [
        // Indexing
        'user_id' => 'int/4',
        'mode' => 'name/8',
        'parent_id' => 'int/4',
        'id' => 'primary_key',
        // Class identifiers
        'name' => 'name/32',
        'marker' => 'int/1',
        'status' => 'int/1',
        'access' => 'int/1',
        // Dates
        'created' => 'time',
        'updated' => 'time',
        'event_start' => 'time',
        'event_end' => 'time',
        'coments_last_update' => 'time',
        // More sort options
        'index' => 'int/2',
        'hits' => 'int/4',
        'value' => 'int/4',
        'spotlight' => 'int/1',
        // Contents
        'text' => 'array',
        'local' => 'array',
        'flags' => 'array',
        'extras' => 'array',
        'links' => 'array',
        'keywords' => 'keywords'
    ];

    // Index
    public array $index = [
        'user_find_children' => ['user_id', 'mode', 'parent_id']
    ];

    private array $indexByName = [];
    public array $indexByParent = [];
    public array $chargedParents = [];
    public array $chargedMode = [];
    private array $rows = [];
    private array $originalRows = [];
    private array $deletedRows = [];
    private array $notFound = [];
    private eclIo_database $database;

    public array $lastInsertedData = [];

    public function __construct()
    {
        global $io;
        if ($io->database->tableEnabled($this))
            $this->database = $io->database;
    }

    public function indexFoundRows($rows, $userName = 0)
    {
        $found = [];
        foreach ($rows as $data) {
            if ($data['name'][0] == ':')
                continue;

            $userId = $data['user_id'];
            $id = $data['id'];
            if (!isset($this->deletedRows[$userId][$id])) {
                if (!isset($this->rows[$userId][$id])) { // row not indexed
                    $this->rows[$userId][$id] = $data;
                    $this->originalRows[$userId][$id] = $data;
                    $this->indexByName[$userId][$data['name']] = $id;
                    $this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id] = $id;
                    $found[] = $data;
                } else {
                    $found[] = $this->rows[$userId][$id];
                }
            }
        }
        return $found;
    }

    public function insert($userId, &$data)
    {
        if (!is_int($userId) || !$this->database)
            return 0;

        $data['user_id'] = $userId;
        if (!isset($data['parent_id']))
            $data['parent_id'] = 0;
        $data['index'] = count($this->children($userId, $data['mode'], $data['parent_id']));
        if (!isset($data['name']) or !strlen($data['name']))
            $data['name'] = 't' . strval(TIME);
        $where = array('user_id' => $userId, 'name' => $data['name']);

        if ($this->database->select($this, $where, 1, ['id'])) {
            $test = 0;
            do { // loop names
                $test++;
                $name = $data['name'] . '-' . str_pad(strval($test), 3, '0', STR_PAD_LEFT);
                $where = array('user_id' => $userId, 'name' => $name);
            } // loop names
            while ($this->database->select($this, $where, 1, ['id']));
            $data['name'] = $name;
        } // prevent duplicated names

        $id = $this->database->insert($this, $data);
        $data['id'] = $id;
        $this->rows[$userId][$id] = $data;
        $this->originalRows[$userId][$id] = $data;
        $this->indexByName[$userId][$data['name']] = $id;
        $this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id] = $id;
        $this->lastInsertedData = $data;
        return $id;
    }

    public function &open($userId, $name, $access = 4)
    {
        if (!isset($this->indexByName[$userId][$name])) {
            if (isset($this->notFound[$userId][$name])) {
                $found = [];
                return $found;
            }
            $this->indexFoundRows($this->database->select($this, ['user_id' => $userId, 'name' => $name], 1));
        }

        $found = [];
        if (isset($this->indexByName[$userId][$name])) {
            $id = $this->indexByName[$userId][$name];
            $found = &$this->rows[$userId][$id];
            if ($found['access'] <= $access)
                return $found;
        } else
            $this->notFound[$userId][$name] = true;
        $empty = [];
        return $empty;
    }

    public function &openById($userId, $id, $access = 4)
    {
        if (!isset($this->rows[$userId][$id])) {
            $this->indexFoundRows($this->database->select($this, ['id' => $id]));
        }

        $found = [];
        if (isset($this->rows[$userId][$id])) {
            $found = &$this->rows[$userId][$id];

            if ($found['access'] <= $access)
                return $found;
        }
        $empty = [];
        return $empty;
    }

    public function &openChild($userId, $mode, $parentId, $name, $access = 4)
    {
        if (!isset($this->chargedParents[$userId][$mode][$parentId])) {
            $this->chargedParents[$userId][$mode][$parentId] = true;
            $this->indexFoundRows($this->database->select($this, [
                'user_id' => $userId,
                'mode' => $mode,
                'parent_id' => $parentId
            ]));
        }

        if (isset($this->indexByParent[$userId][$mode][$parentId])) {
            foreach ($this->indexByParent[$userId][$mode][$parentId] as $id) {
                if ($this->rows[$userId][$id]['name'] == $name) {
                    $found = &$this->rows[$userId][$id];
                    if ($found['access'] <= $access)
                        return $found;
                    $empty = [];
                    return $empty;
                }
            }
        }

        $found = [];
        return $found;
    }

    public function children($userId, $mode, $parentId, $access = 4, $max = 0, $offset = 0, $sort = 'index', $direction = 'asc')
    {
        if (!isset($this->chargedParents[$userId][$mode][$parentId])) {
            if (isset($this->chargedMode[$userId][$mode]))
                return [];

            $this->chargedParents[$userId][$mode][$parentId] = true;
            $this->indexFoundRows($this->database->select($this, [
                'user_id' => $userId,
                'mode' => $mode,
                'parent_id' => $parentId
            ]));
        }

        if (isset($this->indexByParent[$userId][$mode][$parentId])) { // children exists
            $sorted = [];
            $rows = $this->rows[$userId];
            foreach ($this->indexByParent[$userId][$mode][$parentId] as $id) {
                if ($rows[$id]['access'] <= $access)
                    $sorted[$rows[$id][$sort]][] = $rows[$id];
            }

            if ($direction == 'desc')
                krsort($sorted);
            else
                ksort($sorted);

            if ($max == 0)
                $max = count($this->indexByParent[$userId][$mode][$parentId]);
            else
                $max += $offset;

            $list = [];
            $i = -1;
            foreach ($sorted as $doubled) {
                foreach ($doubled as $item) {
                    $i++;
                    if ($i >= $offset and $i < $max)
                        $list[] = $item;
                    elseif ($i > $max)
                        break 2;
                }
            }

            return $list;
        }
        return [];
    }

    public function childrenNames($userId, $mode, $parentId, $access = 4, $max = 0, $offset = 0, $sort = 'index', $direction = 'asc')
    {
        $children = $this->children($userId, $mode, $parentId, $access, $max, $offset, $sort, $direction);
        $names = [];
        foreach ($children as $child) {
            $names[] = $child['name'];
        }
        return $names;
    }

    public function mode($userId, $mode)
    {
        if (!isset($this->chargedMode[$userId][$mode])) {
            $this->chargedMode[$userId][$mode] = $this->indexFoundRows($this->database->select($this, [
                'user_id' => $userId,
                'mode' => $mode
            ]));

            // index chargedParents
            if (!isset($this->chargedParents[$userId]))
                $this->chargedParents[$userId] = [];
            if (!isset($this->chargedParents[$userId][$mode]))
                $this->chargedParents[$userId][$mode] = [];

            foreach ($this->chargedMode[$userId][$mode] as $row) {
                if (!$row or !isset($row['id']) or !isset($row['parent_id']))
                    continue;

                if (!isset($this->chargedParents[$userId][$mode][$row['parent_id']]) or !is_array($this->chargedParents[$userId][$mode][$row['parent_id']]))
                    $this->chargedParents[$userId][$mode][$row['parent_id']] = [];

                $this->chargedParents[$userId][$mode][$row['parent_id']][$row['id']] = true;
                $this->indexByParent[$userId][$mode][$row['parent_id']][$row['id']] = $row['id'];
            }
        }

        return $this->chargedMode[$userId][$mode];
    }

    public function findMarker($userId, $marker, $mode = 'section')
    {
        static $markers = [];
        if (!isset($markers[$userId][$mode])) {
            if (!isset($markers[$userId]))
                $markers[$userId] = [];
            if (!isset($markers[$userId][$mode]))
                $markers[$userId][$mode] = [];
            foreach ($this->mode($userId, $mode) as $data) {
                if ($data['access'] > 30)
                    continue;
                if (!isset($markers[$userId][$mode][$data['marker']]))
                    $markers[$userId][$mode][$data['marker']] = $data['id'];
            }
        }
        if (isset($markers[$userId][$mode][$marker]))
            return $markers[$userId][$mode][$marker];

        return false;
    }

    public function search($where, $access = 4, $max = 0, $offset = 0, $sort = 'created', $direction = 'desc')
    {
        $rows = $this->indexFoundRows($this->database->select($this, $where));

        if ($rows) {
            $sorted = [];
            foreach ($rows as $row) {
                if ($row['access'] <= $access)
                    $sorted[$row[$sort]][] = $row;
            }

            if ($direction == 'desc')
                krsort($sorted);
            else
                ksort($sorted);

            if ($max == 0)
                $max = count($rows);
            else
                $max += $offset;

            $list = [];
            $i = -1;
            foreach ($sorted as $doubled) {
                foreach ($doubled as $item) {
                    $i++;
                    if ($i >= $offset and $i < $max)
                        $list[] = $item;
                    elseif ($i > $max)
                        break 2;
                }
            }

            return $list;
        }
        return [];
    }

    public function childrenReindex($userId, $mode, $parentId)
    {
        foreach ($this->children($userId, $mode, $parentId) as $index => $child) {
            $this->rows[$userId][$child['id']]['index'] = $index;
        }
    }

    public function pathway($userId, $name)
    {
        global $store;
        $pathway = [];

        if (is_int($name))
            $data = $this->openById($userId, $name);
        else
            $data = $this->open($userId, $name);
        if (!$data)
            return false;

        $id = $data['id'];
        do { // loop levels
            if (isset($this->rows[$userId][$id]))
                $data = $this->rows[$userId][$id];
            else
                $data = $this->openById($userId, $id);
            if (!$data)
                return false;

            if ($data['parent_id'] != 0 or $data['mode'] != 'section' or !isset($data['flags']['section_type']) or $data['flags']['section_type'] != 'menu')
                array_unshift($pathway, $data['name']);
            $id = $data['parent_id'];
            if ($id == 1)
                $id = 0;
            if (!$id and $data['mode'] != 'section' and $data['marker']) { // find special section
                $id = $this->findMarker($userId, $data['marker']);
                if (!$id)
                    return false;
            }
        }
        while ($id);
        array_unshift($pathway, $store->user->getName($userId));

        return $pathway;
    }

    public function delete($userId, $id)
    {
        if (defined('TRACKING_REMOVED_PAGES') and TRACKING_REMOVED_PAGES) { // do not remove page
            $data = &$this->openById($userId, $id);
            $data['access'] = 255;
            if (strlen($data['name']) == 32)
                $data['name'] = substr($data['name'], -2);
            $data['name'] = ':' . $data['name'];
            return;
        }

        if (isset($this->rows[$userId][$id])) {
            $data = $this->rows[$userId][$id];
            $this->deletedRows[$userId][$id] = $id;
            $this->rows[$userId][$id] = [];
            unset($this->originalRows[$userId][$id]);
            unset($this->indexByParent[$userId][$data['mode']][$data['parent_id']][$id]);
            unset($this->indexByName[$userId][$data['name']][$id]);
        }

        $this->database->delete($this, ['id' => $id]);
    }

    public function close()
    {
        foreach ($this->originalRows as $userId => $userRows) {
            foreach ($userRows as $id => $originalRow) {
                if ($this->rows[$userId][$id] != $originalRow) {
                    $this->database->update($this, $this->rows[$userId][$id], $originalRow);
                }
            }
        }

        $this->indexByName = [];
        $this->indexByParent = [];
        $this->chargedParents = [];
        $this->rows = [];
        $this->originalRows = [];
        $this->deletedRows = [];
        $this->notFound = [];
    }

}
