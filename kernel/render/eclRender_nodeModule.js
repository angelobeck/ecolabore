
class eclRender_nodeModule extends eclRender_node {
    endingElement;
    module;
    value = 'module';

    create(parentElement, insertBeforeMe) {
        this.endingElement = document.createComment(" module ");
        parentElement.insertBefore(this.endingElement, insertBeforeMe);
        this.generateModule(parentElement, this.endingElement);
    }

    generateModule(parentElement, insertBeforeMe) {
        this.module = this.findMySymbol();
        if (!this.module) {
            return;
        }
        var tokenizer = new eclRender_tokenizer();
        var parser = new eclRender_parser();

        var templateName = this.module.constructor.name;
        var template = templates[templateName];
        if (!template) {
            return;
        }
        var tokens = tokenizer.tokenize(template);
        parser.parse(this, tokens, this.module);

        this.createStaticAttributes();
        this.createDinamicAttributes();
        setTimeout(() => {
            this.component.beforeEvent();
            this.component.module.renderedCallback();
            this.component.afterEvent();
        }, 20);
        this.component.module.connectedCallback();
        this.component.module.refreshCallback();

        this.createChildren(this.children, parentElement, insertBeforeMe);
    }

    refresh(cancelRefreshCallback = false) {
        var module = this.findMySymbol();
        if (module !== this.module) {
            this.removeChildren(this.children);
            this.children = [];
            this.component.module.disconnectedCallback();

            this.module = module;
            this.generateModule(this.endingElement.parentElement, this.endingElement);
            return;
        }

        setTimeout(() => {
            this.component.beforeEvent();
            this.component.module.renderedCallback();
            this.component.afterEvent();
        }, 20);
        if (!cancelRefreshCallback)
            this.component.module.refreshCallback();
        this.refreshDinamicAttributes();
        this.refreshChildren(this.children);
    }

    remove() {
        this.removeChildren(this.children);
        this.component.module.disconnectedCallback();
        if (this.endingElement) {
            let parentElement = this.endingElement.parentElement;
            parentElement.removeChild(this.endingElement);
            this.endingElement = false;
            this.module = false;
            this.component.module = false;
        }
        this.children = this.component.slot;
    }

    createStaticAttributes() {
        for (let name in this.staticAttributes) {
            const value = this.staticAttributes[name];
            this.component.module[this.convertToCamelCase(name)] = value;
        }
    }

    createDinamicAttributes() {
        var names = Object.keys(this.dinamicAttributes);
        for (let i = 0; i < names.length; i++) {
            const name = names[i];
            if (name.startsWith("on")) {
                this.createEvent(name);
            } else if (name.indexOf(":") > 0) {
                continue;
            } else {
                const path = this.dinamicAttributes[name];
                const value = this.parent.component.getProperty(path);
                this.component.module[this.convertToCamelCase(name)] = value;
            }
        }
    }

    createEvent(type) {
        var callbackName = this.dinamicAttributes[type];
        this.component.eventListeners[type] = callbackName;
    }

    refreshDinamicAttributes() {
        for (let name in this.dinamicAttributes) {
            if (name.startsWith("on")) {
                continue;
            } else if (name.indexOf(":") > 0) {
                continue;
            } else {
                const path = this.dinamicAttributes[name];
                const value = this.parent.component.getProperty(path);
                this.component.module[this.convertToCamelCase(name)] = value;
            }
        }
    }

    findMySymbol() {
        var name;
        if (this.staticAttributes.name) {
            name = this.staticAttributes.name;
        } else if (this.dinamicAttributes.name) {
            name = this.parent.component.getProperty(this.dinamicAttributes.name);
        } else {
            return false;
        }

        return page.modules.createModule(name);
    }

    convertToCamelCase(name) {
        var parts = name.split('-');
        var camel = parts.shift().toLowerCase();
        while (parts.length > 0) {
            const part = parts.shift();
            camel += part.substring(0, 1).toUpperCase() + part.substring(1).toLowerCase();
        }
        return camel;
    }

}
