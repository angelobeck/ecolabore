
class eclEngine_application {
    name;
    applicationName;
    parent;

    map = [];
    data = [];
    access = 0;
    path;
    ignoreSubfolders = false;
    domainId = 0;
    userId = 0;

    childrenByName = [];
    allChildren = [];
    allChildrenIsLoaded = false;

    constructor(parent = false, applicationName = 'root', name = '-root') {
        this.parent = parent;
        this.applicationName = applicationName;
        this.name = name;
        if (parent) {
            this.access = parent.access;
            this.path = [...parent.path, name];
            this.domainId = parent.domainId;
            this.userId = parent.userId;
        } else {
            this.path = [];
        }

        const helper = registeredClasses.eclApp[applicationName];
        if (helper.map) {
            this.map = helper.map;
        }
        if (helper.content) {
            this.data = store.staticContent.open(helper.content);
        }

        helper.constructorHelper(this);
    }

    child(name) {
        if (this.childrenByName[name]) {
            return this.childrenByName[name];
        }
        if (this.allChildrenIsLoaded) {
            return false;
        }
        for (let i = 0; i < this.map.length; i++) {
            const applicationName = this.map[i];
            if (!isset(registeredClasses.eclApp[applicationName]))
                continue;

            const helper = registeredClasses.eclApp[applicationName];
            if (helper.name) {
                if (helper.name != name) {
                    continue;
                }
            } else if (!helper.isChild(this, name)) {
                continue;
            }

            this.childrenByName[name] = new eclEngine_application(this, applicationName, name);
            return this.childrenByName[name];
        }
        return false;
    }

    children() {
        if (this.allChildrenIsLoaded) {
            return this.allChildren;
        }

        this.allChildrenIsLoaded = true;
        for (let i = 0; i < this.map.length; i++) {
            const applicationName = this.map[i];
            if (!isset(registeredClasses.eclApp[applicationName]))
                continue;

            const helper = registeredClasses.eclApp[applicationName];
            let names = [];
            if (helper.name) {
                names = [helper.name];
            } else {
                names = helper.childrenNames(this);
            }
            for (let j = 0; j < names.length; j++) {
                let name = names[j];
                let child;
                if (this.childrenByName[name]) {
                    child = this.childrenByName[name];
                } else {
                    child = new eclEngine_application(this, applicationName, name);
                    this.childrenByName[name] = child;
                }
                this.allChildren.push(child);
            }
        }

        return this.allChildren;
    }

    reset() {
        this.childrenByName = [];
        this.allChildren = [];
        this.allChildrenIsLoaded = false;
    }

    dispatch(page) {
        const helper = registeredClasses.eclApp[this.applicationName];
        helper.dispatch(page);
    }

    view(page, view) {
        const helper = registeredClasses.eclApp[this.applicationName];
        var viewName = 'view_' + view;
        helper[viewName](page);
    }

}
