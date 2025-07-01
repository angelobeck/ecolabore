
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
}