<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/xlsxwriter.class.php';

if (!empty($_GET['FORM_ID']) && !empty($_GET['RESULT_ID'])) {

    $data = \Local\Entities\InterraoWebformQuizTable::getList([
        'select' => [
            '*'
        ],
        'filter' => [
            'FORM_ID' => $_GET['FORM_ID'],
            'FORM_RESULT_ID' => $_GET['RESULT_ID']
        ],
        'limit' => 1
    ]);
    if ($arData = $data->fetch()) {
        $arData['RESULT_DATA'] = unserialize($arData['RESULT_DATA']);

        if (!empty($arData['RESULT_DATA']['TYPE'])) {
            $type = [];
            foreach ($arData['RESULT_DATA']['TYPE'] as $item) {
                $type[] = current($item);
            }
        }
        $file = [];
        $arFiles = [];
        if (!empty($arData['RESULT_DATA']['BUILDING_DRAWING'])) {
            $i = 1;
            foreach ($arData['RESULT_DATA']['BUILDING_DRAWING'] as $fileData) {
                $fileData = \CFile::GetFileArray($fileData);

                $ext = pathinfo($fileData['SRC'], PATHINFO_EXTENSION);

                $arFiles[] = [
                    'ORIGINAL_NAME' => $_GET['RESULT_ID'] . '-image' . $i . '.' . $ext,
                    'PATH' => $fileData['SRC']
                ];

                $file[] = (CMain::IsHTTPS() ? 'https' : 'http') . '://' . SITE_SERVER_NAME . $fileData['SRC'];
                $i++;
            }
        }

        if (!empty($arData['RESULT_DATA']['QR_IMAGE_ID'])) {
            $fileData = \CFile::GetFileArray($arData['RESULT_DATA']['QR_IMAGE_ID']);

            $arFiles[] = [
                'ORIGINAL_NAME' => $_GET['RESULT_ID'] . '-qr-map.png',
                'PATH' => $fileData['SRC']
            ];
        }

        $title = ['Название поля', 'Значение'];

        $csvData = [
            'Ф.И.О' => htmlspecialcharsbx($arData['RESULT_DATA']['USER_NAME']),
            'Название организации' => htmlspecialcharsbx($arData['RESULT_DATA']['COMPANY_NAME']),
            'Телефон' => htmlspecialcharsbx($arData['RESULT_DATA']['PHONE']),
            'E-mail' => htmlspecialcharsbx($arData['RESULT_DATA']['EMAIL']),
            'Тип размещения' => htmlspecialcharsbx(implode('; ', $type)),
            'Адрес объекта' => htmlspecialcharsbx($arData['RESULT_DATA']['ADDRESS_OBJECT']),
            'Ссылка на Яндекс картах' => htmlspecialcharsbx($arData['RESULT_DATA']['MAP_LINK']),
            'Транспортная доступность' => ($arData['RESULT_DATA']['TRANSPORT_ACCESSIBILITY'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['TRANSPORT_ACCESSIBILITY_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['TRANSPORT_ACCESSIBILITY']),
            'Площадь поверхности для размещения ФЭМ, кв. м' => htmlspecialcharsbx($arData['RESULT_DATA']['SURFACE_AREA']),
            'Размеры прилегающей территории, кв. м' => htmlspecialcharsbx($arData['RESULT_DATA']['ADJACENT_TERRITORY_SIZE']),
            'Наклон поверхности для размещения ФЭМ' => ($arData['RESULT_DATA']['INCLINATION_SURFACE'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['INCLINATION_SURFACE_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['INCLINATION_SURFACE']),
            'Материал кровли крыши или фасада' => ($arData['RESULT_DATA']['ROOF_MATERIAL'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['ROOF_MATERIAL_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['ROOF_MATERIAL']),
            'Высота поверхности для размещения ФЭМ, этаж' => htmlspecialcharsbx($arData['RESULT_DATA']['FLOOR']),
            'Ориентация поверхности' => ($arData['RESULT_DATA']['SURFACE_ORIENTATION'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['SURFACE_ORIENTATION_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['SURFACE_ORIENTATION']),
            'Фото или чертеж здания или поверхности размещения ФЭМ' => implode(', ', $file),
            'Установка двунаправленного счётчика' => ($arData['RESULT_DATA']['BIDIRECTIONAL_COUNTER'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['BIDIRECTIONAL_COUNTER_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['BIDIRECTIONAL_COUNTER']),
            'Тип СЭС' => htmlspecialcharsbx($arData['RESULT_DATA']['TYPE_CEC']),
            'Название комплекта' => ($arData['RESULT_DATA']['COMPLECT_NAME'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['COMPLECT_NAME_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['COMPLECT_NAME']),
            'Мощность модулей ФЭМ, кВт' => htmlspecialcharsbx($arData['RESULT_DATA']['FEM_MODULES_POWER']),
            'Ёмкость АКБ, кВт' => htmlspecialcharsbx($arData['RESULT_DATA']['BATTERY_CAPACITY']),
            'Требуется поставка оборудования от МЭС' => (!empty(htmlspecialcharsbx(current($arData['RESULT_DATA']['MATERIALS_DELIVERY'])))) ? htmlspecialcharsbx(current($arData['RESULT_DATA']['MATERIALS_DELIVERY'])) : 'Нет',
            'Требуется монтаж от МЭС' => (!empty(htmlspecialcharsbx(current($arData['RESULT_DATA']['MATERIALS_MONTAZH'])))) ? htmlspecialcharsbx(current($arData['RESULT_DATA']['MATERIALS_MONTAZH'])) : 'Нет',
            'Общая мощность электрооборудования на объекте, кВт' => htmlspecialcharsbx($arData['RESULT_DATA']['SUMMARY_POWER']),
            'Пиковые нагрузки во внутренней сети, кВт' => htmlspecialcharsbx($arData['RESULT_DATA']['PEAK_LOADS']),
            'Конфигрурация сети' => ($arData['RESULT_DATA']['CONFIGURATION_NET'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['CONFIGURATION_NET_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['CONFIGURATION_NET']),
            'Тип установленного электросчётчика' => ($arData['RESULT_DATA']['ELECTRIC_METER_TYPE'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['ELECTRIC_METER_TYPE_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['ELECTRIC_METER_TYPE']),
            'Существующий тариф, руб. за кВт*ч' => ($arData['RESULT_DATA']['ELECTRIC_TARIF'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['ELECTRIC_TARIF_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['ELECTRIC_TARIF']),
            'Среднесуточное потребление электроэнергии из сети, кВт' => htmlspecialcharsbx($arData['RESULT_DATA']['AVG_CONSUMPTION_ELECTRIC']),
            'Средний месячный счет за ЭЭ, в руб.' => htmlspecialcharsbx($arData['RESULT_DATA']['AVG_MONTHLY_ENERGY_BILL']),
            'Примерное соотношение такого потребления в кВт (темное/светлое время суток)?' => ($arData['RESULT_DATA']['APPROXIMATE_RATIO_CONSUMPTION'] == 'other') ? htmlspecialcharsbx($arData['RESULT_DATA']['APPROXIMATE_RATIO_CONSUMPTION_DOP_FIELD']) : htmlspecialcharsbx($arData['RESULT_DATA']['APPROXIMATE_RATIO_CONSUMPTION']),
            'Примерный доступный бюджет на осуществление проекта, руб.' => htmlspecialcharsbx($arData['RESULT_DATA']['APPROXIMATE_AVAILABLE_BUDGET']),
            'Ориентировочные сроки, к которым заказчик планирует завершить установку на своем объекте солнечной электростанции, дата' => htmlspecialcharsbx($arData['RESULT_DATA']['PROJECT_COMPLETION_TIME']),
        ];

        $filename = "" . $_GET['RESULT_ID'] . "-form.xlsx";

        $writer = new XLSXWriter();

        $header = ["string", "string"];

        $writer->writeSheetHeader('Sheet1', $header, $col_options = ['suppress_row'=>true, 'widths' => [50, 100]] );
        $writer->writeSheetRow('Sheet1', $title, [
            'halign' => 'left',
            'border' => 'left,right,top,bottom',
            'wrap_text' => true,
            'font-style' => 'bold'
        ]);
        foreach($csvData as $key => $data) {
            $writer->writeSheetRow('Sheet1', [$key, $data], [
                'halign' => 'left',
                'border' => 'left,right,top,bottom',
                'wrap_text' => true
            ]);
        }
        $writer->writeToFile($_SERVER['DOCUMENT_ROOT'] . '/upload/csv/' . $filename);


        $zip = new ZipArchive();
        $zipFilePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/csv/' . $_GET['RESULT_ID'] .'.zip';

        $res = $zip->open($zipFilePath, ZipArchive::CREATE);

        $zip->addFile($_SERVER['DOCUMENT_ROOT'] . '/upload/csv/' . $filename, $filename);
        if (!empty($arFiles)) {
            foreach ($arFiles as $image) {
                $zip->addFile($_SERVER['DOCUMENT_ROOT'] . $image['PATH'], $image['ORIGINAL_NAME']);
            }
        }
        $zip->close();

        unlink($_SERVER['DOCUMENT_ROOT'] . '/upload/csv/' . $filename);

        if (file_exists($zipFilePath)) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header('Content-type: application/zip');
            header("Content-Transfer-Encoding: Binary");
            header('Content-Disposition: attachment; filename="' . $_GET['RESULT_ID'] .'.zip"');
            header("Content-Length: ".filesize($zipFilePath));

            echo file_get_contents($zipFilePath);

            unlink($zipFilePath);
        }


        die();
    }
}