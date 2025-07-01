
function serialize(fromAny, indent = false, indentLevel = 0) {
    if (fromAny === null)
        return 'null';
    if (fromAny === undefined)
        return 'undefined';
    if (fromAny === false)
        return 'false';
    if (fromAny === true)
        return 'true';
    if (typeof (fromAny) === 'string')
        return '"' + escapeString(fromAny) + '"';
    if (typeof (fromAny) === 'number')
        return fromAny.toString();
    if (Array.isArray(fromAny))
        return serializeArray(fromAny, indent, indentLevel);
    else
        return serializeObject(fromAny, indent, indentLevel);

    function serializeArray(fromArray, indent, indentLevel) {
        var buffer = '[';
        if (fromArray.length > 0)
            buffer += "\r\n";

        for (let i = 0; i < fromArray.length; i++) {
            if (indent)
                buffer += '    '.repeat(indentLevel + 1);
            buffer += serialize(fromArray[i], indent, indentLevel + 1);
            if (i + 1 < fromArray.length)
                buffer += ",\r\n";
            else
                buffer += "\r\n";
        }
        if (fromArray.length > 0 && indent)
            buffer += '    '.repeat(indentLevel);
        buffer += ']';
        return buffer;
    }

    function serializeObject(fromObject, indent, indentLevel) {
        var buffer = '{';
        var keys = Object.keys(fromObject);
        if (keys.length > 0)
            buffer += "\r\n";

        for (let i = 0; i < keys.length; i++) {
            const key = keys[i];
            if (indent)
                buffer += '    '.repeat(indentLevel + 1);
            buffer += '"' + key + '": ' + serialize(fromObject[key], indent, indentLevel + 1);
            if (i + 1 < keys.length)
                buffer += ",\r\n";
            else
                buffer += "\r\n";
        }
        if (indent)
            buffer += '    '.repeat(indentLevel);
        buffer += '}';
        return buffer;
    }

    function escapeString(fromString) {
        return fromString.replace(/[#]/g, "#c")
            .replace(/[\\]/g, "#b")
            .replace(/\n/g, "#n")
            .replace(/\r/g, "#r")
            .replace(/\0/g, "#0")
            .replace(/["]/g, "#q");
    }

}
