<?php

namespace App\Utils;

class HtmlToEmoji
{
    public static function convertTextToEmoji(string $string): array|string
    {
        $emojis = [
            "o/" => "👋",
            "</3" => "💔",
            "<3" => "💗",
            "8-D" => "😁",
            "8D" => "😁",
            ":-D" => "😁",
            ":-3" => "😁",
            ":3" => "😁",
            ":D" => "😁",
            "B^D" => "😁",
            "X-D" => "😁",
            "XD" => "😁",
            "x-D" => "😁",
            "xD" => "😁",
            ":\")" => "😂",
            ":\"-)" => "😂",
            ":-))" => "😃",
            "8)" => "😄",
            ":)" => "😊",
            ":-)" => "😄",
            ":]" => "😄",
            ":^)" => "😄",
            ":c)" => "😄",
            ":o)" => "😄",
            ":}" => "😄",
            ":っ)" => "😄",
            "0:)" => "😇",
            "0:-)" => "😇",
            "0:-3" => "😇",
            "0:3" => "😇",
            "0;^)" => "😇",
            "O:-)" => "😇",
            "3:)" => "😈",
            "3:-)" => "😈",
            "}:)" => "😈",
            "}:-)" => "😈",
            "*)" => "😉",
            "*-)" => "😉",
            ":-," => "😉",
            ";)" => "😉",
            ";-)" => "😉",
            ";-]" => "😉",
            ";D" => "😉",
            ";]" => "😉",
            ";^)" => "😉",
            ":-|" => "😐",
            ":|" => "😐",
            ":(" => "😒",
            ":-(" => "😒",
            ":-<" => "😒",
            ":-[" => "😒",
            ":-c" => "😒",
            ":<" => "😒",
            ":[" => "😒",
            ":c" => "😒",
            ":{" => "😒",
            ":っC" => "😒",
            "%)" => "😖",
            "%-)" => "😖",
            ":-P" => "😜",
            ":-b" => "😜",
            ":-p" => "😜",
            ":-Þ" => "😜",
            ":-þ" => "😜",
            ":P" => "😜",
            ":b" => "😜",
            ":p" => "😜",
            ":Þ" => "😜",
            ":þ" => "😜",
            ";(" => "😜",
            "X-P" => "😜",
            "XP" => "😜",
            "d:" => "😜",
            "x-p" => "😜",
            "xp" => "😜",
            ":-||" => "😠",
            ":@" => "😠",
            ":-." => "😡",
            ":-/" => "😡",
            ":/" => "😡",
            ":L" => "😡",
            ":S" => "😡",
            ":\\" => "😡",
            ":\"(" => "😢",
            ":\"-(" => "😢",
            "^5" => "😤",
            "^<_<" => "😤",
            "o/\\o" => "😤",
            "|-O" => "😫",
            "|;-)" => "😫",
            ":###.." => "😰",
            ":-###.." => "😰",
            "D-\":" => "😱",
            "D8" => "😱",
            "D:" => "😱",
            "D:<" => "😱",
            "D;" => "😱",
            "DX" => "😱",
            "v.v" => "😱",
            "8-0" => "😲",
            ":-O" => "😲",
            ":-o" => "😲",
            ":O" => "😲",
            ":o" => "😲",
            "O-O" => "😲",
            "O_O" => "😲",
            "O_o" => "😲",
            "o-o" => "😲",
            "o_O" => "😲",
            "o_o" => "😲",
            ":$" => "😳",
            "#-)" => "😵",
            ":#" => "😶",
            ":&" => "😶",
            ":-#" => "😶",
            ":-&" => "😶",
            ":-X" => "😶",
            ":X" => "😶",
            ":-J" => "😼",
            ":*" => "😽",
            ":^*" => "😽",
            "ಠ_ಠ" => "🙅",
            "*\\0/*" => "🙆",
            "\\o/" => "🙆",
            ":>" => "😄",
            ">.<" => "😡",
            ">:(" => "😠",
            ">:)" => "😈",
            ">:-)" => "😈",
            ">:/" => "😡",
            ">:O" => "😲",
            ">:P" => "😜",
            ">:[" => "😒",
            ">:\\" => "😡",
            ">;)" => "😈",
            ">_>^" => "😤",
            "^^" => "😊",
            ":sweat" => "😅",
        ];

        foreach ($emojis as $key => $emoji) {
            $string = str_replace($key, $emoji, $string);
        }

        return $string;
    }
}
