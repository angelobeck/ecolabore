
class eclRender_component {
    module;
    slot;
    scopes = [];
    rootNode = false;
    trackedProperties = {};
    trackedPropertyChanged = false;
    regularTrackedProperties;
    eventListeners = {};

    constructor(rootNode, module, slot) {
        this.rootNode = rootNode;
        this.module = module;
        this.slot = rootNode.cloneChildren(slot);

        this.module.dispatchEvent = (event) => {
            if (this.eventListeners['on' + event.type]) {
                const callName = this.eventListeners['on' + event.type];
                const component = this.rootNode.parent.component;
                component.beforeEvent();
                component.module[callName](event);
                component.afterEvent();
            }
        };

        this.module.refresh = () => {
            this.rootNode.refresh(true);
            this.trackedPropertyChanged = false;
        };

        this.module.track = (name) => {
            var component = this;
            if (component.module[name] !== null || component.module[name] !== undefined)
                component.trackedProperties[name] = component.module[name];
            else
                component.trackedProperties[name] = false;

            Object.defineProperty(this.module, name, {
                get() {
                    return component.trackedProperties[name];
                },
                set(value) {
                    component.trackedProperties[name] = value;
                    if (!component.trackedPropertyChanged) {
                        component.trackedPropertyChanged = true;
                        setTimeout(() => {
                            if (component.trackedPropertyChanged) {
                                component.rootNode.refresh(true);
                                component.beforeEvent();
                                component.module.renderedCallback();
                                component.afterEvent();
                            }
                        }, 20);
                    }
                }
            });
        };

        if (this.rootNode.parent && this.rootNode.parent.component && this.rootNode.parent.component.module)
            this.module.parentModule = this.rootNode.parent.component;
    }

    getProperty(path, returnString = false) {
        var current = this.module;
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
            } else if (isset(current[name])) {
                current = current[name];
            } else {
                return '';
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
        this.module[path] = value;
    }

    beforeEvent() {
        this.regularTrackedProperties = {};
        var names = Object.keys(this.module);
        for (let i = 0; i < names.length; i++) {
            const name = names[i];
            this.regularTrackedProperties[name] = this.module[name];
        }
    }

    afterEvent() {
        if (this.trackedPropertyChanged) {
            this.trackedPropertyChanged = false;
            this.renderedCallbackChangedAttributes();
            return;
        }

        var names = Object.keys(this.trackedProperties);
        for (let i = 0; i < names.length; i++) {
            const name = names[i];
            if (this.regularTrackedProperties[name] != this.module[name]) {
                this.renderedCallbackChangedAttributes();
                break;
            }
        }
    }

    renderedCallbackChangedAttributes() {
        setTimeout(() => {
            this.beforeEvent();
            this.module.renderedCallback();
            this.afterEvent();
        }, 20);
        this.trackedPropertyChanged = false;
        this.rootNode.refresh(true);
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
