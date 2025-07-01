
function clone(data, prefix = '') {
    if(Array.isArray(data))
        return cloneArray(data, prefix);
    if(typeof(data) === "object")
        return cloneObject(data, prefix);
    if(typeof(data) === "string" && prefix !== "" && data.charAt(0) === "~")
        return prefix + data.substring(1);
    else
    return data;

    function cloneArray(data, prefix) {
        return data.map(value => {
            return clone(value, prefix);
        });
    }

    function cloneObject(data, prefix) {
        var cloned = {};
        for(let name in data) {
            if(name === "text")
                cloned[name] = clone(data[name], '');
            else
            cloned[name] = clone(data[name], prefix);
        }
        return cloned;
    }

}
