
function unserialize(jsonSerializedString, fileName = '') {
    var line = 1;
    var index = 0;
    var length = jsonSerializedString.length;
    return unserializeAny();

    function unserializeAny() {
        var char;
        while (index < length) {
            char = jsonSerializedString.charAt(index);

            switch (char) {
                case "{":
                    return unserializeObject();

                case "[":
                    return unserializeArray();

                case '"':
                    return unserializeString();

                case 'f':
                    index += 5;
                    return false;

                case 'n':
                    index += 4;
                    return null;

                case 't':
                    index += 4;
                    return true;

                case 'u':
                    index += 9;
                    return undefined;

                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9':
                case '.':
                case '-':
                    return unserializeNumber();

                case "\n":
                    line++;
                    index++;
                    break;

                case "\r":
                case ' ':
                    index++;
                    break;

                default:
                    return null;
                    break;
            }
        }
        return null;
    }

    function unserializeObject() {
        var result = {};
        var char;
        var key = false;
        index++;

        while (index < length) {
            char = jsonSerializedString.charAt(index);

            if (char === '}') {
                index++;
                return result;
            }

            if (char === ',') {
                index++;
                continue;
            }

            if (char === '"' && key === false) {
                key = unserializeString();
                continue;
            }

            if (char === ':' && key !== false) {
                index++;
                result[key] = unserializeAny();
                key = false;
                continue;
            }

            index++;
        }
        return result;
    }

    function unserializeArray() {
        var char;
        var result = [];
        index++;
        while (index < length) {
            char = jsonSerializedString.charAt(index);

            if (char === ']') {
                index++;
                return result;
            }

            if (char === ',') {
                index++;
                continue;
            }

            const value = unserializeAny();
            if (value !== null)
                result.push(value);
        }
        return result;
    }

    function unserializeString() {
        index++;
        var start = index;
        var buffer = '';
        while (index < length && jsonSerializedString.charAt(index) !== '"') {
            index++;
        }
        buffer = jsonSerializedString.substring(start, index);
        index++;
        return unescapeString(buffer);
    }

    function unserializeNumber() {
        var buffer = '';
        while (index < length) {
            char = jsonSerializedString.charAt(index);
            if (/^[0-9.-]$/.test(char)) {
                index++;
                buffer += char;
            } else {
                break;
            }
        }
        return Number.parseFloat(buffer);
    }

    function unescapeString(fromString) {
        return fromString.replace(/[#]q/g, '"')
            .replace(/[#]0/g, "\0")
            .replace(/[#]n/g, "\n")
            .replace(/[#]r/g, "\r")
            .replace(/[#]t/g, "\t")
            .replace(/[#]b/g, "\\")
            .replace(/[#]c/g, "#");
    }

}
