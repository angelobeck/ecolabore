
class eclCom_filter_checkbox extends eclCom {
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

    get _checked_() {
        var value = false;
        if (this.control.flags && this.control.flags.trueValue && this.value === this.control.flags.trueValue)
            value = true;
        if (this.control.flags && this.control.flags.invert)
            value = !value;

        return value;
    }

    handleChange(event) {
        if (!this.formulary || !this.control.flags || !this.control.flags.target)
            return;

        var value = event.detail.checked;
        if (this.control.flags && this.control.flags.invert)
            value = !value;
        if (value && isset(this.control.flags.trueValue))
            this.formulary.setField(this.control.flags.target, this.control.flags.trueValue);
        else if (!value && isset(this.control.flags.falseValue))
            this.formulary.setField(this.control.flags.target, this.control.flags.falseValue);
        else
            this.formulary.setField(this.control.flags.target, value);
    }

}
