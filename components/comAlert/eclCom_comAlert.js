
class eclCom_comAlert extends eclCom {
    showAlert = false;
    showAlertMonitor = false;
    showMenu = false;
    showMenuMonitor = false;
    hidden = false;
    alertName = 'alert';
    menuName = 'menu';
    alerts = [];

    connectedCallback() {
        this.track('hidden');
        this.track('showAlert');
        this.track('showMenu');
    }

    renderedCallback() {
        if (this.showAlertMonitor !== this.showAlert) {
            this.showAlertMonitor = this.showAlert;
            setTimeout(() => {
                this.hidden = this.showAlert || this.showMenu;
            }, 100);
        }

        if (this.showMenuMonitor !== this.showMenu) {
            this.showMenuMonitor = this.showMenu;
            setTimeout(() => {
                this.hidden = this.showAlert || this.showMenu;
            }, 100);
        }
    }

}
