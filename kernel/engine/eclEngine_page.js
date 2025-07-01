
class eclEngine_page {
    cuts = {};
    rootNode;
    session = {};

    application;
    domain;
    user;

    pathMonitor = [];
    actions = [];

    constructor() {
        this.modules = new eclEngine_modules();
    }

    reset() {
        this.modules.reset();
        this.modules.layout = 'modLayout_main';
        this.modules.title = 'modTitle_main';
    }

    route() {
        var path = this.getPathFromUrl(location.href);

        this.domain = root.child(path[0]);

        if (path.length === 1 && this.domain.child('-home'))
            this.application = this.domain.child('-home');
        else
            this.application = this.routeSubfolders(root, path);
    }

    routeSubfolders(application, path) {
        if (path.length === 0) {
            return application;
        }
        if (application.ignoreSubfolders) {
            return application;
        }
        var name = path.shift();
        var child = application.child(name);
        if (child) {
            child = this.routeSubfolders(child, path);
            if (child) {
                return child;
            }
        }
        child = application.child('-default');
        if (child) {
            child.path = [...child.parent.path, name];
            child.name = name;
            child.reset();
            return this.routeSubfolders(child, path);
        }
        return null;
    }

    getPathFromHash() {
        var hash = location.hash;
        if (typeof (hash) !== "string" || hash.length <= 1) {
            return [];
        } else {
            return hash.substring(1).split("/");
        }
    }

    getPathFromUrl(href) {
        this.actions = [];

        var path = [];
        var hashPos = href.indexOf('#');
        if (hashPos > 0)
            href = href.substring(0, hashPos);
        if (href.startsWith('https://'))
            href = href.substring(8);
        else if (href.startsWith('http://'))
            href = href.substring(7);

        var searchPos = href.indexOf('?url=');
        if (searchPos > 0) {
            href = href.substring(searchPos + 5);
        } else if (SYSTEM_REWRITE_ENGINE) {
            href = href.substring(SYSTEM_HOST.length);
        } else {
            href = '';
        }

        if (href.endsWith("/"))
            href = href.substring(0, href.length - 1);

        if (href.length === 0)
            path = [];
        else
            path = href.split('/');

        if (SYSTEM_HOSTING_MODE === 'single')
            path.unshift(SYSTEM_DEFAULT_DOMAIN_NAME);

        if (path[path.length - 1].startsWith('_')) {
            this.processActions(path.pop());
        }
        return path;
    }

    processActions(actions) {
        var groups = actions.split('_');
        for (let i = 0; i < groups.length; i++) {
            let actionGroup = groups[i];
            if (actionGroup.length === 0)
                continue;

            let action = actionGroup.split('-');
            this.actions[action[0]] = action;
        }
    }

    adjustHistory(path) {
        if (path.length > 1 && path.length === this.pathMonitor.length) {
            this.pathMonitor = path;
        }
    }

    dispatch() {
        this.domain.dispatch(this);
        this.application.dispatch(this);
        this.application.view(this, 'main');
    }

    render() {
        if (this.application.data.text && this.application.data.text.title)
            document.title = this.selectLanguage(this.application.data.text.title).value;
        else if (this.domain.data.text && this.domain.data.text.title)
            document.title = this.selectLanguage(this.domain.data.text.title).value;
        else
            document.title = "Ecolabore";

        if (!this.rootNode) {
            while (document.body.children.length) {
                document.body.removeChild(document.body.lastElementChild);
            }
            this.rootNode = new eclRender_nodeModule();
            this.rootNode.staticAttributes.name = 'layout';
            this.rootNode.create(document.body);
        } else {
            this.rootNode.refresh();
        }
    }

    access(level) {
        if(level === 0)
            return true;

        return false;
    }

    selectLanguage(text) {
        if (typeof (text) === "string") {
            return { value: text, lang: this.lang };
        } else if (typeof (text) === "number") {
            return { value: text.toString(), lang: this.lang };
        }
        if (Array.isArray(text)) {
            return { value: "", lang: this.lang };
        }
        if (text[this.lang]) {
            return { ...text[this.lang], lang: this.lang };
        }
        for (let lang in text) {
            return { ...text[lang], lang: lang };
        }
        return { value: "", lang: this.lang };
    }

    url(fromPath, language = false, actions = '') {
        var host = location.protocol + '//' + SYSTEM_HOST;
        if (!SYSTEM_REWRITE_ENGINE)
            host += SYSTEM_SCRIPT_NAME + '?url=';

        var path;
        if (!Array.isArray(fromPath))
            path = [...this.application.path];
        else
            path = [...fromPath];

        if (actions !== '')
            path.push(actions);

        if (SYSTEM_HOSTING_MODE == 'single' && path[0] === SYSTEM_DEFAULT_DOMAIN_NAME)
            path.shift();

        if (path.length)
            return host + path.join("/");
        else
            return host;
    }

    createModule(name) {
        var data = store.staticContent.open(name);
        if (data.flags && data.flags.module && registeredClasses.eclMod[data.flags.module]) {
            let moduleConstructor = registeredClasses.eclMod[data.flags.module];
            let module = new moduleConstructor(this, data);
            return module;
        }
        return new eclMod(this, data);
    }

}
