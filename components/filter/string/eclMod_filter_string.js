
class eclMod_filter_string extends eclMod {
    control;
    formulary;
    value = '';

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

        this.formulary.setField(this.control.flags.target, event.detail.value);
    }

}
