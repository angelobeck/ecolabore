
class eclEngine_components {
    #registeredComponents = {};

    createComponent(name, controlName = '') {
        if (controlName !== '')
            this[name] = controlName;
        else if (this[name])
            controlName = this[name];
        else
            return false;

        if (this.#registeredComponents[name] && this.#registeredComponents[name].controlName === controlName)
            return this.#registeredComponents[name].component;

        var control = store.staticContent.open(controlName);

        if (!control.flags || !control.flags.component || !registeredClasses.eclCom[control.flags.component])
            return false;

        var symbol = registeredClasses.eclCom[control.flags.component];
        var component = new symbol(control);

        this.#registeredComponents[name] = {
            controlName: controlName,
            component: component
        };

        return component;
    }

    reset() {
        this.#registeredComponents = {};

        for (const name in this) {
            if(Object.hasOwn(this, name))
            delete this[name];
        }
    }
}
