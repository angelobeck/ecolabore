
class eclMod_filter_string extends eclMod {
    control;
    formulary;
    value = '';

    connectedCallback() {
        this.formulary.subscribe(this);
        if(this.control.target)
            this.value = this.formulary.getField(this.control.target);
    }

    refreshCallback() {}

    handleChange(event) {
var value = event.currentTarget.value;
if(this.control.target)
    this.formulary.setField(this.control.target, value);
    }

    disconnectedCallback() {
        this.formulary.unsubscribe(this);
    }

}
