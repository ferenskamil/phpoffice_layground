<?php

namespace app;

class LangsHandler
{
    function displayLangsInTable() : void
    {
        $langs = $this->getLangs();
        $langs = array_map(function(string $lang){
            return $this->divideLangStringIntoParts($lang);
        }, $langs);

        echo <<<HTML
            <table style="border: 1px solid black">
                <tbody>
                    <tr>
                        <td colspan="2">Plugin_local_name</td>
                    </tr>
        HTML;
        foreach ($langs as $lang) {
            echo <<<HTML
                    <tr>
                        <td style="border:1px solid black">$lang->key</td>
                        <td style="border:1px solid black">$lang->value</td>
                    </tr>
            HTML;
        }
        echo <<<HTML
                </tbody>
            </table>
        HTML;
    }

    function convertObjAndDisplayLang() : void
    {
        $langs = $this->getLangs();
        $langs = array_map(function(string $lang){
            return $this->divideLangStringIntoParts($lang);
        }, $langs);

        foreach ($langs as $lang) {
            $lang->key = str_replace("'", "\'", $lang->key);
            $lang->value = str_replace("'", "\'", $lang->key);
            echo '$string[\'' . $lang->key . '\'] =  \''.  $lang->value  .'\';';
            echo '<hr>';
        }
    }

    function divideLangStringIntoParts(string $lang) : stdClass
    {
        $keyPart = $this->getKeyPartOfString($lang);
        $valuePart = $this->getValuePartOfString($lang);

        return (object) [
            'key' => $this->extractString($keyPart),
            'value' => $this->extractString($valuePart),
        ];
    }

    function getStrChar(string $str) : ?string
    {
        $firstQuotePos = strpos($str, '"');
        $firstApostrophePos = strpos($str, "'");

        if (empty($firstQuotePos) && empty($firstApostrophePos)) {
            echo 'xxx';
            return null;

        } else if (empty($firstQuotePos)) {
            $char = "'";

        } else if (empty($firstApostrophePos)) {
            $char = '"';

        } else if ($firstApostrophePos < $firstQuotePos) {
            $char = "'";

        } else if ($firstQuotePos < $firstApostrophePos) {
            $char = '"';
        }

        return $char;
    }

    function getKeyPartOfString(string $str)
    {
        $equalPos = strpos($str, '=');
        return explode('=', $str)[0];
    }

    function extractString(string $str) : string
    {
        $strChar = $this->getStrChar($str);
        $startPos = strpos($str, $strChar);
        $endPos = strrpos($str, $strChar);

        $result = substr($str, 0, strlen($str) - strlen(substr($str, $endPos)));
        $result = substr($result, $startPos + 1);

        return $result;
    }

    function getValuePartOfString(string $str) : string
    {
        $equalPos = strpos($str, '=');
        return substr($str, $equalPos);
    }

    function getLangs() : array
    {
        return [
            '$string["Hello Worl:::dxxxx"xxxxxxxxxxxxxxxxL"] = \'Va==:::::===lue\'',
            '$string[\'Lorem ipsu\'m dolor es\'] = \'Some value\'',
            '$string["Atu jakaś inna wartosć "] = \'A nie sory klucz\'',
            '$string["Hell\'o Worl:::dxxxx"xxxxxxxxxxxxxxxxL"] = \'Va==:::::===lue\'',
            '$string[\'Lorem ipsum dolor es\'] = \'Some value\'',
            '$string["Atu jakaś inna wartosć "] = \'A nie sory klucz\'',
            '$string["Hell\'o Worl:::dxxxx"xxxxxxxxxxxxxxxxL"] = \'Va==:::::===lue\'',
            '$string[\'Lorem ipsum dolor es\'] = \'Some value\'',
            '$string["Atu jakaś inna wartosć "] = \'A nie sory klucz\'',
        ];
    }
}

// (new LangsHandler())->displayLangsInTable();
(new LangsHandler())->convertObjAndDisplayLang();