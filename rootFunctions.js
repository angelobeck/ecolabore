
function isset(value, path = '') {
    if (value === null || value === undefined)
        return false;
    if (path === '')
        return true;
    let parts = path.split('.');
    do {
        let key = parts.shift();
        if (value[key] === undefined || value[key] === null)
            return false;

        value = value[key];
    } while (parts.length);
    return true;
}

function getMap(applicationName) {
    if (isset(applicationsMaps[applicationName]))
        return applicationsMaps[applicationName];
    else
        return [];
}

function setMap(parentName, childName) {
    if (!window.applicationsMaps)
        window.applicationsMaps = {};
    if (!window.applicationsMaps[parentName])
        window.applicationsMaps[parentName] = [];

    window.applicationsMaps[parentName].push(childName);
}

function serialize(fromAny) {
    if (fromAny === null)
        return 'null';
    if (fromAny === undefined)
        return 'undefined';
    if (fromAny === false)
        return 'false';
    if (fromAny === true)
        return 'true';
    if (typeof (fromAny) === 'string')
        return '"' + fromAny + '"';
    if (typeof (fromAny) === 'number')
        return fromAny.toString();
    if (Array.isArray(fromAny))
        return serializeArray(fromAny);
    else
        return serializeObject(fromAny);

    function serializeArray(fromArray) {
        var buffer = '[';
        for (let i = 0; i < fromArray.length; i++) {
            buffer += serialize(fromArray[i]);
            if (i + 1 < fromArray.length)
                buffer += ",\r\n";
            else
                buffer += "\r\n";
        }
        buffer += ']';
        return buffer;
    }

    function serializeObject(fromObject) {
        var buffer = '{';
        var keys = Object.keys(fromObject);
        for (let i = 0; i < keys.length; i++) {
            const key = keys[i];
            buffer += '"' + key + '": ' + serialize(fromObject[key]);
            if (i + 1 < keys.length)
                buffer += ",\r\n";
            else
                buffer += "\r\n";
        }
        buffer += '}';
        return buffer;
    }
}
