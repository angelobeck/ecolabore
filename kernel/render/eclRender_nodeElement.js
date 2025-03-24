
class eclRender_nodeElement extends eclRender_node {
    loopChildren = [];
    element;

    create(parentElement, insertBeforeMe) {
        var tagName = this.value.toUpperCase();
        this.element = document.createElement(tagName);
        parentElement.insertBefore(this.element, insertBeforeMe);
        this.createStaticAttributes();
        this.createDinamicAttributes();

        if (this.dinamicAttributes["for:each"]) {
            this.createLoop(this.element);
        } else {
            this.createChildren(this.children, this.element);
        }
    }

    refresh() {
        this.refreshDinamicAttributes();

        if (this.dinamicAttributes["for:each"]) {
            this.refreshLoop(this.element);
        } else {
            this.refreshChildren(this.children);
        }
    }

    remove() {
        if (this.dinamicAttributes["for:each"]) {
            while (this.loopChildren.length > 0) {
                this.removeChildren(this.loopChildren.shift());
            }
        } else {
            this.removeChildren(this.children);
        }
        if (this.element) {
            const parentElement = this.element.parentElement;
            parentElement.removeChild(this.element);
            this.element = false;
        }
    }

    createStaticAttributes() {
        for (let name in this.staticAttributes) {
            if (name.indexOf(":") >= 0) {
                continue;
            }
            let value = this.staticAttributes[name];
            this.element.setAttribute(name, value);
        }
    }

    createDinamicAttributes() {
        for (let name in this.dinamicAttributes) {
            const path = this.dinamicAttributes[name];
            if (name.startsWith("on")) {
                this.createEvent(name);
            } else if (name === "wire:element") {
                this.component.module[path] = this.element;
            } else if (name.indexOf(":") > 0) {
                continue;
            } else {
                const value = this.component.getProperty(path);
                if (!value) {
                    continue;
                }
                this.element.setAttribute(name, value);
            }
        }
    }

    createEvent(name) {
        var target = this.dinamicAttributes[name];
        this.element[name] = (event) => {
            this.component.beforeEvent();
            this.component.module[target](event);
            this.component.afterEvent();
        };
    }

    refreshDinamicAttributes() {
        for (let name in this.dinamicAttributes) {
            const path = this.dinamicAttributes[name];
            if (name.startsWith("on")) {
                continue;
            } else if (name === "wire:element") {
                this.component.module[path] = this.element;
                continue;
            } else if (name.indexOf(":") > 0) {
                continue;
            } else {
                const value = this.component.getProperty(path);
                if (name === "value") {
                    this.element.value = value;
                } else {
                    this.element.setAttribute(name, value);
                }
            }
        }
    }

}
