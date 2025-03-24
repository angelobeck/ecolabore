
class eclEngine_formulary {
    page;
    data = [];
    flags = [];
    prefix = 'edit';
    children = [];
    received = [];
    error = [];
    control = [];

    sessionKey;
    errorStatus = 0;
    errorMessage = [];

    constructor(page, control, data, prefix) {
        this.page = page;
        this.received = page.received;
        this.data = data;
        this.prefix = prefix;
        this.sessionKey = page.application.path.implode('/') + '/_' + prefix;
        if (typeof (control) === 'string') {
            control = store.staticContent.open(control);
        }
        this.control = control;
    }

    save(posteriori = 0) {
        var control, name, filter;
        this.error = [];
        if (this.control.children) {
            for (let i = 0; i < this.control.children.length; i++) {
                control = this.control[i];
                if (is_string(control)) {
                    control = this.cloneControl(store.staticContent.open(control));
                }

                if (!control.filter)
                    continue;
                if (!registeredClasses.eclFilter[control.filter])
                    continue;
                if (!control.name)
                    continue;
                if (control.condition && !this.condition(control.condition))
                    continue;
                if (control.posteriori && posteriori == 1)
                    continue;
                if (!control.posteriori && posteriori == 2)
                    continue;
                if (contro.view)
                    continue;

                name = this.prefix + '_' + control.name;
                filter = registeredClasses.eclFilter[control.filter];
                if (!control.target)
                    control.target = control.name;

                filter.save(this, control, name);
            }
        }

        return this.errorStatus === 0;
    }

    create() {
        var name, control, filter;
        if (!this.errorStatus) {
            this.children = [];
            if (this.control.children) {
                for (let i = 0; i < this.control.children.length; i++) {
                    name = this.control.children[i];

                    if (is_string(name))
                        control = this.cloneControl(store.staticContent.open(name));
                    else
                        control = name;

                    if (!control.filter)
                        continue;
                    if (!registeredClasses.eclFilter[control.filter])
                        continue;
                    if (!control.name)
                        continue;
                    if (control.condition && !this.condition(control.condition))
                        continue;

                    name = this.prefix + '_' + control.name;
                    filter = registeredClasses.eclFilter[control.filter];
                    if (!control.target)
                        control.target = control.name;

                    if (control.view)
                        filter.view(this, control, name);
                    else
                        filter.create(this, control, name);
                }
            }
        }
        const form = new eclMod_form(this.page);
        form.children = this.children;
        return form;
    }

    alert() {
        var alert = new eclMod_form_alert(this.page);
        alert.data = this.errorMessage;
        return alert;
    }

    error() {
        return this.errorStatus > 0;
    }

    errorClear() {
        this.errorStatus = 0;
        this.errorMessage = [];
    }

    condition(condition) {
        return !!this.flags[condition];
    }

    getField(target) {
        if (!target.length)
            return false;

        var path = target.explode('.');
        var length = path.length;
        var search = [this.data];
        var found;
        for (let i = 0; i < length;) {
            let field = path[i];
            if (!search[i][field])
                return false;
            found = search[i][field];
            i++;
            if (length == i)
                return found;
            search[i] = found;
        }
        return found;
    }

    setField(target, value = false) {
        if (!target.length)
            return;

        path = target.explode('.');
        length = path.length;
        do { // its not a loop
            if (length == 1) {
                this.data[path[0]] = value;
                break;
            }
            if (!this.data[path[0]])
                this.data[path[0]] = {};

            if (length == 2) {
                this.data[path[0]][path[1]] = value;
                break;
            }
            if (!this.data[path[0]][path[1]])
                this.data[path[0]][path[1]] = {};

            if (length == 3) {
                this.data[path[0]][path[1]][path[2]] = value;
                break;
            }

            if (!this.data[path[0]][path[1]][path[2]])
                this.data[path[0]][path[1]][path[2]] = {};

            if (length == 4) {
                this.data[path[0]][path[1]][path[2]][path[3]] = value;
                break;
            }
            if (!this.data[path[0]][path[1]][path[2]][path[3]])
                this.data[path[0]][path[1]][path[2]][path[3]] = [];

            if (length == 5) {
                this.data[path[0]][path[1]][path[2]][path[3]][path[4]] = value;
                break;
            }
            if (!this.data[path[0]][path[1]][path[2]][path[3]][path[4]])
                this.data[path[0]][path[1]][path[2]][path[3]][path[4]] = {};

            if (length == 6) {
                this.data[path[0]][path[1]][path[2]][path[3]][path[4]][path[5]] = value;
                break;
            }

            break;
        }
        while ('Ill never be evaluated');

        if (value === null) {
            switch (length) {
                case 6:
                    if (!this.data[path[0]][path[1]][path[2]][path[3]][path[4]][path[5]])
                        this.data[path[0]][path[1]][path[2]][path[3]][path[4]][path[5]] = null;

                case 5:
                    if (!this.data[path[0]][path[1]][path[2]][path[3]][path[4]])
                        this.data[path[0]][path[1]][path[2]][path[3]][path[4]] = null;

                case 4:
                    if (!this.data[path[0]][path[1]][path[2]][path[3]])
                        this.data[path[0]][path[1]][path[2]][path[3]] = null;

                case 3:
                    if (!this.data[path[0]][path[1]][path[2]])
                        this.data[path[0]][path[1]][path[2]] = null;

                case 2:
                    if (!this.data[path[0]][path[1]])
                        this.data[path[0]][path[1]] = null;

                case 1:
                    if (!this.data[path[0]])
                        this.data[path[0]] = null;
            }
        }
    }

    appendChild(data) {
        child = new eclEngine_child(this.page, data);
        this.children.push(child);
        return child;
    }

    setErrorMessage(control, name, message) {
        if (this.errorStatus > 0)
            return;

        if (typeof (message) === 'string') {
            this.errorMessage = this.cloneControl(store.staticContent.open(message));
        } else {
            this.errorMessage = this.cloneControl(message);
        }
    }

    cloneControl(control) {
        cloned = {};
        for (let key in control) {
            if (key === 'flags' || key === 'text') {
                let subitem = control[key];
                for (let subkey in subitem) {
                    cloned[subkey] = subitem[subkey];
                }
            } else {
                cloned[key] = control[key];
            }
        }
        return cloned;
    }

}
