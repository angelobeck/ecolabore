
class eclMod_tag_formulary extends eclMod {
    fields = {};
    name = '';

    registeredFilters = [];
    disabled = false;
    errorMessage = '';
    parent;

    connectedCallback() {
        this.api('fields');
        this.api('name');
    }

    refreshCallback() {
        if (this.name !== '')
            this.data = store.staticContent.open(this.name);
    }

    get _actions_() {
        if (!this.data.actions)
            return [];

        var actions = [];
        for (let i = 0; i < this.data.actions.length; i++) {
            const name = this.data.actions[i];
            const control = store.staticContent.open(name);
            if (control.flags && control.flags.action && control.text && control.text.label) {
                actions.push({
                    label: control.text.label,
                    value: control.flags.action
                });
            }
        }
        return actions;
    }

    get _filters_() {
        var filters = [];

        if (!this.data.children)
            return filters;
        for (let i = 0; i < this.data.children.length; i++) {
            const controlName = this.data.children[i];
            const control = store.staticContent.open(controlName);
            if (control.flags && control.flags.filter && registeredClasses.eclMod[control.flags.filter]) {
                filters.push({
                    name: control.flags.filter,
                    control: control
                });
            }
        }
        return filters;
    }

    handleAction(event) {
        var value = event.detail.value;
        this.dispatchEvent(new CustomEvent("action", {
            detail: {
                action: value,
                formulary: this
            }
        }));
    }

    subscribe(filter) {
        this.registeredFilters.push(filter);
    }

    unsubscribe(filter) {
        for (let i = 0; i < this.registeredFilters.length; i++) {
            let current = this.registeredFilters[i];
            if (current === filter) {
                this.registeredFilters.splice(i, 1);
                return;
            }
        }
    }

    getField(target) {
        if (!target.length)
            return null;

        var path = target.split('.');
        var length = path.length;
        var found = [this.fields];
        var field;
        var current;
        for (let i = 0; i < length;) {
            field = path[i];
            if (!isset(found[i][field]))
                return null;
            current = found[i][field];
            i++;
            if (length == i)
                return current;
            found[i] = current;
        }
        return current;
    }

    setField(target, value = null) {
        if (!target.length)
            return;

        var path = target.split('.');
        var length = path.length;
        do { // its not a loop
            if (length == 1) {
                this.fields[path[0]] = value;
                break;
            }
            if (!isset(this.fields[path[0]]))
                this.fields[path[0]] = {};

            if (length == 2) {
                this.fields[path[0]][path[1]] = value;
                break;
            }
            if (!isset(this.fields[path[0]][path[1]]))
                this.fields[path[0]][path[1]] = {};

            if (length == 3) {
                this.fields[path[0]][path[1]][path[2]] = value;
                break;
            }

            if (!isset(this.fields[path[0]][path[1]][path[2]]))
                this.fields[path[0]][path[1]][path[2]] = {};

            if (length == 4) {
                this.fields[path[0]][path[1]][path[2]][path[3]] = value;
                break;
            }
            if (!isset(this.fields[path[0]][path[1]][path[2]][path[3]]))
                this.fields[path[0]][path[1]][path[2]][path[3]] = {};

            if (length == 5) {
                this.fields[path[0]][path[1]][path[2]][path[3]][path[4]] = value;
                break;
            }
            if (!isset(this.fields[path[0]][path[1]][path[2]][path[3]][path[4]]))
                this.fields[path[0]][path[1]][path[2]][path[3]][path[4]] = {};

            if (length == 6) {
                this.fields[path[0]][path[1]][path[2]][path[3]][path[4]][path[5]] = value;
                break;
            }

            break;
        }
        while (false);

        if (value === null) {
            switch (length) {
                case 6:
                    if (!this.fields[path[0]][path[1]][path[2]][path[3]][path[4]][path[5]])
                        delete this.fields[path[0]][path[1]][path[2]][path[3]][path[4]][path[5]];

                case 5:
                    if (!this.fields[path[0]][path[1]][path[2]][path[3]][path[4]])
                        delete this.fields[path[0]][path[1]][path[2]][path[3]][path[4]];

                case 4:
                    if (!this.fields[path[0]][path[1]][path[2]][path[3]])
                        delete this.fields[path[0]][path[1]][path[2]][path[3]];

                case 3:
                    if (!this.fields[path[0]][path[1]][path[2]])
                        delete this.fields[path[0]][path[1]][path[2]];

                case 2:
                    if (!this.fields[path[0]][path[1]])
                        delete this.fields[path[0]][path[1]];

                case 1:
                    if (!this.fields[path[0]])
                        delete this.fields[path[0]];
            }
        }
    }

    check() {
        return true;
    }

}
