
class eclIo_request {
    thenCallback = false;
    catchCallback = false;
    request;

    constructor(data = false, endpoint = 'main', path = false) {
        if (!Array.isArray(path))
            path = [...page.application.path];
        var url = page.url(path, true, '_endpoint-' + endpoint);

        if(page.session.user && page.session.user.sessionId && page.session.user.sessionKey) {
            if(typeof(data) !== "object")
                data = {};

            data.sessionId = page.session.user.sessionId;
            data.sessionKey = page.session.user.sessionKey;
        }

        this.request = new XMLHttpRequest();

        this.request.onload = () => {
            var raw = this.request.responseText;
            var data = unserialize(this.request.responseText);
            if (data && this.thenCallback && data.response !== undefined && data.response !== null)
                this.thenCallback(data.response, raw);
            else if(data && data.error && data.error.message === 'system_accessDenied')
                navigate(page.url(page.domain.name, '-access-denied'));
            else if(data && data.error && data.error.message === 'system_invalidSession')
                navigate(page.url(page.domain.name, '-invalid-session'));
            else if (data && data.error && this.catchCallback)
                this.catchCallback(data.error, raw);
            else if(this.catchCallback)
                this.catchCallback({}, raw);
        };

        this.request.onerror = (error) => {
            var raw = this.request.responseText || '';
            var data = unserialize(raw);
            if (this.catchCallback) {
                this.catchCallback(data, raw);
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
