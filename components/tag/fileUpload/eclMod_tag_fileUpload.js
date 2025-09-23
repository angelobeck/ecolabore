
class eclMod_tag_fileUpload extends eclMod {
    endpoint = '';
    name = 'file';
    path = '';
    title = 'Enviar arquivo';

    headerElement;
    headerFocus = false;
    inputFileElement;
    disabled = false;
    request;
    progressElement;
    progress = '0';

    connectedCallback() {
        this.api('endpoint');
        this.api('name');
        this.api('path');
        this.api('title');
        this.track('disabled');
    }

    actionDialogOpen() {
        page.alertOpen(this.name);
        this.headerFocus = true;
    }

    renderedCallback() {
        if (this.headerFocus && this.headerElement) {
            this.headerFocus = false;
            this.headerElement.focus();
        }
    }

    actionSend() {
        this.disabled = true;

        var url = page.url(true, true, '_endpoint-file');
        if (page.session.user && page.session.user.sessionId && page.session.user.sessionKey) {
            url += '-' + page.session.user.sessionId;
            url += '-' + page.session.user.sessionKey;
        }

        this.request = new XMLHttpRequest();

        this.request.upload.onprogress = (event) => {
            if (event.lengthComputable) {
                var percent = Math.round(event.loaded * 100 / event.total);
                this.progressElement.value = percent;
                this.progress = percent + "%";
            }
        };

        this.request.onloadend = () => {
            this.disabled = false;
            this.dispatchEvent(new CustomEvent("loadend"));
            page.alertClose(this.name);
        }

        this.request.open("POST", url);

        if (this.inputFileElement.files && this.inputFileElement.files.length) {
            var formData = new FormData();
            formData.append("file", this.inputFileElement.files[0]);

            this.request.send(formData);
        } else {
            this.request.send(url);
        }
    }

    actionClose() {
        this.disabled = false;
        page.alertClose(this.name);
    }

}
