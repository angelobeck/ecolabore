
class eclMod_filter_radio extends eclMod {
    control;
    formulary;
    value = false;

    connectedCallback() {
        this.api('formulary');
        this.api('control');
    }

    refreshCallback() {
        if (!this.formulary)
            return;

        if (this.control.flags && this.control.flags.target)
            this.value = this.formulary.getField(this.control.flags.target);
    }

    disconnectedCallback() {
        if (!this.formulary)
            return;

        this.formulary.unsubscribe(this);
    }

    handleChange(event) {
        if (!this.formulary || !this.control.flags || !this.control.flags.target)
            return;

        var value = event.detail.value;
        this.formulary.setField(this.control.flags.target, value);
    }

    get _options_() {
        return [
            { label: "Novo amigo", value: 'male'},
            {label: "Nova amiga", value: "female"}
        ];
    }
}
