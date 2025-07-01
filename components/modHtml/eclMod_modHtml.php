<?php

class eclMod_modHtml extends eclMod
{
        public string $lang;
        public array|string $title;
        public string $charset;
        public string $styleSrc;
        public string $scripts = '';

        public string $host = SERVER_HOST;
        public string $scriptName = SERVER_SCRIPT_NAME;
        public string $rewriteEngine = 'false';
        public string $hostingMode = SERVER_HOSTING_MODE;
        public string $defaultDomainName = DEFAULT_DOMAIN_NAME;
        public string $defaultLanguage = DEFAULT_LANGUAGE;

        public function connectedCallback(): void
        {
                $this->lang = $this->page->lang;
                $this->charset = $this->page->charset;
                $this->styleSrc = $this->page->url([$this->page->domain->name, '-styles', 'application.css']);
                $this->rewriteEngine = SERVER_REWRITE_ENGINE ? 'true' : 'false';

                $title = $this->page->data['text']['label'] ?? $this->page->application->data['text']['label'] ?? '';
                $domainTitle = $this->page->domain->data['text']['title'] ?? 'Ecolabore';
                if ($title)
                        $this->title = $this->page->selectLanguage($title, ' - ', $domainTitle)['value'];
                else
                        $this->title = $this->page->selectLanguage($domainTitle)['value'];

                $styleCache = PATH_CACHE . PACK_NAME . PACK_TIME . '.css';
                if (is_file($styleCache)) {
                                $this->styleSrc = $this->page->protocol . '//' . SERVER_HOST . 'cache/' . PACK_NAME . PACK_TIME . '.js';
                }
                $scriptCache = PATH_CACHE . PACK_NAME . PACK_TIME . '.js';
                if (is_file($scriptCache)) {
                        $src = $this->page->protocol . '//' . SERVER_HOST . 'cache/' . PACK_NAME . PACK_TIME . '.js';
$this->scripts = '<script src="' . $src . '"></script>';
                } else {
                        $this->scripts = eclApp_systemJavascript_application::generate_script($this->page, true);
                }
        }

}
