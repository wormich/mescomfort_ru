<?php

function avar_dump($data)
{
    global $USER;
    if ($USER->IsAdmin()) {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
}

function avar_dump_ex($data)
{
    global $USER;
    if ($USER->IsAdmin()) {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        exit();
    }
}

function file_var_dump($arParams = array(), $mode = "a")
{
    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/check_file.txt", $mode);
    ob_start();
    echo "===================\r\n";
    var_dump($arParams);
    echo "===================\r\n";
    $mytext = ob_get_clean();
    fwrite($fp, $mytext);
    fclose($fp);
}

function captcha_check($g_recaptcha_response)
{
    /*проверка капчи*/
    $url = "https://www.google.com/recaptcha/api/siteverify";
    /*secret - секретный код, взятый из личного кабинета капчи https://www.google.com/recaptcha/intro/index.html*/
    $post_data = array(
        "secret" => "6LfFs3kUAAAAAEoyffuuows_NHh6GOcXMrAIhaY0",
        "response" => $g_recaptcha_response
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    /*указываем, что у нас POST запрос*/
    curl_setopt($ch, CURLOPT_POST, 1);
    /*добавляем переменные*/
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);

    /*сайт проверки возвращает json*/
    $captcha_check = json_decode($output, true);
    return $captcha_check;
}

function getTermination($n, $titles) {
    $cases = array(2, 0, 1, 1, 1, 2);
    return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

/**
 * Форматирование чисел.
 * @param $value
 * @return string
 */
function decimalNumberFormat($value, $decimals = 2): string {
    if (!preg_match('/\d+.\d+/', $value)) {
        $decimals = 0;
    }
    return number_format($value, $decimals, ',', ' ');
}