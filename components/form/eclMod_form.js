
class eclMod_form extends eclMod {
    registeredFilters = [];
    fields = {};
    commands = {};
    disabled = false;
    errorMessage = '';

    get _commands_() {
        if (!this.data.commands)
            return [];

        var commands = [];
        for (let i = 0; i < this.data.commands.length; i++) {
            const name = this.data.commands[i];
            const control = store.staticContent.open(name);
            if (control.flags && control.flags.command && control.text && control.text.label) {
                commands.push({
                    label: control.text.label,
                    name: control.flags.command
                });
            }
        }
        return commands;
    }

    get _filters_() {
        if (!this.data.children)
            return [];

        var filters = [];
        for (let i = 0; i < this.data.children.length; i++) {
            const name = this.data.children[i];
            const control = store.staticContent.open(name);
            if (control.flags.filter && registeredClasses.eclMod[control.flags.filter]) {
                filters.push({
                    control: control,
                    name: control.flags.filter
                });
            }

        }
        return filters;
    }

    handleCommand(event) {
        var name = event.currentTarget.dataset.name;
        if (isset(this.commands[name]))
            this.commands[name]();
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

        path = target.split('.');
        length = path.length;
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

}
