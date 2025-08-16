
class eclIo_convert {
    static fromCharacterTable = 'aAáÁàÀäÄãÃâÂbBcCçÇdDeEéÉèÈëËêÊfFgGhHiIjJkKlLmMnNñÑoOóÓòÒöÖõÕôÔpPqQrRsStTuUúÚùÙüÜûÛvVwWxXyYýÝÿzZ-';
    static toCharacterTable = 'aaaaaaaaaaaabbccccddeeeeeeeeeeffgghhiijjkkllmmnnnnooooooooooooppqqrrssttuuuuuuuuuuvvwwxxyyyyyzz-';

    static slug(input) {
        var buffer = '';
        var char = '';
        var last = '_';
        var length = input.length;
        for (let i = 0; i < length; i++) {
            char = input.charAt(i);
            let index = this.fromCharacterTable.indexOf(char);
            if (index >= 0) {
                char = this.toCharacterTable.substring(index, index + 1);
            } else {
                char = '_';
            }

            if (char === '_' && last === '_')
                continue;

            buffer += char;
            last = char;
        }

        return buffer;
    }

    static numbersOnly(fromString) {
        var buffer = '';
        for (let i = 0; i < fromString.length; i++) {
            const char = fromString.charAt(i);
            if (/^[0-9]$/.test(char))
                buffer += char;
        }
        return buffer;
    }

    static stripTags(fromString) {
        var buffer = '';
        while(true) {
            let start = fromString.indexOf('<');
            if(start === -1) {
                buffer += fromString;
                return buffer;
            }

            if(start > 0) {
                buffer += fromString.substring(0, start);
            }

            let end = fromString.indexOf('>', start);
if(end === -1)
    return buffer;

fromString = fromString.substring(end + 1);
        }
    }

}