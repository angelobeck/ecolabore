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
        return $this->error('The endpoint ' . implode('/', $this->page->application->path) . '/_endpoint-' . $this->name . ' is invalid');
    }

    public function error(string | array $message): array
    {
        if(is_string($message))
        return [
            'error' => [
                'message' => $message,
                'application' => $this->page->application->applicationName
            ]
        ];

        return [
            'error' => $message
        ];
    }

    public function response(mixed $response): array
    {
        return [
            'response' => $response
        ];
    }

}
