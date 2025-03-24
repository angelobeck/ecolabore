
class eclEngine_page {
    modules = {};
    cuts = {};
    rootNode;
    session = {};

    application;
    domain;
    user;

    reset() {
        this.modules = {
            layout: registeredClasses.eclMod.modLayout,
            list: registeredClasses.eclMod.modList,
            nav: registeredClasses.eclMod.modNav,
            title: registeredClasses.eclMod.modTitle
        }
    }

    route() {
        var path;
        path = this.getPathFromHash();

        if (path.length === 0) {
            path = [SYSTEM_APPLICATION_NAME];
        } else if (
            path[0].startsWith('-')
            && root.child(path[0].substring(1))
        ) {
            path[0] = path[0].substring(1);
        }

        if (path.length === 1) {
            path.push("-home");
        }

        this.domain = root.child(path[0]);

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

    getPathFromUrl() {
        var path = [];
        var href = window.location.href;
        var tail = href.substring(SYSTEM_BASE_URL.length);
        if (tail.endsWith("/")) {
            tail = tail.substring(0, -1);
        }
        if (SYSTEM_HOSTING_MODE === "single") {
            if (tail.length > 0) {
                path = tail.split("/");
            }
        }
        return path;
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

    navigate(url) {
        if (SYSTEM_NAVIGATION_MODE === "hash") {
            window.location = url;
        } else {
            window.history.pushState({}, "", url);
            init();
        }
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

    url(path) {
        if (path === true)
            return '#' + this.application.path.join('/');
        if (!Array.isArray(path))
            return '#';

        return "#" + path.join("/");
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
