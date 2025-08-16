
class eclMod_modAlert extends eclMod {
    showAlert = false;
    alerts = [];

    connectedCallback() {
        this.track('showAlert');
    }

}
