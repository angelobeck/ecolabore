
class eclRender_nodeTag extends eclRender_node {
    endingElement;
    moduleSymbol;

    create(parentElement, insertBeforeMe) {
        this.endingElement = document.createComment(" tag ");
        parentElement.insertBefore(this.endingElement, insertBeforeMe);
        this.generateModule(parentElement, this.endingElement);
    }

    generateModule(parentElement, insertBeforeMe) {
        this.moduleSymbol = registeredClasses.eclTag[this.value];
        var module = new this.moduleSymbol();

        var tokenizer = new eclRender_tokenizer();
        var parser = new eclRender_parser();

        var templateName = module.constructor.name;
                var template = templates[templateName];
        if (!template) {
            return;
        }
        var tokens = tokenizer.tokenize(template);
        parser.parse(this, tokens, module);

        this.createStaticAttributes();
        this.createDinamicAttributes();
        setTimeout(() => {
            this.component.beforeEvent();
            this.component.module.renderedCallback();
            this.component.afterEvent();
        }, 20);
        this.component.module.connectedCallback();
        this.createChildren(this.children, parentElement, insertBeforeMe);
    }

    refresh() {
        this.refreshDinamicAttributes()
        setTimeout(() => {
            this.component.beforeEvent();
            this.component.module.renderedCallback();
            this.component.afterEvent();
        }, 20);
        this.refreshChildren(this.children);
    }

    remove() {
        this.removeChildren(this.children);
        this.component.module.disconnectedCallback();
        if (this.endingElement) {
            let parentElement = this.endingElement.parentElement;
            parentElement.removeChild(this.endingElement);
            this.endingElement = false;
        }
        this.children = [];
    }

    createStaticAttributes() {
        for (let name in this.staticAttributes) {
            const value = this.staticAttributes[name];
            this.component.module[name] = value;
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
                this.component.module[name] = value;
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
                this.component.module[name] = value;
            }
        }
    }

}
