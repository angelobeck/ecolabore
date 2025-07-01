<?php

class eclStore_group extends eclStore
{ // class eclStore_user

    public string $name = 'group';

    public array $fields = [
        'id' => 'primary_key',
        'domain_id' => 'int/4',
        'name' => 'name/32',
        'text' => 'array',
        'details' => 'array'
    ];

}
