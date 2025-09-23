
class eclMod_modAlert extends eclMod {
    showAlert = false;
    name = 'alert';
    alerts = [];

    connectedCallback() {
        this.track('showAlert');
    }

}
