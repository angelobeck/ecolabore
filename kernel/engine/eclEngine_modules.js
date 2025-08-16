
class eclEngine_modules {
    #registeredModules = {};

    createModule(name, controlName = '') {
        if (controlName !== '')
            this[name] = controlName;
        else if (this[name])
            controlName = this[name];
        else
            return false;

        if (this.#registeredModules[name] && this.#registeredModules[name].controlName === controlName)
            return this.#registeredModules[name].module;

        var control = store.staticContent.open(controlName);

        if (!control.flags || !control.flags.module || !registeredClasses.eclMod[control.flags.module])
            return false;

        var symbol = registeredClasses.eclMod[control.flags.module];
        var module = new symbol(control);

        this.#registeredModules[name] = {
            controlName: controlName,
            module: module
        };

        return module;
    }

    reset() {
        this.#registeredModules = {};

        for (const name in this) {
            if(Object.hasOwn(this, name))
            delete this[name];
        }
    }
}
