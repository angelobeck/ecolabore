
class eclRender_mediator {
    component;
    slot;
    scopes = [];
    rootNode = false;
    render = false;
    eventListeners = {};
    apis = {};
    trackedProperties = {};

    constructor(rootNode, component, slot) {
        this.rootNode = rootNode;
        var mediator = this;

        this.component = component;
        this.slot = rootNode.cloneChildren(slot);

        this.component.node = this.rootNode;

        this.component.dispatchEvent = (event) => {
            if (this.eventListeners['on' + event.type]) {
                const callName = this.eventListeners['on' + event.type];
                const mediator = this.rootNode.parent.mediator;
                if (mediator.component[callName])
                    mediator.component[callName](event);
            }
        };

        this.component.refresh = () => {
            this.rootNode.refresh(true);
            this.refresh = false;
        };

        this.component.api = (name) => {
            var mediator = this;
            if (mediator.apis[name] !== undefined && mediator.apis[name] !== null)
                null;
            else if (mediator.component[name] !== null && mediator.component[name] !== undefined)
                mediator.apis[name] = mediator.component[name];
            else
                mediator.apis[name] = false;

            Object.defineProperty(this.component, name, {
                get() {
                    return mediator.apis[name];
                },
                set(value) {
                    mediator.apis[name] = value;
                    if (!mediator.render) {
                        mediator.render = true;
                        setTimeout(() => {
                            if (mediator.render) {
                                mediator.render = false;
                                mediator.rootNode.refresh(true);
                                mediator.component.renderedCallback();
                            }
                        }, 20);
                    }
                }
            });
        };

        this.component.track = (name) => {
            var mediator = this;
            if (mediator.component[name] !== null && mediator.component[name] !== undefined)
                mediator.trackedProperties[name] = mediator.component[name];
            else
                mediator.trackedProperties[name] = false;

            Object.defineProperty(this.component, name, {
                get() {
                    return mediator.trackedProperties[name];
                },
                set(value) {
                    mediator.trackedProperties[name] = value;
                    if (!mediator.render) {
                        mediator.render = true;
                        setTimeout(() => {
                            if (mediator.render) {
                                mediator.render = false;
                                mediator.rootNode.refresh(true);
                                if (mediator.component.renderedCallback)
                                    mediator.component.renderedCallback();
                            }
                        }, 20);
                    }
                }
            });
        };
    }

    getProperty(path, returnString = false) {
        var current = this.component;
        var parts = path.split('.');
        var name = parts[0];
        if (!/^[a-zA-Z_][a-zA-Z0-9_]*$/.test(name))
            return '';
        if (name === 'this') {
            parts.shift();
        } else {
            for (let i = 0; i < this.scopes.length; i++) {
                let scope = this.scopes[i];
                if (name in scope) {
                    current = scope[name];
                    parts.shift();
                    break;
                }
            }
        }
        while (parts.length > 0) {
            name = parts.shift();
            if (!/^[a-zA-Z_][a-zA-Z0-9_]*$/.test(name))
                return '';
            if (/^[A-Z][a-zA-Z0-9_]*$/.test(name)) {
                const scopeName = name.charAt(0).toLowerCase() + name.substring(1);
                if (registeredClasses.eclScope[scopeName])
                    current = registeredClasses.eclScope[scopeName].getScope(page, current, parts.shift() || '');
                continue;
            }
            if (current.constructor.name === 'eclEngine_child') {
                if (current.data[name])
                    current = current.data[name];
                else if (current.data.text[name])
                    current = current.data.text[name];
                else if (name === 'child')
                    current = current.child;
                else
                    return '';
            } else if (current[name] === undefined || current[name] === null) {
                return '';
            } else {
                current = current[name];
            }
        }
        if (returnString) {
            if (current === true)
                return 'true';
            else if (current === false)
                return 'false';
            else if (Array.isArray(current))
                return '';
            else if (current === undefined)
                return '';
            else if (current === null)
                return '';
            else
                return current.toString();
        }
        return current;
    }

    setProperty(path, value) {
        this.component[path] = value;
    }

    getScope(node) {
        var name;
        var scope;
        var value = '';

        if (!node.staticAttributes['scope:name'] && !node.dinamicAttributes['scope:name'])
            return false;

        if (node.staticAttributes['scope:name'])
            name = node.staticAttributes['scope:name'];
        else
            name = this.getProperty(node.dinamicAttributes['scope:name']);

        if (node.staticAttributes['scope:value'])
            value = node.staticAttributes['scope:value'];
        else if (node.dinamicAttributes['scope:value'])
            value = this.getProperty(node.dinamicAttributes['scope:value']);

        if (!/[a-z][a-zA-Z0-9_]*/.test(name))
            return false;
        if (!registeredClasses.eclScope || !registeredClasses.eclScope[name])
            return false;

        scope = registeredClasses.eclScope[name].getScope(value);

        if (!scope)
            return false;

        var prefixedScope = {};
        var keys = Object.keys(scope);
        for (let i = 0; i < keys.length; i++) {
            const key = keys[i];
            prefixedScope[name + '_' + key] = scope[key];
        }
        return prefixedScope;
    }

}
