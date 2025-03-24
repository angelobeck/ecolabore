<?php

class eclMod_modHtml extends eclMod
{
        public string $lang;
        public array|string $title;
        public string $charset;
        public string $scripts = '';
        public string $applicationName = '';

        public function connectedCallback(): void
        {
                $this->lang = $this->page->lang;
                $this->charset = $this->page->charset;
                $this->applicationName = $this->page->domain->name;

                $title = $this->page->data['text']['label'] ?? $this->page->application->data['text']['label'] ?? '';
                $domainTitle = $this->page->domain->data['text']['title'] ?? 'Ecolabore';
                if ($title)
                        $this->title = $this->page->selectLanguage($title, ' - ', $domainTitle)['value'];
                else
                        $this->title = $this->page->selectLanguage($domainTitle)['value'];

                        $this->scripts = eclApp_systemJavascript_application::generate_script($this->page, true);
                                }

}
