<?php

class eclIo_convert
{

    public static $remove_diacritics_iso_8859_1 = [
        192 => 65, // &Agrave; => A
        193 => 65, // &Aacute; => A
        194 => 65, // &Acirc; => A
        195 => 65, // &Atilde; => A
        196 => 65, // &Auml; => A
        197 => 65, // &Aring; => A
        199 => 67, // &Ccedil; => C
        200 => 69, // &Egrave; => E
        201 => 69, // &Eacute; => E
        202 => 69, // &Ecirc; => E
        203 => 69, // &Euml; => E
        204 => 73, // &Igrave; => I
        205 => 73, // &Iacute; => I
        206 => 73, // &Icirc; => I
        207 => 73, // &Iuml; => I
        209 => 78, // &Ntilde; => N
        210 => 79, // &Ograve; => O
        211 => 79, // &Oacute; => O
        212 => 79, // &Ocirc; => O
        213 => 79, // &Otilde; => O
        214 => 79, // &Ouml; => O
        217 => 85, // &Ugrave; => U
        218 => 85, // &Uacute; => U
        219 => 85, // &Ucirc; => U
        220 => 85, // &Ueml; => U
        221 => 89, // &Yacute; => Y
        224 => 97, // &agrave; => a
        225 => 97, // &aacute; => a
        226 => 97, // &acirc; => a
        227 => 97, // &atilde; => a
        228 => 97, // &auml; => a
        229 => 97, // &aring; => a
        231 => 99, // &ccedil; => c
        232 => 101, // &egrave; => e
        233 => 101, // &eacute; => e
        234 => 101, // &ecirc; => e
        235 => 101, // &euml; => e
        236 => 105, // &igrave; => i
        237 => 105, // &iacute; => i
        238 => 105, // &icirc; => i
        239 => 105, // &iuml; => i
        241 => 110, // &ntilde; => n
        242 => 111, // &ograve; => o
        243 => 111, // &oacute; => o
        244 => 111, // &ocirc; => o
        245 => 111, // &otilde; => o
        246 => 111, // &ouml; => o
        249 => 117, // &ugrave; => u
        250 => 117, // &uacute; => u
        251 => 117, // &ucirc; => u
        252 => 117, // &uuml; => u
        253 => 121, // &yacute; => y
        255 => 121, // &yuml; => y
    ];

    public const DATABASE_ESCAPE_CHARS_LIST = [
        '#' => '#c',
        "\0" => '#0',
        "\r" => '#r',
        "\n" => '#n',
        "\t" => '#t',
        '\\' => '#b'
    ];

    public const ESCAPE_CHARS_LIST = [
        '#' => '#c',
        "\0" => '#0',
        "\r" => '#r',
        "\n" => '#n',
        "\t" => '#t',
        '"' => '#q',
        '\\' => '#b'
    ];

    public static function escapeString(string $value): string
    {
        return str_replace(array_keys(self::ESCAPE_CHARS_LIST), array_values(self::ESCAPE_CHARS_LIST), $value);
    }

    public static function unescapeString(string $value): string
    {
        return str_replace(array_values(self::ESCAPE_CHARS_LIST), array_keys(self::ESCAPE_CHARS_LIST), $value);
    }

    public static function databaseEscapeString(string $value): string
    {
        return str_replace(array_keys(self::DATABASE_ESCAPE_CHARS_LIST), array_values(self::DATABASE_ESCAPE_CHARS_LIST), $value);
    }

    public static function databaseUnescapeString(string $value): string
    {
        return str_replace(array_values(self::DATABASE_ESCAPE_CHARS_LIST), array_keys(self::DATABASE_ESCAPE_CHARS_LIST), $value);
    }

    public static function json2array(string $json, string $fileName = ''): mixed
    {
        $index = 0;
        $line = 1;
        return self::json_any2array($json, $index, $fileName, $line);
    }

    private static function json_any2array(string $json, int &$index, string $fileName, int &$line)
    {
        while ($index < strlen($json)) {
            $char = $json[$index];
            switch ($char) {
                case "{":
                    return self::json_object2array($json, $index, $fileName, $line);

                case "[":
                    return self::json_array2array($json, $index, $fileName, $line);

                case '"':
                    $index++;
                    $start = $index;
                    $end = strpos($json, '"', $index);
                    if ($end === false)
                        throw new ErrorException("JSON decode error: missing string ending quote in file $fileName, on line $line");
                    $index = $end + 1;
                    return self::unescapeString(substr($json, $start, $end - $start));

                case 'f':
                    if (substr($json, $index, 5) === 'false') {
                        $index += 5;
                        return false;
                    }
                    throw new ErrorException("JSON decode error: invalid character in file $fileName on line $line");

                case 't':
                    if (substr($json, $index, 4) === 'true') {
                        $index += 4;
                        return true;
                    }
                    throw new ErrorException("JSON decode error: invalid character in file $fileName on line $line");

                case "\n":
                    $line++;
                case ' ':
                case "\r":
                case "\t":
                    $index++;
                    break;

                default:
                    $length = strspn($json, '-0.123456789', $index);
                    if ($length > 0) {
                        $number = substr($json, $index, $length);
                        $index += $length;
                        $isFloat = strpos($number, '.');
                        if ($isFloat === false)
                            return intval($number);
                        else
                            return floatval($number);
                    }
                    throw new ErrorException("JSON decode error: invalid character in file $fileName on line $line");
            }
        }
        return null;
    }

    private static function json_object2array(string $json, int &$index, string $fileName, int &$line)
    {
        $result = [];
        $index++;
        while ($index < strlen($json)) {
            $char = $json[$index];
            switch ($char) {
                case '}':
                    $index++;
                    return $result;

                case "\n":
                    $line++;
                case " ":
                case "\r":
                case "\t":
                    $index++;
                    break;

                case '"':
                    $index++;
                    $start = $index;
                    $end = strpos($json, '"', $index);
                    if ($end === false)
                        throw new ErrorException("JSON decode error: missing string ending quote in file $fileName, on line $line");
                    $index = $end + 1;
                    $key = substr($json, $start, $end - $start);
                    if (!preg_match('/^[a-zA-Z0-9_]+$/', $key))
                        throw new ErrorException("JSON decode error: invalid character in fileName $fileName, on line $line");
                    $dots = strpos($json, ':', $index);
                    if ($dots === false)
                        throw new ErrorException("JSON decode error: missing :  in fileName $fileName, on line $line");
                    $index = $dots + 1;
                    $value = self::json_any2array($json, $index, $fileName, $line);
                    $result[$key] = $value;
                    break;

                case ',':
                    $index++;
                    break;

                default:
                    throw new ErrorException("JSON decode error: invalid character in file $fileName on line $line");

            }
        }
        throw new ErrorException("JSON decode error: missing } in file $fileName on line $line");
    }

    private static function json_array2array(string $json, int &$index, string $fileName, int &$line)
    {
        $index++;
        $result = [];
        while (isset($json[$index])) {
            $char = $json[$index];
            switch ($char) {
                case ']':
                    $index++;
                    return $result;

                case ',':
                    $index++;
                    break;

                case "\n":
                    $line++;
                case ' ':
                case "\r":
                case "\t":
                    $index++;
                    break;

                default:
                    $result[] = self::json_any2array($json, $index, $fileName, $line);
            }
        }
        throw new ErrorException("JSON decode error: missing ] in file $fileName on line $line");
    }

    static function array2json(mixed $data): string
    {
        $buffer = '';

        if (!is_array($data)) {
            if ($data === true)
                return 'true';
            elseif ($data === false)
                return 'false';
            elseif (is_int($data))
                return strval($data);
            elseif (is_float($data))
                return strval($data);
            elseif (is_string($data))
                return '"' . self::escapeString($data) . '"';
            else
                throw new ErrorException('JSON encode error: invalid value type');

        } elseif (!$data) {
            return '{}';
        }

        reset($data);
        $key = key($data);
        if ($key === 0) {
            $buffer .= '[' . CRLF;
            foreach ($data as $key => $value) {
                if ($key)
                    $buffer .= ',' . CRLF;

                $buffer .= self::array2json($value);
            }
            $buffer .= CRLF . ']';
            return $buffer;
        } // is indexed array

        $buffer .= '{' . CRLF;
        $index = 0;
        foreach ($data as $key => $value) { // each array element
            $key = strval($key);
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $key))
                throw new ErrorException("JSON encode error: invalid key '{$key}'");

            if ($index++)
                $buffer .= ',' . CRLF;
            $buffer .= '"' . $key . '": ';

            $buffer .= self::array2json($value);
        } // each array element
        $buffer .= CRLF . '}';
        return $buffer;
    }

    public function removeDiacritics(string $fromString, string $encoding = 'UTF-8'): string
    {
        $length = mb_strlen($fromString, $encoding);
        $buffer = '';
        $iso = mb_convert_encoding($fromString, 'ISO8859-1', $encoding);
        for ($i = 0; $i < $length; $i++) {
            $ord = ord($iso[$i]);
            if (isset(self::$remove_diacritics_iso_8859_1[$ord])) {
                $buffer .= chr(self::$remove_diacritics_iso_8859_1[$ord]);
            } else {
                $buffer .= mb_substr($fromString, $i, 1, $encoding);
            }
        }
        return $buffer;
    }

    public static function slug(string $fromString, string $encoding = 'UTF-8'): string
    {
        $buffer = '';
        $last = -1;
        $iso = mb_convert_encoding($fromString, 'ISO8859-1', $encoding);
        $length = strlen($iso);
        for ($i = 0; $i < $length; $i++) {
            if ($iso[$i] === '#') {
                $i++;
                continue;
            }
            $ord = ord($iso[$i]);
            if (isset(self::$remove_diacritics_iso_8859_1[$ord])) {
                $ord = self::$remove_diacritics_iso_8859_1[$ord];
            }
            if ($ord >= 65 && $ord <= 90) { // A to Z
                $ord += 32; // convert to lower
            } else if (($ord < 97 || $ord > 122) && $ord != 45) { // (<a || > z) && != -
                $ord = 95; // _
            }
            if (($last == 95 && $ord == 95) || ($last == 45 && $ord == 45)) {
                continue; // skip repeated _ or -
            }
            $last = $ord;
            $buffer .= chr($ord);
        }
        return trim($buffer, '_-');
    }

    public static function htmlSpecialChars(string $fromString): string
    {
        $search = ['&', '"', '<', '>'];
        $replace = ['&amp;', '&quot;', '&lt;', '&gt;'];
        return str_replace($search, $replace, $fromString);
    }

    public static function generateRandomName($length = 8)
    {
        $buffer = '';
        for ($i = 0; $i < $length; $i++) {
            $number = rand(0, 61);
            if ($number < 10)
                $buffer .= chr($number + 48);
            else if ($number < 36)
                $buffer .= chr($number + 55);
            else
                $buffer .= chr($number + 61);
        }
        return $buffer;
    }

    public static function hex2bin(string $hexadecimalEncodedString): string
    {
        $buffer = '';
        $length = strlen($hexadecimalEncodedString);
        for ($i = 0; $i < $length; $i += 2) {
            $buffer .= chr(hexdec(substr($hexadecimalEncodedString, $i, 2)));
        }
        return $buffer;
    }

    public static function extractNumbers(string $numericString): string
    {
        $buffer = '';
        $length = strlen($numericString);
        for ($i = 0; $i < $length; $i++) {
            $char = $numericString[$i];
            if (preg_match('/^[0-9]$/', $char)) {
                $buffer .= $char;
            }
        }
        return $buffer;
    }

}
