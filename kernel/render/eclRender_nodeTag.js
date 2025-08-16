
class eclRender_nodeTag extends eclRender_node {
    endingElement;
    moduleSymbol;
    module;

    create(parentElement, insertBeforeMe) {
        this.endingElement = document.createComment(" tag ");
        parentElement.insertBefore(this.endingElement, insertBeforeMe);
        this.generateModule(parentElement, this.endingElement);
    }

    generateModule(parentElement, insertBeforeMe) {
        var moduleName;
        if (this.value === 'tag') {
            if (this.staticAttributes.name)
                moduleName = this.staticAttributes.name;
            else if (this.dinamicAttributes.name)
                moduleName = this.component.getProperty(this.dinamicAttributes.name);
        } else {
            moduleName = registeredTags[this.value];
        }

        if (!registeredClasses.eclMod[moduleName])
            return;

        this.moduleSymbol = registeredClasses.eclMod[moduleName];
        this.module = new this.moduleSymbol();
        var tokenizer = new eclRender_tokenizer();
        var parser = new eclRender_parser();

        var templateName = this.module.constructor.name;
        var template = templates[templateName];
        if (!template) {
            return;
        }

        if (this.parent)
            this.parent.component.childComponents.push(this.module);

        var tokens = tokenizer.tokenize(template);
        parser.parse(this, tokens, this.module);

        this.createStaticAttributes();
        this.createDinamicAttributes();
        setTimeout(() => {
            this.component.module.renderedCallback();
        }, 20);
        this.component.module.connectedCallback();
        this.component.module.refreshCallback();

        this.createChildren(this.children, parentElement, insertBeforeMe);
    }

    refresh() {
        setTimeout(() => {
            this.component.module.renderedCallback();
        }, 20);
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
        }
        this.children = this.component.slot;

        if (this.parent) {
            let children = this.parent.component.childComponents;
            for (let i = 0; i < children.length; i++) {
                if (children[i] === this.module)
                    children.slice(i, 1);
            }
        }
    }

    createStaticAttributes() {
        for (let name in this.staticAttributes) {
            const value = this.staticAttributes[name];
            this.component.apis[this.convertToCamelCase(name)] = value;
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
                this.component.apis[this.convertToCamelCase(name)] = value;
            }
        }
    }

    createEvent(type) {
        var callbackName = this.dinamicAttributes[type];
        this.component.eventListeners[type] = callbackName;
    }

    refreshDinamicAttributes() {
        for (let attributeName in this.dinamicAttributes) {
            if (attributeName.startsWith("on"))
                continue;
            if (attributeName.indexOf(":") > 0)
                continue;

            let name = this.convertToCamelCase(attributeName);
            const path = this.dinamicAttributes[attributeName];
            const value = this.parent.component.getProperty(path);
            this.component.apis[name] = value;
        }
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
