<?php

class eclStore_userGroup extends eclStore
{

    public string $name = 'user_group';

    public array $fields = [
        // Indexing
        'group_id' => 'int/4',
        'id' => 'primary_key',
        'user_id' => 'int/4',
    ];

    // Index
    public array $index = [
        'group_find_user' => ['user_id'],
        'group_find_group' => ['group_id']
    ];

}
