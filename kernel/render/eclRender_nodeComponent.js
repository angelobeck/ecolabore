
class eclRender_nodeComponent extends eclRender_node {
    endingElement;
    component;
    value = 'component';

    create(parentElement, insertBeforeMe) {
        this.endingElement = document.createComment(" component ");
        parentElement.insertBefore(this.endingElement, insertBeforeMe);
        this.generateComponent(parentElement, this.endingElement);
    }

    generateComponent(parentElement, insertBeforeMe) {
        this.component = this.findMySymbol();
        if (!this.component) {
            return;
        }
        var tokenizer = new eclRender_tokenizer();
        var parser = new eclRender_parser();

        var templateName = this.component.constructor.name;
        var template = templates[templateName];
        if (!template) {
            return;
        }
        var tokens = tokenizer.tokenize(template);
        parser.parse(this, tokens, this.component);

        this.createStaticAttributes();
        this.createDinamicAttributes();
        setTimeout(() => {
            if (this.mediator.component.renderedCallback)
                this.mediator.component.renderedCallback();
        }, 20);
        this.mediator.component.connectedCallback();
        this.mediator.component.refreshCallback();

        this.createChildren(this.children, parentElement, insertBeforeMe);
    }

    refresh(cancelRefreshCallback = false) {
        var component = this.findMySymbol();
        if (component !== this.component) {
            this.removeChildren(this.children);
            this.children = [];
            if (this.mediator.component && this.mediator.component.disconnectedCallback)
                this.mediator.component.disconnectedCallback();

            this.component = component;
            this.generateComponent(this.endingElement.parentElement, this.endingElement);
            return;
        }

        setTimeout(() => {
            this.mediator.component.renderedCallback();
        }, 20);
        if (!cancelRefreshCallback)
            this.mediator.component.refreshCallback();
        this.refreshDinamicAttributes();
        this.refreshChildren(this.children);
    }

    remove() {
        this.removeChildren(this.children);
        if (this.mediator.component && this.mediator.component.disconnectedCallback)
            this.mediator.component.disconnectedCallback();
        if (this.endingElement) {
            let parentElement = this.endingElement.parentElement;
            parentElement.removeChild(this.endingElement);
            this.endingElement = false;
            this.component = false;
            this.mediator.component = false;
        }
        this.children = this.mediator.slot;
    }

    createStaticAttributes() {
        for (let name in this.staticAttributes) {
            const value = this.staticAttributes[name];
            this.mediator.apis[this.convertToCamelCase(name)] = value;
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
                const value = this.parent.mediator.getProperty(path);
                this.mediator.apis[this.convertToCamelCase(name)] = value;
            }
        }
    }

    createEvent(type) {
        var callbackName = this.dinamicAttributes[type];
        this.mediator.eventListeners[type] = callbackName;
    }

    refreshDinamicAttributes() {
        for (let attributeName in this.dinamicAttributes) {
            if (attributeName.startsWith("on"))
                continue;
            if (attributeName.indexOf(":") > 0)
                continue;

            let name = this.convertToCamelCase(attributeName);
            const path = this.dinamicAttributes[attributeName];
            const value = this.parent.mediator.getProperty(path);
            this.mediator.apis[name] = value;
        }
    }

    findMySymbol() {
        var name;
        if (this.staticAttributes.name) {
            name = this.staticAttributes.name;
        } else if (this.dinamicAttributes.name) {
            name = this.parent.mediator.getProperty(this.dinamicAttributes.name);
        } else {
            return false;
        }

        return page.components.createComponent(name);
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
