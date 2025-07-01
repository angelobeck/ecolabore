<?php

class eclEndpoint
{
    public eclEngine_page $page;
    public string $name = '';
    public array $data = [];

    public function __construct(eclEngine_page $page, string $name)
    {
        $this->page = $page;
        $this->name = $name;
    }

    public function dispatch(array $received): array
    {
        return [
            'error' => [
                'message' => 'The endpoint ' . implode('/', $this->page->application->path) . '/_endpoint-' . $this->name . ' is invalid',
                'application' => $this->page->application->applicationName
            ]
        ];
    }

}
