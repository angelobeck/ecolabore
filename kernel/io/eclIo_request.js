
class eclIo_request {
    thenCallback = false;
    catchCallback = false;
    request;

    constructor(data = false, endpoint = 'main', path = false) {
        if (!Array.isArray(path))
            path = [...page.application.path];
        var url = page.url(path, true, '_endpoint-' + endpoint);

        this.request = new XMLHttpRequest();

        this.request.onload = () => {
            var raw = this.request.responseText;
            var data = unserialize(this.request.responseText);
            if (this.thenCallback)
                this.thenCallback(data.response, raw);
            if (data.error && this.catchCallback)
                this.catchCallback(data.error);
        };

        this.request.onerror = (error) => {
            if (this.catchCallback) {
                this.catchCallback(unserialize(this.request.responseText || ''));
            }
        };

        this.request.onloadend = () => {
        };

        this.request.open('PUT', url);

        if (data === undefined || data === null || data === false)
            this.request.send();
        else
            this.request.send(serialize(data));

    }

    then(callback) {
        this.thenCallback = callback;
        return this;
    }

    catch(callback) {
        this.catchCallback = callback;
        return this;
    }

}
