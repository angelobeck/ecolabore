
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
