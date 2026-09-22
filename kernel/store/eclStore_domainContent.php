<?php

class eclStore_domainContent extends eclStore
{

    public string $name = 'domain_content';

    public array $fields = [
        // Indexing
        'domain_id' => 'int/4',
        'mode' => 'name/8',
        'parent_id' => 'int/4',
        'id' => 'primary_key',
        'master_id' => 'int/4',
        'subscription_id' => 'int/4',
        'place_id' => 'int/4',
        'user_id' => 'int/4',
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
        'details' => 'array',
        'flags' => 'array',
        'extras' => 'array',
        'files' => 'array',
        'links' => 'array',
        'keywords' => 'keywords'
    ];

    // Index
    public array $index = [
        'domain_find_children' => ['domain_id', 'mode', 'parent_id'],
        'domain_find_name' => ['domain_id', 'name']
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

    public function indexFoundRows($rows, $domainName = 0)
    {
        $found = [];
        foreach ($rows as $data) {
            if ($data['name'][0] == ':')
                continue;

            if (!$data['domain_id'])
                $data['domain_id'] = $domainName;
            $domainId = $data['domain_id'];
            $id = $data['id'];
            if (!isset($this->deletedRows[$domainId][$id])) {
                if (!isset($this->rows[$domainId][$id])) { // row not indexed
                    $this->rows[$domainId][$id] = $data;
                    $this->originalRows[$domainId][$id] = $data;
                    $this->indexByName[$domainId][$data['name']] = $id;
                    $this->indexByParent[$domainId][$data['mode']][$data['parent_id']][$id] = $id;
                    $found[] = $data;
                } // row not indexed
                else
                    $found[] = $this->rows[$domainId][$id];
            } // row not deleted
        } // each row
        return $found;
    }

    public function insert($domainId, &$data)
    {
        if (!is_int($domainId) || !$this->database)
            return 0;

        $data['domain_id'] = $domainId;
        if (!isset($data['parent_id']))
            $data['parent_id'] = 0;
        $data['index'] = count($this->children($domainId, $data['mode'], $data['parent_id']));
        if (!isset($data['name']) or !strlen($data['name']))
            $data['name'] = 't' . strval(TIME);
        $where = array('domain_id' => $domainId, 'name' => $data['name']);

        if ($this->database->select($this, $where, 1, ['id'])) {
            $test = 0;
            do { // loop names
                $test++;
                $name = $data['name'] . '-' . str_pad(strval($test), 3, '0', STR_PAD_LEFT);
                $where = array('domain_id' => $domainId, 'name' => $name);
            } // loop names
            while ($this->database->select($this, $where, 1, ['id']));
            $data['name'] = $name;
        } // prevent duplicated names

        $id = $this->database->insert($this, $data);
        $data['id'] = $id;
        $this->rows[$domainId][$id] = $data;
        $this->originalRows[$domainId][$id] = $data;
        $this->indexByName[$domainId][$data['name']] = $id;
        $this->indexByParent[$domainId][$data['mode']][$data['parent_id']][$id] = $id;
        $this->lastInsertedData = $data;
        return $id;
    }

    public function &open($domainId, $name, $access = 4)
    {
        if (!isset($this->indexByName[$domainId][$name])) {
            if (isset($this->notFound[$domainId][$name])) {
                $found = [];
                return $found;
            }
            $this->indexFoundRows($this->database->select($this, ['domain_id' => $domainId, 'name' => $name], 1));
        }

        $found = [];
        if (isset($this->indexByName[$domainId][$name])) {
            $id = $this->indexByName[$domainId][$name];
            $found = &$this->rows[$domainId][$id];
            if ($found['access'] <= $access)
                return $found;
        } else
            $this->notFound[$domainId][$name] = true;
        $empty = [];
        return $empty;
    }

    public function &openById($domainId, $id, $access = 4)
    {
        if (!isset($this->rows[$domainId][$id])) {
            $this->indexFoundRows($this->database->select($this, ['id' => $id]));
        }

        $found = [];
        if (isset($this->rows[$domainId][$id])) {
            $found = &$this->rows[$domainId][$id];

            if ($found['access'] <= $access)
                return $found;
        }
        $empty = [];
        return $empty;
    }

    public function &openChild($domainId, $mode, $parentId, $name, $access = 4)
    {
        if (!isset($this->chargedParents[$domainId][$mode][$parentId])) {
            $this->chargedParents[$domainId][$mode][$parentId] = true;
            $this->indexFoundRows($this->database->select($this, [
                'domain_id' => $domainId,
                'mode' => $mode,
                'parent_id' => $parentId
            ]));
        }

        if (isset($this->indexByParent[$domainId][$mode][$parentId])) {
            foreach ($this->indexByParent[$domainId][$mode][$parentId] as $id) {
                if ($this->rows[$domainId][$id]['name'] == $name) {
                    $found = &$this->rows[$domainId][$id];
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

    public function children($domainId, $mode, $parentId, $access = 4, $max = 0, $offset = 0, $sort = 'index', $direction = 'asc')
    {
        if (!isset($this->chargedParents[$domainId][$mode][$parentId])) {
            if (isset($this->chargedMode[$domainId][$mode]))
                return [];

            $this->chargedParents[$domainId][$mode][$parentId] = true;
            $this->indexFoundRows($this->database->select($this, [
                'domain_id' => $domainId,
                'mode' => $mode,
                'parent_id' => $parentId
            ]));
        }

        if (isset($this->indexByParent[$domainId][$mode][$parentId])) { // children exists
            $sorted = [];
            $rows = $this->rows[$domainId];
            foreach ($this->indexByParent[$domainId][$mode][$parentId] as $id) {
                if ($rows[$id]['access'] <= $access)
                    $sorted[$rows[$id][$sort]][] = $rows[$id];
            }

            if ($direction == 'desc')
                krsort($sorted);
            else
                ksort($sorted);

            if ($max == 0)
                $max = count($this->indexByParent[$domainId][$mode][$parentId]);
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

    public function childrenNames($domainId, $mode, $parentId, $access = 4, $max = 0, $offset = 0, $sort = 'index', $direction = 'asc')
    {
        $children = $this->children($domainId, $mode, $parentId, $access, $max, $offset, $sort, $direction);
        $names = [];
        foreach ($children as $child) {
            $names[] = $child['name'];
        }
        return $names;
    }

    public function mode($domainId, $mode)
    {
        if (!isset($this->chargedMode[$domainId][$mode])) {
            $this->chargedMode[$domainId][$mode] = $this->indexFoundRows($this->database->select($this, [
                'domain_id' => $domainId,
                'mode' => $mode
            ]));

            // index chargedParents
            if (!isset($this->chargedParents[$domainId]))
                $this->chargedParents[$domainId] = [];
            if (!isset($this->chargedParents[$domainId][$mode]))
                $this->chargedParents[$domainId][$mode] = [];

            foreach ($this->chargedMode[$domainId][$mode] as $row) {
                if (!$row or !isset($row['id']) or !isset($row['parent_id']))
                    continue;

                if (!isset($this->chargedParents[$domainId][$mode][$row['parent_id']]) or !is_array($this->chargedParents[$domainId][$mode][$row['parent_id']]))
                    $this->chargedParents[$domainId][$mode][$row['parent_id']] = [];

                $this->chargedParents[$domainId][$mode][$row['parent_id']][$row['id']] = true;
                $this->indexByParent[$domainId][$mode][$row['parent_id']][$row['id']] = $row['id'];
            }
        }

        return $this->chargedMode[$domainId][$mode];
    }

    public function findMarker($domainId, $marker, $mode = 'section')
    {
        static $markers = [];
        if (!isset($markers[$domainId][$mode])) {
            if (!isset($markers[$domainId]))
                $markers[$domainId] = [];
            if (!isset($markers[$domainId][$mode]))
                $markers[$domainId][$mode] = [];
            foreach ($this->mode($domainId, $mode) as $data) {
                if ($data['access'] > 30)
                    continue;
                if (!isset($markers[$domainId][$mode][$data['marker']]))
                    $markers[$domainId][$mode][$data['marker']] = $data['id'];
            }
        }
        if (isset($markers[$domainId][$mode][$marker]))
            return $markers[$domainId][$mode][$marker];

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

    public function childrenReindex($domainId, $mode, $parentId)
    {
        foreach ($this->children($domainId, $mode, $parentId) as $index => $child) {
            $this->rows[$domainId][$child['id']]['index'] = $index;
        }
    }

    public function pathway($domainId, $name)
    {
        global $store;
        $pathway = [];

        if (is_int($name))
            $data = $this->openById($domainId, $name);
        else
            $data = $this->open($domainId, $name);
        if (!$data)
            return false;

        $id = $data['id'];
        do { // loop levels
            if (isset($this->rows[$domainId][$id]))
                $data = $this->rows[$domainId][$id];
            else
                $data = $this->openById($domainId, $id);
            if (!$data)
                return false;

            if ($data['parent_id'] != 0 or $data['mode'] != 'section' or !isset($data['flags']['section_type']) or $data['flags']['section_type'] != 'menu')
                array_unshift($pathway, $data['name']);
            $id = $data['parent_id'];
            if ($id == 1)
                $id = 0;
            if (!$id and $data['mode'] != 'section' and $data['marker']) { // find special section
                $id = $this->findMarker($domainId, $data['marker']);
                if (!$id)
                    return false;
            }
        }
        while ($id);
        array_unshift($pathway, $store->domain->getName($domainId));

        return $pathway;
    }

    public function delete($domainId, $id)
    {
        if (defined('TRACKING_REMOVED_PAGES') and TRACKING_REMOVED_PAGES) { // do not remove page
            $data = &$this->openById($domainId, $id);
            $data['access'] = 255;
            if (strlen($data['name']) == 32)
                $data['name'] = substr($data['name'], -2);
            $data['name'] = ':' . $data['name'];
            return;
        }

        if (isset($this->rows[$domainId][$id])) {
            $data = $this->rows[$domainId][$id];
            $this->deletedRows[$domainId][$id] = $id;
            $this->rows[$domainId][$id] = [];
            unset($this->originalRows[$domainId][$id]);
            unset($this->indexByParent[$domainId][$data['mode']][$data['parent_id']][$id]);
            unset($this->indexByName[$domainId][$data['name']][$id]);
        }

        $this->database->delete($this, ['id' => $id]);
    }

    public function close()
    {
        foreach ($this->originalRows as $domainId => $domainRows) {
            foreach ($domainRows as $id => $originalRow) {
                if ($this->rows[$domainId][$id] != $originalRow) {
                    $this->database->update($this, $this->rows[$domainId][$id], $originalRow);
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
