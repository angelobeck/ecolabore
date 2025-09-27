
class eclEngine_store {
    staticContent;
    #handlers = {};

    constructor() {
        this.staticContent = new eclStore_staticContent();
    }

    load(handlerName) {
        if (this.#handlers[handlerName])
            return this.#handlers[handlerName];

        if (!registeredClasses.eclStore || !registeredClasses.eclStore[handlerName])
            return null;

        var handlerClass = registeredClasses.eclStore[handlerName];
        var handler = new handlerClass();

        this.#handlers[handlerName] = handler;
        return handler;
    }
}
