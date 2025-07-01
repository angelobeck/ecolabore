<?php

class eclIo_scandir {
    private string $location;
    private array $files = [];
    private int $index = 0;

    public function __construct(string $location) {
$this->location = $location;
if(!is_dir($location))
return;


    }

    private function mapDir($location)
    {
        foreach(scandir($location) as $fileName) {

        }
    }

}