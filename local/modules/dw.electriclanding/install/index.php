<?

use Bitrix\Main\Config\Option;
use \Bitrix\Main\EventManager;

class dw_electriclanding extends CModule
{
    const MODULE_ID = 'dw.electriclanding';

    public $MODULE_ID = 'dw.electriclanding',
        $MODULE_VERSION,
        $MODULE_VERSION_DATE,
        $MODULE_NAME = 'Электромонтажные работы (Лендинг)',
        $PARTNER_NAME = 'Interrao',
        $PARTNER_URI = '';

    public function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . 'version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

    function InstallFiles($arParams = array())
    {
        /*
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/templates')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/local/templates/' . $item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/include')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/include_landing/' . '/' . $item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/pages')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/' . $item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/components')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/local/components/' . $item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        */

        // Установка инфблоков !
        if (CModule::IncludeModule('iblock')) {
            $iblocktype = "dw_pes_landing";

            $obIBlockType = new CIBlockType;
            $arFields = array(
                "ID" => $iblocktype,
                "SECTIONS" => "Y",
                "LANG" => array(
                    "ru" => array(
                        "NAME" => "Заказ услуг (Лэндинг)",
                    )
                )
            );
            $res = $obIBlockType->Add($arFields);

            $obIblock = new CIBlock;
            $arFields = array(
                'NAME' => 'Слайдер',
                'CODE' => 'DW_PES_SLIDER',
                'ACTIVE' => 'Y',
                'IBLOCK_TYPE_ID' => $iblocktype,
                "SITE_ID" => array("s1") //Массив ID сайтов
            );
            $newIblockID = $obIblock->Add($arFields);
            Option::set(self::MODULE_ID, 'slider_iblock_id', $newIblockID);
            CIBlock::SetPermission($newIblockID, array('2' => 'R'));

            $arFields = array(
                "NAME" => "Картинка",
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => "PICTURE",
                "PROPERTY_TYPE" => "F",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "Ссылка",
                "ACTIVE" => "Y",
                "SORT" => "200",
                "CODE" => "LINK",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "Описание",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "DESC",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Чем опасна старая проводка',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'DESC' => 'Чем опасна старая проводка',
                    'LINK' => 'https://www.pes.spb.ru/services/elektromontazhnye_raboty/chem-opasna-staraya-provodka/',
                    'PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/slider_img.jpg'),
                )
            ]);

            $obIblock = new CIBlock;
            $arFields = array(
                'NAME' => 'Услуги',
                'CODE' => 'DW_PES_SERVICES',
                'ACTIVE' => 'Y',
                'IBLOCK_TYPE_ID' => $iblocktype,
                "SITE_ID" => array("s1") //Массив ID сайтов
            );
            $newIblockID = $obIblock->Add($arFields);
            Option::set(self::MODULE_ID, 'service_iblock_id', $newIblockID);
            CIBlock::SetPermission($newIblockID, array('2' => 'R'));

            $arFields = array(
                "NAME" => "Цена",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "PRICE",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Монтаж слаботочных систем и сигнализации',
                'CODE' => 'montazh-slabotochnykh-sistem-i-signalizatsii',
                'PREVIEW_TEXT' => 'Сигнализации, видеонаблюдение, системы контроля доступа',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_1.png'),
                'DETAIL_TEXT' => 'В Петроэлектросбыте&nbsp;есть возможность заказать услуги по монтажу сигнализаций и систем видеонаблюдения для квартиры, частного дома или офиса. А также систем контроля доступа и учета рабочего времени.<br>
 Сделаем аккуратный удобный монтаж проводки и розеток слаботочных систем: телефония, интернет, телевидения, домофон.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 9 500 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Подключение техники',
                'CODE' => 'podklyuchenie-tekhniki',
                'PREVIEW_TEXT' => 'Подключение электроплиты, стиральной и посудомоечной машины, теплого пола',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_2.png'),
                'DETAIL_TEXT' => 'Купили стиральную машину? Посудомойку? Плиту? Хотите установить теплый пол или бойлер?&nbsp;<br>
 Специалист Петроэлектросбыта&nbsp;поможет подключить новое оборудование с соблюдением действующих норм: ГОСТ, ПУЭ, ПТЭЭП и других, а также техники безопасности и особенностей электроснабжения Вашего дома и Вашей квартиры.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 530 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Замена розеток и выключателей',
                'CODE' => 'zamena-rozetok-vyklyuchateley',
                'PREVIEW_TEXT' => 'Установка и подключение электроточек: розеток, выключателей, коробок, звонков',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_3.png'),
                'DETAIL_TEXT' => 'В системе электроснабжения Вашей квартиры самыми первыми выходят из строя и начинают представлять опасность розетки и выключатели. Мы корректно заменим «выпадающую» или греющуюся розетку на современную без демонтажа всей проводки. Проведем дополнительную розетку, «утопим» розетки и выключатели в стену.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 315 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Установка (замена) автоматов, УЗО',
                'CODE' => 'ustanovka-zamena-avtomatov-uzo',
                'PREVIEW_TEXT' => 'Замена и установка дифференциальных автоматов, автоматических выключателей, УЗО',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_4.png'),
                'DETAIL_TEXT' => 'Замените устаревший автомат на отвечающий современным требованиям. Установите УЗО, чтобы чувствовать себя в безопасности.&nbsp;<br>
 Монтаж одного элемента - от 500 руб.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 500 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Установка и монтаж осветительных приборов',
                'CODE' => 'ustanovka-i-montazh-osvetitelnykh-priborov',
                'PREVIEW_TEXT' => 'Сборка, монтаж, демонтаж люстр,<br>
			 светильников, бра',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_5.png'),
                'DETAIL_TEXT' => 'Демонтируем и соберем люстру любой сложности.<br>
 Установим потолочные светильники, бра, выключатели.&nbsp;<br>
 Заменим устаревшие ртутные лампы дневного света и лампы накаливания на современные экономичные и яркие светодиоды.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 525 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Проектирование электроснабжения',
                'CODE' => 'proektirovanie-elektrosnabzheniya',
                'PREVIEW_TEXT' => 'Выезд специалиста, обмер, смета, техническое задание, проектная и исполнительная документация',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_6.png'),
                'DETAIL_TEXT' => 'Осмотр и консультация (без составления сметы) – от 680 рублей. При заключении договора на электромонтаж (от 15 000 руб.) – выезд и консультация бесплатно.<br>
 Обращайтесь к нам, если нужна разработка проекта электроснабжения, сметы, экспертизы, исполнительной документации.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 3 000 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Электрика под ключ',
                'CODE' => 'elektrika-pod-klyuch',
                'PREVIEW_TEXT' => 'Все виды работ в квартире, частном доме, коттедже',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_7.png'),
                'DETAIL_TEXT' => 'Выполним все работы по электрике «под ключ»: от проектирования до установки розеток и выключателей за 3 три дня.<br>
 Штробление без пыли, замена алюминиевой проводки на медную, установка автоматов защиты, подключение ввода, замена (монтаж) коробки электрощита, установка розеток и выключателей, коробок расключения, монтаж люстр и светильников.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 30 000 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Реконструкция электрощита',
                'CODE' => 'rekonstruktsiya-elektroshchita',
                'PREVIEW_TEXT' => 'Замена, перенос, монтаж счетчиков и щита учета, подключение ввода',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_8.png'),
                'DETAIL_TEXT' => 'Заменим Ваш старый электрощит современным компактным боксом. Любые работы в щите учета: перенос в квартиру или вынос на лестницу, перенос и замена счетчика.<br>
 <br>
 Установка и сборка пластикового бокса наружного – от 3500 руб.<br>
 Установка и сборка пластикового бокса внутреннего (включая врезку в бетон или кирпич) – от 4000 руб.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 3 500 руб.',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Замена электропроводки',
                'CODE' => 'zamena-elektroprovodki',
                'PREVIEW_TEXT' => '1-к. квартира, 2-к. квартира, 3-к. квартира, частный дом',
                'PREVIEW_PICTURE' => CFile::MakeFileArray('/local/templates/elektromontazhnye_raboty/images/install_service_9.png'),
                'DETAIL_TEXT' => 'Полная замена проводки в однокомнатной квартире включает:&nbsp;<br>
 – штробление стен – 15 пог. м;<br>
 – укладка проводов – 40 пог. м;<br>
 – врезка подрозетников – 12 шт.;<br>
 – установка розеток, выключателей – 12 шт.;<br>
 – установка осветительных приборов (люстра, светильник, бра) – 5 шт.<br>
 <br>
 Полная замена проводки в двухкомнатной квартире включает:<br>
 – штробление стен – 20 пог. м;<br>
 – укладка проводов – 50 пог. м;<br>
 – врезка подрозетников – 16 шт.;<br>
 – установка розеток, выключателей – 16 шт.;<br>
 – установка осветительных приборов (люстра, светильник, бра) – 6 шт.<br>
 <br>
 Полная замена проводки в трехкомнатной квартире включает:<br>
 – штробление стен – 25 пог. м;<br>
 – укладка проводов – 60 пог. м;<br>
 – врезка подрозетников – 20 шт.;<br>
 – установка розеток, выключателей – 20 шт.;<br>
 – установка осветительных приборов (люстра, светильник, бра) – 7 шт.<br>
 Во всех комплектах включено штробление без пыли.<br>
 <br>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'PRICE' => 'от 17 500 руб.',
                )
            ]);

            $obIblock = new CIBlock;
            $arFields = array(
                'NAME' => 'Вопрос-ответ',
                'CODE' => 'DW_PES_FAQ',
                'ACTIVE' => 'Y',
                'IBLOCK_TYPE_ID' => $iblocktype,
                "SITE_ID" => Array('s1')
            );
            $newIblockID = $obIblock->Add($arFields);
            Option::set(self::MODULE_ID, 'faq_iblock_id', $newIblockID);
            CIBlock::SetPermission($newIblockID, array('2' => 'R'));

            $arFields = array(
                "NAME" => "Ширина поля",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "FIELD_WIDTH",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Искрят розетки',
                'PREVIEW_TEXT' => 'Искрят розетки',
                'DETAIL_TEXT' => '<p style="text-align: justify;">
	 Проблема может быть в плохом контакте вилки провода с розеткой. Если Вы давно не протягивали контакты в розетке и не проводили профилактический осмотр электроточек, то рекомендуем произвести оценку ситуации профессиональным электриком и устранить проблему.
</p>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'FIELD_WIDTH' => '200',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'У меня мигает свет',
                'PREVIEW_TEXT' => 'У меня мигает свет',
                'DETAIL_TEXT' => '<p style="text-align: justify;">
	 Мигание лампы может происходить по ряду причин: неисправна сама лампа, плохой контакт лампочки и патрона, либо имеется неисправность в самой проводке или коммутирующем аппарате (автоматический выключатель/пробки). Рекомендуем произвести оценку ситуации профессиональным электриком и устранить проблему.
</p>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'FIELD_WIDTH' => '200',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'Я увидел ваше объявление («плохая проводка – причина пожаров») и обращаюсь к Вам чтоб проконсультироваться по качеству/проблемам в своей проводке?',
                'PREVIEW_TEXT' => 'Я увидел ваше объявление («плохая проводка – причина пожаров») и обращаюсь к Вам чтоб проконсультироваться по качеству/проблемам в своей проводке?',
                'DETAIL_TEXT' => '<p style="text-align: justify;">
	 Если Вы не меняли электропроводку более 15 лет и она алюминиевая, то стоит задуматься над ее заменой, ведь производитель гарантирует целостность алюминиевого изделия в срок не более 15 лет.<br>
	 С течением времени алюминий имеет свойство менять свои характеристики, тем более алюминиевая проводка в старых домах рассчитывалась на другую нагрузку. При длительных нагрузках высокой мощности алюминий теряет свои прочностные свойства, что в дальнейшем может привести к микроразрывам внутри проводки и как следствие - короткому замыканию.<br>
	 При коротком замыкании резко увеличивается сила тока, проходимого в проводке, при этом температура проводника возрастает до 200 градусов, что может привести к:<br>
	 1) пожару, ввиду возгорания изоляции проводки и подсоединенных электротехнических предметов;<br>
	 2) выходу из строя бытовой техники;<br>
	 3) поражению электрическим током при контакте с оголенной частью проводки.
</p>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'FIELD_WIDTH' => '',
                )
            ]);

            (new CIBlockElement())->Add([
                'IBLOCK_ID' => $newIblockID,
                'NAME' => 'У меня при включении электрооборудования выбивает автомат (автоматический выключатель)?',
                'PREVIEW_TEXT' => 'У меня при включении электрооборудования выбивает автомат (автоматический выключатель)?',
                'DETAIL_TEXT' => '<p style="text-align: justify;">
	Это может означать, что автоматический выключатель не рассчитан на такую мощность или сама проводка имеет изъян, и при нагрузке возникает короткое замыкание, что приводит к росту силы тока и, как следствие, автоматический выключатель разъединяет линию. Рекомендуем произвести оценку ситуации профессиональным электриком и устранить проблему. Диагностику можно заказать у нас.
</p>',
                "DETAIL_TEXT_TYPE" => 'html',
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => array(
                    'FIELD_WIDTH' => '',
                )
            ]);

            $obIblock = new CIBlock;
            $arFields = array(
                'NAME' => 'Форма заказа услуг',
                'CODE' => 'DW_PES_FORM',
                'ACTIVE' => 'Y',
                'IBLOCK_TYPE_ID' => $iblocktype,
                "SITE_ID" => Array('s1')
            );
            $newIblockID = $obIblock->Add($arFields);
            Option::set(self::MODULE_ID, 'form_iblock_id', $newIblockID);
            CIBlock::SetPermission($newIblockID, array('2' => 'R'));

            $arFields = array(
                "NAME" => "Телефон на связи",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "PHONE",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "E-mail",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "EMAIL",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "Комментарий",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "COMMENT",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "Улица",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "STREET",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "Дом",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "HOME",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "Корпус",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "KORP",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);

            $arFields = array(
                "NAME" => "Квартира",
                "ACTIVE" => "Y",
                "SORT" => "300",
                "CODE" => "KVARTIRA",
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $newIblockID,
            );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);
        }
        return true;
    }

    function UnInstallFiles()
    {
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/components')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.' || !is_dir($p0 = $p . '/' . $item))
                        continue;

                    $dir0 = opendir($p0);
                    while (false !== $item0 = readdir($dir0)) {
                        if ($item0 == '..' || $item0 == '.')
                            continue;
                        DeleteDirFilesEx('/local/components/' . $item . '/' . $item0);
                    }
                    closedir($dir0);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . self::MODULE_ID . '/install/templates')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.' || !is_dir($p0 = $p . '/' . $item))
                        continue;

                    $dir0 = opendir($p0);
                    while (false !== $item0 = readdir($dir0)) {
                        if ($item0 == '..' || $item0 == '.')
                            continue;
                        DeleteDirFilesEx('/local/templates/' . $item . '/' . $item0);
                    }
                    closedir($dir0);
                }
                closedir($dir);
            }
        }

        // Удаление инфоблоков
        if (CModule::IncludeModule('iblock')) {
            CIBlockType::Delete('dw_pes_landing');
        }
        return true;
    }


    public function InstallEventsHandlers()
    {
    }

    public function UnInstallEventsHandlers()
    {
    }

    public function DoInstall()
    {
        RegisterModule($this->MODULE_ID);

        $this->InstallFiles();
        $this->InstallEventsHandlers();
    }

    public function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);

        $this->UnInstallFiles();
        $this->UnInstallEventsHandlers();
    }
}
