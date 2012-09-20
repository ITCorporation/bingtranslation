<?php
    header('content-type: text/html; charset: utf-8');
    require_once("bingTranslate.class.php");

    $bing = new BingTranslate('trendstory_translate','0WTZF6wKpVTpgCw8CBdGvCaQYU4fD1FCZe4OaS+OM0s=');

    $translation = $bing->getTranslation("the book is on the table","en","pt");
    echo $translation["Translations"][0]["TranslatedText"];
