<?php
    header('content-type: text/html; charset: utf-8');
    require_once("bingTranslate.class.php");

    $bing = new BingTranslate('CLIENT_ID','CLIENT_SECRET');

    $translation = $bing->getTranslation("the book is on the table","en","pt");
    echo $translation["Translations"][0]["TranslatedText"];
