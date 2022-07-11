<?php

namespace Local\Form;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Loader;
use Local\Entities\FormAnswerTable;
use Local\Entities\FormFieldTable;
use Local\Entities\FormResultAnswerTable;
use Local\Entities\InterraoWebformQuizTable;
use Local\Helpers\Constants;

class FormQuestionsList
{

    const CSV_BUTTON_NAME = 'Скачать всё для печати';
    const CSV_BUTTON_TITLE = 'Скачать всё для печати';
    //Получать ID из базы смысла нет, так как в символьный код входит ID формы
    const WEB_FORM_ID = 3;

    const PROPERTIES_TYPE = [
      'Сетевые солнечные электростанции',
      'Автономные солнечные электростанции',
      'Гибридные/универсальные солнечные электростанции'
    ];

    const PROPERTIES_SECTION = [
      'Солнечные электростанции'
    ];

    /**
     * Формирование массива с данными формы
     * @param array $elementData
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function getFormData($elementData = [])
    {
        $answer = \CFormResult::GetDataByID(
          $_GET['RESULT_ID'],
          [],
          $result,
          $arAnswer2
        );

        $resultData = InterraoWebformQuizTable::getList([
          'select' => ['ID', 'RESULT_DATA'],
          'filter' => [
            'FORM_ID' => $_GET['WEB_FORM_ID'],
            'FORM_RESULT_ID' => $_GET['RESULT_ID']
          ]
        ]);

        $data = [];
        if ($resultData->getSelectedRowsCount() > 0) {
            $result = $resultData->fetch();
            $resultId = $result['ID'];
            $data = unserialize($result['RESULT_DATA']);
        }

        $answerFormData = [
          'USER_NAME' => (!empty($data['USER_NAME'])) ? $data['USER_NAME'] : current($arAnswer2['name'])['USER_TEXT'],
          'PHONE' => (!empty($data['PHONE'])) ? $data['PHONE'] : current($arAnswer2['phone'])['USER_TEXT'],
          'EMAIL' => (!empty($data['EMAIL'])) ? $data['EMAIL'] : current($arAnswer2['mail'])['USER_TEXT'],
          'ADDRESS_OBJECT' => (!empty($data['ADDRESS_OBJECT'])) ? $data['ADDRESS_OBJECT'] : current(
            $arAnswer2['comment']
          )['USER_TEXT'],
        ];

        if (!empty($data['TYPE'])) {
            $type = [];
            foreach ($data['TYPE'] as $item) {
                $type[] = current($item);
            }
        }

        if (!empty($data['QR_IMAGE_ID'])) {
            $qrLink = \CFile::GetPath($data['QR_IMAGE_ID']);
        } elseif (!empty($data['MAP_LINK']) && empty($data['QR_IMAGE_ID'])) {
            $data['QR_IMAGE_ID'] = self::saveQrCodeFile($data['MAP_LINK']);
            //Обновляем изображение в базе
            $res = \Local\Entities\InterraoWebformQuizTable::update($resultId, [
              'RESULT_DATA' => serialize($data)
            ]);
            $qrLink = \CFile::GetPath($data['QR_IMAGE_ID']);
        }

        $complect = [];

        $dbComplect = SectionTable::getList([
          'select' => [
            'ELEMENT_ID' => 'ELEMENTS.ID',
            'ELEMENT_NAME' => 'ELEMENTS.NAME',
            'SERVICE_TYPE' => 'ELEMENTS.SERVICE_TYPE.VALUE',
            'PROPERTY_FEM' => 'ELEMENTS.FEM.VALUE',
            'PROPERTY_AKB' => 'ELEMENTS.AKB.VALUE',
            'TYPE.ID',
            'TYPE.NAME',
          ],
          'filter' => [
            'IBLOCK_ID' => Constants::IBLOCK_CATALOG_SERVICES,
            '=CODE' => 'solnechnye-elektrostantsii',
            '=ACTIVE' => 'Y'
          ],
          'runtime' => [
            new ReferenceField(
              'ELEMENTS',
              \Bitrix\Iblock\Elements\ElementCatalogOfServicesTable::getEntity(),
              [
                '=ref.IBLOCK_SECTION_ID' => 'this.ID'
              ]

            ),
            new ReferenceField(
              'TYPE',
              \Bitrix\Iblock\Elements\ElementServicesTypeTable::getEntity(),
              [
                '=ref.ID' => 'this.SERVICE_TYPE',
              ]
            )
          ],
          'order' => [
            'NAME' => 'asc'
          ],
          'cache' => [
            'ttl' => 86000
          ]
        ]);

        $jsonComplectData = [];

        $complect[] = ['NAME' => '(Не установлено)'];
        while ($complectItem = $dbComplect->fetch()) {
            switch (strip_tags($complectItem['IBLOCK_SECTION_TYPE_NAME'])) {
                case 'Сетевые солнечные электростанции':
                    $complectItem['IBLOCK_SECTION_TYPE_NAME'] = 'Сетевая';
                    break;
                case 'Автономные солнечные электростанции':
                    $complectItem['IBLOCK_SECTION_TYPE_NAME'] = 'Автономная';
                    break;
                case 'Гибридные/универсальные солнечные электростанции':
                    $complectItem['IBLOCK_SECTION_TYPE_NAME'] = 'Гибридная';
                    break;
                case 'Резервное электроснабжение на базе АКБ с функцией ИБП':
                    $complectItem['IBLOCK_SECTION_TYPE_NAME'] = 'АКБ на базе ИБП';
                    break;
            }

            $complect[] = [
              'NAME' => strip_tags($complectItem['ELEMENT_NAME']),
              'CODE' => strip_tags($complectItem['ELEMENT_NAME']),
              'TYPE' => $complectItem['IBLOCK_SECTION_TYPE_ID'],
              'TYPE_NAME' => $complectItem['IBLOCK_SECTION_TYPE_NAME'],
              'FEM' => $complectItem['PROPERTY_FEM'],
              'AKB' => $complectItem['PROPERTY_AKB'],
            ];

            $jsonComplectData[$complectItem['IBLOCK_SECTION_TYPE_NAME']][] = [
              'NAME' => strip_tags($complectItem['ELEMENT_NAME']),
              'CODE' => strip_tags($complectItem['ELEMENT_NAME']),
              'TYPE' => $complectItem['IBLOCK_SECTION_TYPE_ID'],
              'TYPE_NAME' => $complectItem['IBLOCK_SECTION_TYPE_NAME'],
              'FEM' => $complectItem['PROPERTY_FEM'],
              'AKB' => $complectItem['PROPERTY_AKB'],
            ];
        }

        $el = [];
        if (in_array($elementData['SECTION_NAME'], self::PROPERTIES_SECTION)) {
            if (!empty($data['COMPLECT_NAME'])) {
                $el['COMPLECT_NAME'] = $data['COMPLECT_NAME'];
            } else {
                $el['COMPLECT_NAME'] = $elementData['NAME'];
            }

            if (!empty($data['FEM_MODULES_POWER'])) {
                $el['FEM_MODULES_POWER'] = $data['FEM_MODULES_POWER'];
            } else {
                $el['FEM_MODULES_POWER'] = $elementData['PROPERTY_FEM_VALUE'];
            }

            if (!empty($data['BATTERY_CAPACITY'])) {
                $el['BATTERY_CAPACITY'] = $data['BATTERY_CAPACITY'];
            } else {
                $el['BATTERY_CAPACITY'] = $elementData['PROPERTY_AKB_VALUE'];
            }
        } else {
            $el['COMPLECT_NAME'] = $data['COMPLECT_NAME'];
            $el['FEM_MODULES_POWER'] = $data['FEM_MODULES_POWER'];
            $el['BATTERY_CAPACITY'] = $data['BATTERY_CAPACITY'];
        }

        $arFields = [
          'Контактная информация' => [
            'USER_NAME' => [
              'NAME' => 'Ф.И.О',
              'FIELD_TYPE' => 'text',
              'FIELD_NAME' => 'QUIZ[USER_NAME]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($answerFormData['USER_NAME'])) ? $answerFormData['USER_NAME'] : ''
            ],
            'COMPANY_NAME' => [
              'NAME' => 'Название организации',
              'FIELD_TYPE' => 'text',
              'FIELD_NAME' => 'QUIZ[COMPANY_NAME]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['COMPANY_NAME'])) ? $data['COMPANY_NAME'] : ''
            ],
            'PHONE' => [
              'NAME' => 'Телефон',
              'FIELD_TYPE' => 'text',
              'FIELD_NAME' => 'QUIZ[PHONE]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($answerFormData['PHONE'])) ? $answerFormData['PHONE'] : ''
            ],
            'EMAIL' => [
              'NAME' => 'E-mail',
              'FIELD_TYPE' => 'email',
              'FIELD_NAME' => 'QUIZ[EMAIL]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($answerFormData['EMAIL'])) ? $answerFormData['EMAIL'] : ''
            ]
          ],
          'Тип размещения СЭС' => [
            'TYPE' => [
              'NAME' => 'Тип размещения',
              'FIELD_TYPE' => 'checkbox',
              'FIELD_NAME' => 'QUIZ[TYPE]',
              'LIST_PARAMS' => [
                [
                  'CODE' => 'QUIZ[TYPE][earth]',
                  'NAME' => 'На земле или прилегающей территории',
                ],
                [
                  'CODE' => 'QUIZ[TYPE][industrial_roof]',
                  'NAME' => 'На крыше промышленного здания',
                ],
                [
                  'CODE' => 'QUIZ[TYPE][residential_roof]',
                  'NAME' => 'На крыше жилого здания',
                ],
                [
                  'CODE' => 'QUIZ[TYPE][facade]',
                  'NAME' => 'Интегрированная в фасад',
                ],
              ],
              'VALUE' => (!empty($type)) ? $type : '',
            ],
            'ADDRESS_OBJECT' => [
              'NAME' => 'Адрес объекта',
              'FIELD_TYPE' => 'text',
              'FIELD_NAME' => 'QUIZ[ADDRESS_OBJECT]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($answerFormData['ADDRESS_OBJECT'])) ? $answerFormData['ADDRESS_OBJECT'] : ''
            ],
            'MAP_LINK' => [
              'NAME' => 'Ссылка на <a target="_blank" href="http://maps.yandex.ru/">Яндекс картах</a>',
              'FIELD_TYPE' => 'text',
              'FIELD_NAME' => 'QUIZ[MAP_LINK]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['MAP_LINK'])) ? $data['MAP_LINK'] : '',
            ],
            'QR_IMAGE_ID' => [
              'NAME' => 'ID изображения qr кода',
              'FIELD_NAME' => 'QUIZ[QR_IMAGE_ID]',
              'VALUE' => $data['QR_IMAGE_ID'],
              'FIELD_TYPE' => 'hidden'
            ],
            'QR_IMAGE' => [
              'NAME' => 'QR код на Яндекс карты',
              'VALUE' => $qrLink,
              'FIELD_TYPE' => 'image_link'
            ],
            'TRANSPORT_ACCESSIBILITY' => [
              'NAME' => 'Транспортная доступность',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[TRANSPORT_ACCESSIBILITY]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['TRANSPORT_ACCESSIBILITY'])) ? $data['TRANSPORT_ACCESSIBILITY'] : '',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Круглогодичная',
                  'NAME' => 'Круглогодичная',
                ],
                [
                  'CODE' => 'Сезонная',
                  'NAME' => 'Сезонная',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[TRANSPORT_ACCESSIBILITY_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['TRANSPORT_ACCESSIBILITY_DOP_FIELD'])) ? $data['TRANSPORT_ACCESSIBILITY_DOP_FIELD'] : '',
                ]
              ],
            ],
            'SURFACE_AREA' => [
              'NAME' => 'Площадь поверхности для размещения ФЭМ, кв. м',
              'FIELD_NAME' => 'QUIZ[SURFACE_AREA]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['SURFACE_AREA'])) ? $data['SURFACE_AREA'] : '',
            ],
            'ADJACENT_TERRITORY_SIZE' => [
              'NAME' => 'Размеры прилегающей территории, кв. м',
              'FIELD_NAME' => 'QUIZ[ADJACENT_TERRITORY_SIZE]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['ADJACENT_TERRITORY_SIZE'])) ? $data['ADJACENT_TERRITORY_SIZE'] : '',
            ],
            'INCLINATION_SURFACE' => [
              'NAME' => 'Наклон поверхности для размещения ФЭМ',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[INCLINATION_SURFACE]',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Плоская поверхность',
                  'NAME' => 'Плоская поверхность',
                ],
                [
                  'CODE' => 'Наклон до 10 градусов',
                  'NAME' => 'Наклон до 10 градусов',
                ],
                [
                  'CODE' => 'Наклон до 20 градусов',
                  'NAME' => 'Наклон до 20 градусов',
                ],
                [
                  'CODE' => 'Наклон до 30 градусов',
                  'NAME' => 'Наклон до 30 градусов',
                ],
                [
                  'CODE' => 'Наклон до 40 градусов',
                  'NAME' => 'Наклон до 40 градусов',
                ],
                [
                  'CODE' => 'Наклон более 40 градусов',
                  'NAME' => 'Наклон более 40 градусов',
                ],
                [
                  'CODE' => 'Вертикальная поверхность',
                  'NAME' => 'Вертикальная поверхность',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'VALUE' => (!empty($data['INCLINATION_SURFACE'])) ? $data['INCLINATION_SURFACE'] : '',
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[INCLINATION_SURFACE_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['INCLINATION_SURFACE_DOP_FIELD'])) ? $data['INCLINATION_SURFACE_DOP_FIELD'] : '',
                ]
              ],
            ],
            'ROOF_MATERIAL' => [
              'NAME' => 'Материал кровли крыши или фасада',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[ROOF_MATERIAL]',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Металлочерепица',
                  'NAME' => 'Металлочерепица',
                ],
                [
                  'CODE' => 'Фальцевая кровля (сталь, медь)',
                  'NAME' => 'Фальцевая кровля (сталь, медь)',
                ],
                [
                  'CODE' => 'Профнастил',
                  'NAME' => 'Профнастил',
                ],
                [
                  'CODE' => 'Ондулин',
                  'NAME' => 'Ондулин',
                ],
                [
                  'CODE' => 'Шифер',
                  'NAME' => 'Шифер',
                ],
                [
                  'CODE' => 'Керамическая черепица',
                  'NAME' => 'Керамическая черепица',
                ],
                [
                  'CODE' => 'Песчано-цеметная черепица',
                  'NAME' => 'Песчано-цеметная черепица',
                ],
                [
                  'CODE' => 'Сланцевая кровля',
                  'NAME' => 'Сланцевая кровля',
                ],
                [
                  'CODE' => 'Гибкая черепица - гонтом, шиглас (стеклохолст с битумом)',
                  'NAME' => 'Гибкая черепица - гонтом, шиглас (стеклохолст с битумом)',
                ],
                [
                  'CODE' => 'Рулонная кровля - гидроизол, стеклоизол',
                  'NAME' => 'Рулонная кровля - гидроизол, стеклоизол',
                ],
                [
                  'CODE' => 'Мембранная кровля - ПВХ, ЭПДМ, ТПО',
                  'NAME' => 'Мембранная кровля - ПВХ, ЭПДМ, ТПО',
                ],
                [
                  'CODE' => 'Наливная кровля - мастичная',
                  'NAME' => 'Наливная кровля - мастичная',
                ],
                [
                  'CODE' => 'Оцелендрованное дерево, сруб',
                  'NAME' => 'Оцелендрованное дерево, сруб',
                ],
                [
                  'CODE' => 'Клееный профилированный брус',
                  'NAME' => 'Клееный профилированный брус',
                ],
                [
                  'CODE' => 'Монолитный бетон',
                  'NAME' => 'Монолитный бетон',
                ],
                [
                  'CODE' => 'Полнотельный кирпич',
                  'NAME' => 'Полнотельный кирпич',
                ],
                [
                  'CODE' => 'Керамические блоки',
                  'NAME' => 'Керамические блоки',
                ],
                [
                  'CODE' => 'Ячеистый бетон',
                  'NAME' => 'Ячеистый бетон',
                ],
                [
                  'CODE' => 'Газобетон',
                  'NAME' => 'Газобетон',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'VALUE' => (!empty($data['ROOF_MATERIAL'])) ? $data['ROOF_MATERIAL'] : '',
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[ROOF_MATERIAL_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['ROOF_MATERIAL_DOP_FIELD'])) ? $data['ROOF_MATERIAL_DOP_FIELD'] : '',
                ]
              ],
            ],
            'FLOOR' => [
              'NAME' => 'Высота поверхности для размещения ФЭМ, этаж',
              'FIELD_NAME' => 'QUIZ[FLOOR]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['FLOOR'])) ? $data['FLOOR'] : '',
            ],
            'SURFACE_ORIENTATION' => [
              'NAME' => 'Ориентация поверхности',
              'FIELD_NAME' => 'QUIZ[SURFACE_ORIENTATION]',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['SURFACE_ORIENTATION'])) ? $data['SURFACE_ORIENTATION'] : '',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Север',
                  'NAME' => 'Север',
                ],
                [
                  'CODE' => 'Северо-восток',
                  'NAME' => 'Северо-восток',
                ],
                [
                  'CODE' => 'Восток',
                  'NAME' => 'Восток',
                ],
                [
                  'CODE' => 'Юго-восток',
                  'NAME' => 'Юго-восток',
                ],
                [
                  'CODE' => 'Юг',
                  'NAME' => 'Юг',
                ],
                [
                  'CODE' => 'Юго-запад',
                  'NAME' => 'Юго-запад',
                ],
                [
                  'CODE' => 'Запад',
                  'NAME' => 'Запад',
                ],
                [
                  'CODE' => 'Северо-запад',
                  'NAME' => 'Северо-запад',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[SURFACE_ORIENTATION_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['SURFACE_ORIENTATION_DOP_FIELD'])) ? $data['SURFACE_ORIENTATION_DOP_FIELD'] : '',
                ]
              ],
            ],
            'BUILDING_DRAWING' => [
              'NAME' => 'Фото или чертеж здания или поверхности размещения ФЭМ',
              'FIELD_NAME' => 'BUILDING_DRAWING',
              'FIELD_TYPE' => 'image',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['BUILDING_DRAWING'])) ? $data['BUILDING_DRAWING'] : '',
            ],
            'BIDIRECTIONAL_COUNTER' => [
              'NAME' => 'Установка двунаправленного счётчика',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[BIDIRECTIONAL_COUNTER]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['BIDIRECTIONAL_COUNTER'])) ? $data['BIDIRECTIONAL_COUNTER'] : '',
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[BIDIRECTIONAL_COUNTER_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['BIDIRECTIONAL_COUNTER_DOP_FIELD'])) ? $data['BIDIRECTIONAL_COUNTER_DOP_FIELD'] : '',
                ]
              ],
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Требуется установка',
                  'NAME' => 'Требуется установка',
                ],
                [
                  'CODE' => 'Не требуется установка',
                  'NAME' => 'Не требуется установка',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ]
            ],
          ],
          'Особенности подключения СЭС' => [
            'TYPE_CEC' => [
              'NAME' => 'Тип СЭС',
              'FIELD_TYPE' => 'radio',
              'FIELD_NAME' => 'QUIZ[TYPE_CEC]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['TYPE_CEC'])) ? $data['TYPE_CEC'] : '',
              'DESCRIPTION' => '
<span data-rel="Сетевая">Экономия при оплате счетов за электроэнергию в адрес энергосбытовой компании – <strong>только сетевые СЭС</strong><br></span>
<span data-rel="Гибридная">Сохранение энергообеспечения на объекте при частых аварийных отключениях подачи на линиях центральных электропередач – <strong>гибридные СЭС</strong> или комплекты <strong>ИБП без ФЭМ</strong><br></span>
<span data-rel="Автономная">Обеспечение автономного энергоснабжения на удаленных от электросетевой инфраструктуры объектах (временных, мобильных объектах) – <strong>автономные СЭС</strong><br></span>
<span data-rel="АКБ на базе ИБП">Обеспечение автономного энергоснабжения на удаленных от электросетевой инфраструктуры объектах (временных, мобильных объектах) – <strong>автономные СЭС</strong></span>
',
              'LIST_PARAMS' => [
                [
                  'CODE' => 'setevaya',
                  'NAME' => 'Сетевая',
                ],
                [
                  'CODE' => 'avtonomnaya',
                  'NAME' => 'Автономная',
                ],
                [
                  'CODE' => 'gibridnaya',
                  'NAME' => 'Гибридная',
                ],
                [
                  'CODE' => 'akb_ibp',
                  'NAME' => 'АКБ на базе ИБП',
                ],
              ],
            ],
            'COMPLECT_NAME' => [
              'NAME' => 'Название комплекта',
              'FIELD_NAME' => 'QUIZ[COMPLECT_NAME]',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_WIDTH' => 50,
              'LIST_PARAMS' => array_merge($complect, [
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ]),
              'JS_PARAMS' => $jsonComplectData,
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[COMPLECT_NAME_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['COMPLECT_NAME_DOP_FIELD'])) ? $data['COMPLECT_NAME_DOP_FIELD'] : '',
                ]
              ],
              'VALUE' => $el['COMPLECT_NAME'],
            ],
            'FEM_MODULES_POWER' => [
              'NAME' => 'Мощность модулей ФЭМ, кВт',
              'FIELD_NAME' => 'QUIZ[FEM_MODULES_POWER]',
              'FIELD_TYPE' => 'text',
//                    'FIELD_WIDTH' => 50,
              'FIELD_PARAM' => 'style="width:60%;"',
              'VALUE' => $el['FEM_MODULES_POWER'],
            ],
            'BATTERY_CAPACITY' => [
              'NAME' => 'Ёмкость АКБ, кВт',
              'FIELD_NAME' => 'QUIZ[BATTERY_CAPACITY]',
              'FIELD_TYPE' => 'text',
//                    'FIELD_WIDTH' => 50,
              'FIELD_PARAM' => 'style="width:60%;"',
              'VALUE' => $el['BATTERY_CAPACITY'],
            ],
            'MATERIALS_DELIVERY' => [
              'NAME' => 'Требуется поставка оборудования от МЭС',
              'FIELD_NAME' => 'QUIZ[MATERIALS_DELIVERY]',
              'FIELD_TYPE' => 'checkbox',
              'FIELD_WIDTH' => 50,
              'LIST_PARAMS' => [
                [
                  'CODE' => 'QUIZ[MATERIALS_DELIVERY]',
                  'NAME' => 'Да',
                ],
              ],
              'VALUE' => (!empty(current($data['MATERIALS_DELIVERY']))) ? current($data['MATERIALS_DELIVERY']) : '',
            ],
            'MATERIALS_MONTAZH' => [
              'NAME' => 'Требуется монтаж от МЭС',
              'FIELD_NAME' => 'QUIZ[MATERIALS_MONTAZH]',
              'FIELD_TYPE' => 'checkbox',
              'FIELD_WIDTH' => 50,
              'LIST_PARAMS' => [
                [
                  'CODE' => 'QUIZ[MATERIALS_MONTAZH]',
                  'NAME' => 'Да',
                ],
              ],
              'VALUE' => (!empty(current($data['MATERIALS_MONTAZH']))) ? current($data['MATERIALS_MONTAZH']) : '',
            ],
          ],
          'Профиль потребления клиентом ЭЭ' => [
            'SUMMARY_POWER' => [
              'NAME' => 'Общая мощность электрооборудования на объекте, кВт',
              'FIELD_NAME' => 'QUIZ[SUMMARY_POWER]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 20,
              'VALUE' => (!empty($data['SUMMARY_POWER'])) ? $data['SUMMARY_POWER'] : '',
            ],
            'PEAK_LOADS' => [
              'NAME' => 'Пиковые нагрузки во внутренней сети, кВт',
              'FIELD_NAME' => 'QUIZ[PEAK_LOADS]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 20,
              'VALUE' => (!empty($data['PEAK_LOADS'])) ? $data['PEAK_LOADS'] : '',
            ],
            'CONFIGURATION_NET' => [
              'NAME' => 'Конфигурация сети',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[CONFIGURATION_NET]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['CONFIGURATION_NET'])) ? $data['CONFIGURATION_NET'] : '',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Однофазная',
                  'NAME' => 'Однофазная',
                ],
                [
                  'CODE' => 'Трехфазная',
                  'NAME' => 'Трехфазная',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[CONFIGURATION_NET_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['CONFIGURATION_NET_DOP_FIELD'])) ? $data['CONFIGURATION_NET_DOP_FIELD'] : '',
                ]
              ],
            ],
            'ELECTRIC_METER_TYPE' => [
              'NAME' => 'Тип установленного электросчётчика',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[ELECTRIC_METER_TYPE]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['ELECTRIC_METER_TYPE'])) ? $data['ELECTRIC_METER_TYPE'] : '',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Однотарифный',
                  'NAME' => 'Однотарифный',
                ],
                [
                  'CODE' => 'Двухтарифный',
                  'NAME' => 'Двухтарифный',
                ],
                [
                  'CODE' => 'Трехтарифный',
                  'NAME' => 'Трехтарифный',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[ELECTRIC_METER_TYPE_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['ELECTRIC_METER_TYPE_DOP_FIELD'])) ? $data['ELECTRIC_METER_TYPE_DOP_FIELD'] : '',
                ]
              ],
            ],
            'ELECTRIC_TARIF' => [
              'NAME' => 'Существующий тариф, руб. за кВт*ч ',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[ELECTRIC_TARIF]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['ELECTRIC_TARIF'])) ? $data['ELECTRIC_TARIF'] : '',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => 'Однотарифный, Москва 5,73',
                  'NAME' => 'Однотарифный, Москва 5,73',
                ],
                [
                  'CODE' => 'Однотарифный, МО 4,01',
                  'NAME' => 'Однотарифный, МО 4,01',
                ],
                [
                  'CODE' => 'Двухтарифный, Москва — Т1 - 6,59; Т2 - 2,52',
                  'NAME' => 'Двухтарифный, Москва — Т1 - 6,59; Т2 - 2,52',
                ],
                [
                  'CODE' => 'Двухтарифный, МО — Т1 - 4,61; Т2 - 1,76',
                  'NAME' => 'Двухтарифный, МО — Т1 - 4,61; Т2 - 1,76',
                ],
                [
                  'CODE' => 'Трехтарифный, Москва — Т1 - 7,45; T2 - 5,73; T3 - 2,52',
                  'NAME' => 'Трехтарифный, Москва — Т1 - 7,45; T2 - 5,73; T3 - 2,52',
                ],
                [
                  'CODE' => 'Трехтарифный, МО — Т1 - 5,21; T2 - 4,01; T3 - 1,76',
                  'NAME' => 'Трехтарифный, МО — Т1 - 5,21; T2 - 4,01; T3 - 1,76',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[ELECTRIC_TARIF_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['ELECTRIC_TARIF_DOP_FIELD'])) ? $data['ELECTRIC_TARIF_DOP_FIELD'] : '',
                ]
              ],
            ],
            'AVG_CONSUMPTION_ELECTRIC' => [
              'NAME' => 'Среднесуточное потребление электроэнергии из сети, кВт',
              'FIELD_NAME' => 'QUIZ[AVG_CONSUMPTION_ELECTRIC]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 20,
              'VALUE' => (!empty($data['AVG_CONSUMPTION_ELECTRIC'])) ? $data['AVG_CONSUMPTION_ELECTRIC'] : '',
            ],
            'AVG_MONTHLY_ENERGY_BILL' => [
              'NAME' => 'Средний месячный счет за ЭЭ, в руб.',
              'FIELD_NAME' => 'QUIZ[AVG_MONTHLY_ENERGY_BILL]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 20,
              'VALUE' => (!empty($data['AVG_MONTHLY_ENERGY_BILL'])) ? $data['AVG_MONTHLY_ENERGY_BILL'] : '',
            ],
            'APPROXIMATE_RATIO_CONSUMPTION' => [
              'NAME' => 'Примерное соотношение такого потребления в кВт (темное/светлое время суток)?',
              'FIELD_TYPE' => 'dropdown',
              'FIELD_NAME' => 'QUIZ[APPROXIMATE_RATIO_CONSUMPTION]',
              'FIELD_WIDTH' => 50,
              'VALUE' => (!empty($data['APPROXIMATE_RATIO_CONSUMPTION'])) ? $data['APPROXIMATE_RATIO_CONSUMPTION'] : '',
              'LIST_PARAMS' => [
                [
                  'CODE' => '',
                  'NAME' => '(Не установлено)'
                ],
                [
                  'CODE' => '100% день',
                  'NAME' => '100% день',
                ],
                [
                  'CODE' => '100% ночь',
                  'NAME' => '100% ночь',
                ],
                [
                  'CODE' => '80% ночь/20% день',
                  'NAME' => '80% ночь/20% день',
                ],
                [
                  'CODE' => '80% день/20 % ночь',
                  'NAME' => '80% день/20 % ночь',
                ],
                [
                  'CODE' => 'other',
                  'NAME' => 'Другое (ввести вручную)',
                ],
              ],
              'DOP_FIELD' => [
                [
                  'NAME' => 'Другое',
                  'FIELD_TYPE' => 'text',
                  'FIELD_NAME' => 'QUIZ[APPROXIMATE_RATIO_CONSUMPTION_DOP_FIELD]',
                  'FIELD_WIDTH' => 20,
                  'VALUE' => (!empty($data['APPROXIMATE_RATIO_CONSUMPTION_DOP_FIELD'])) ? $data['APPROXIMATE_RATIO_CONSUMPTION_DOP_FIELD'] : '',
                ]
              ],
            ],
          ],
          'Ожидания клиента по бюджету и срокам' => [
            'APPROXIMATE_AVAILABLE_BUDGET' => [
              'NAME' => 'Примерный доступный бюджет на осуществление проекта, руб.',
              'FIELD_NAME' => 'QUIZ[APPROXIMATE_AVAILABLE_BUDGET]',
              'FIELD_TYPE' => 'text',
              'FIELD_WIDTH' => 20,
              'VALUE' => (!empty($data['APPROXIMATE_AVAILABLE_BUDGET'])) ? $data['APPROXIMATE_AVAILABLE_BUDGET'] : '',
            ],
            'PROJECT_COMPLETION_TIME' => [
              'NAME' => 'Ориентировочные сроки, к которым заказчик планирует завершить установку на своем объекте солнечной электростанции, дата',
              'FIELD_NAME' => 'QUIZ[PROJECT_COMPLETION_TIME]',
              'FIELD_TYPE' => 'date',
              'FIELD_WIDTH' => 20,
              'VALUE' => (!empty($data['PROJECT_COMPLETION_TIME'])) ? $data['PROJECT_COMPLETION_TIME'] : '',
            ],
          ]
        ];

        return $arFields;
    }

    /**
     * Добавление таба с формой анкеты
     * @param $form
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function WebFormOnAdminTabControlBegin(&$form)
    {
        if (
          $GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/form_result_edit.php'
          && $_GET['WEB_FORM_ID'] == self::WEB_FORM_ID
        ) {
            Loader::includeModule('form');

            $answer = \CFormResult::GetDataByID(
              $_GET['RESULT_ID'],
              [],
              $result,
              $arAnswer2
            );

            $arElement = [];
            if (!empty($arAnswer2) && !empty(current($arAnswer2['subservices'])['ANSWER_VALUE'])) {
                $elementId = current($arAnswer2['subservices'])['ANSWER_VALUE'];

                $dbElement = \CIBlockElement::GetList(
                  [],
                  [
                    'IBLOCK_ID' => Constants::IBLOCK_CATALOG_SERVICES,
                    'ID' => $elementId
                  ],
                  false,
                  false,
                  [
                    'NAME',
                    'PROPERTY_SERVICE_TYPE',
                    'PROPERTY_FEM',
                    'PROPERTY_AKB',
                    'IBLOCK_SECTION_ID'
                  ]
                );

                if ($arElement = $dbElement->Fetch()) {
                    if (!empty($arElement['IBLOCK_SECTION_ID'])) {
                        $arElement['SECTION_NAME'] = SectionTable::getList([
                          'select' => ['NAME'],
                          'filter' => [
                            'IBLOCK_ID' => Constants::IBLOCK_CATALOG_SERVICES,
                            'ID' => $arElement['IBLOCK_SECTION_ID']
                          ]
                        ])->fetch()['NAME'];
                    }
                    if (!empty($arElement['PROPERTY_SERVICE_TYPE_VALUE'])) {
                        $dbPropertyType = \CIBlockElement::GetList(
                          [],
                          [
                            'IBLOCK_ID' => Constants::IBLOCK_SERVICES_TYPES,
                            'ID' => $arElement['PROPERTY_SERVICE_TYPE_VALUE'],
                          ],
                          false,
                          false,
                          [
                            'NAME',
                            'CODE'
                          ]
                        );

                        if ($arPropertyType = $dbPropertyType->Fetch()) {
                            $arElement['SERVICE_TYPE'] = $arPropertyType;
                        }
                    }
                }
            }

//            $arFields = self::getFormData($arElement);

            $content = self::getFormQuestionListHtml($arFields);

//            $form->tabs[] = array(
//                "DIV" => "form_quiz",
//                "TAB" => "Опросный лист СЭС",
//                "ICON" => "main_user_edit",
//                "TITLE" => "Опросный лист СЭС",
//                "CONTENT" => $content
//            );

        }
    }

    /**
     * Получение верстки элементов формы
     * @param array $arFields
     * @return string
     */
    private static function getFormQuestionListHtml($arFields = [])
    {
        ob_start();
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script type="text/javascript">

            function printImage(imageLink) {
                var win = window.open(imageLink);
                // win.document.write('<img src="' + imageLink  + '">');
                win.print();
                setTimeout(function () {
                    win.close();
                }, 100);
            }

            // document.addEventListener("DOMContentLoaded", function () {
            //     const meterInstallation = document.querySelector('select[name="form_dropdown_QUIZ[BIDIRECTIONAL_COUNTER]"]');
            //     meterInstallation.addEventListener('change', function () {
            //         const text = this.value;
            //         if (text === 'other') {
            //             document.querySelector('.installing-counter').style.display = "inline-block";
            //         } else {
            //             document.querySelector('.installing-counter').style.display = "none";
            //         }
            //     });
            // });

            $(document).ready(function () {
                var currentType = $('input[name="form_radio_QUIZ[TYPE_CEC]"]:checked').val();
                $('div.property_descr > span').css('display', 'none');
                $('div.property_descr > span[data-rel="' + currentType + '"]').css('display', 'block');

                $('select').on('change', function () {
                    if ($(this).val() === 'other') {
                        $(this).siblings('.installing-counter').css('display', 'inline-block');
                    } else {
                        $(this).siblings('.installing-counter').css('display', 'none');
                    }
                });

                $('input[name="form_radio_QUIZ[TYPE_CEC]"]').on('change', function () {
                    var type = $(this).val();

                    $('div.property_descr > span').css('display', 'none');
                    $('div.property_descr > span[data-rel="' + type + '"]').css('display', 'block');

                    var selectList = $('select#COMPLECT_NAME').html('');
                    $(selectList).siblings('.installing-counter').css('display', 'none');
                    $.each(jsParams[type], function (key, val) {
                        if (key === 0) {
                            $('input[name="form_text_QUIZ[FEM_MODULES_POWER]"]').val(val.FEM);
                            $('input[name="form_text_QUIZ[BATTERY_CAPACITY]"]').val(val.AKB);
                        }

                        $(selectList).append('<option value="' + val.CODE + '" data-fem="' + val.FEM + '" data-akb="' + val.AKB + '" data-type="' + val.TYPE_NAME + '">' + val.NAME + '</option>');
                    });
                    $(selectList).append('<option value="other">Другое (ввести вручную)</option>');
                });

                $('select#COMPLECT_NAME').on('change', function () {
                    var option = $(this).find(':selected');
                    var fem = option.attr('data-fem');
                    var akb = option.attr('data-akb');

                    $('input[name="form_text_QUIZ[FEM_MODULES_POWER]"]').val(fem);
                    $('input[name="form_text_QUIZ[BATTERY_CAPACITY]"]').val(akb);
                })
            })
        </script>

        <?php
        foreach ($arFields as $key => $section) { ?>
            <tr class="heading">
                <td colspan="2"><?= $key ?></td>
            </tr>
            <?php
            foreach ($section as $propertyKey => $property) { ?>
                <tr>
                    <?php
                    if ($property['FIELD_TYPE'] != 'hidden') { ?>
                        <td><?= $property['NAME'] ?></td>
                    <?php
                    } ?>
                    <td <?php
                        if (!empty($property['DESCRIPTION'])) { ?>style="display: flex;    align-items: center;"<?php
                    } ?>>
                        <div <?php
                             if (!empty($property['DESCRIPTION'])) { ?>style="width: 160px;"<?php
                        } ?>>
                            <?php
                            switch ($property["FIELD_TYPE"]) {
                                case "hidden":
                                    $input = \CForm::GetHiddenField(
                                      $property['FIELD_NAME'],
                                      $property['VALUE'],
                                      $property['FIELD_PARAM']
                                    );

                                    echo $input;
                                    break;
                                case "radio":
                                    foreach ($property['LIST_PARAMS'] as $PARAM) {
                                        $input = \CForm::GetRadioField(
                                          $property['FIELD_NAME'],
                                          $PARAM["NAME"],
                                          $property['VALUE'],
                                          $PARAM["FIELD_PARAM"]
                                        );

                                        echo $input;
                                        echo "<label for=\"" . $PARAM['CODE'] . "\">" . htmlspecialcharsbx(
                                            $PARAM["NAME"]
                                          ) . "</label><br />";
                                    }
                                    break;
                                case "checkbox":
                                    foreach ($property['LIST_PARAMS'] as $PARAM) {
                                        $input = \CForm::GetCheckBoxField(
                                          $PARAM['CODE'],
                                          $PARAM['NAME'],
                                          $property['VALUE'],
                                          $PARAM["FIELD_PARAM"]
                                        );

                                        echo $input . "<label for=\"" . $property['FIELD_NAME'] . "\">" . htmlspecialcharsbx(
                                            $PARAM["NAME"]
                                          ) . "</label><br />";
                                    }
                                    break;
                                case "dropdown":
                                    $prop = [];
                                    foreach ($property['LIST_PARAMS'] as $PARAM) {
                                        $prop['reference'][] = $PARAM['NAME'];
                                        $prop['reference_id'][] = $PARAM['CODE'];
                                        $prop['param'][] = $PARAM['PARAM'];
                                    }
                                    if ($property['FIELD_NAME'] == 'QUIZ[COMPLECT_NAME]') {
                                        ?>
                                        <script>
                                            var jsParams = <?= \CUtil::PhpToJSObject($property['JS_PARAMS']) ?>
                                        </script>
                                        <select class="inputselect" name="form_dropdown_<?= $property['FIELD_NAME'] ?>"
                                                id="COMPLECT_NAME" style="width: 61.5%">
                                            <?php
                                            foreach ($property['LIST_PARAMS'] as $prop) { ?>
                                                <option value="<?= $prop['CODE'] ?>" data-fem="<?= $prop['FEM'] ?>"
                                                        <?php
                                                        if ($prop['CODE'] == $property['VALUE']) { ?>selected<?php
                                                } ?> data-akb="<?= $prop['AKB'] ?>"
                                                        data-type="<?= $prop['TYPE_NAME'] ?>"><?= $prop['NAME'] ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    <?php
                                    } else {
                                        echo \CForm::GetDropDownField(
                                          $property['FIELD_NAME'],
                                          $prop,
                                          $property['VALUE'],
                                          $property["FIELD_PARAM"]
                                        );
                                    }

                                    if (!empty($property['DOP_FIELD'])) {
                                        foreach ($property['DOP_FIELD'] as $item) {
                                            if ($property['VALUE'] == 'other') {
                                                echo '<div class="installing-counter" style="display: inline-block;">';
                                            } else {
                                                echo '<div class="installing-counter" style="display: none;">';
                                            }
                                            echo '<span id="property" style="margin-left: 30px;">' . $item['NAME'] . ' ';
                                            echo \CForm::GetTextField(
                                              $item["FIELD_NAME"],
                                              $item['VALUE'],
                                              $item["FIELD_WIDTH"],
                                              $item["FIELD_PARAM"]
                                            );
                                            echo '</span></div><br/>';
                                        }
                                    }

                                    break;
                                case "text":
                                    echo \CForm::GetTextField(
                                        $property["FIELD_NAME"],
                                        $property['VALUE'],
                                        $property["FIELD_WIDTH"],
                                        $property["FIELD_PARAM"]
                                      ) . '<br />';

                                    if ($property['FIELD_NAME'] == 'QUIZ[MAP_LINK]' && !empty($property['MAP_QR_CODE'])) {
                                        echo '<img class="printable" src="' . $property['MAP_QR_CODE'] . '"><br />';
                                        echo '<a href="javascript:void(0)" onclick=\'printImage("' . $property['MAP_QR_CODE'] . '")\'>Распечатать</a><br />';
                                    }

                                    break;
                                case "email":
                                    echo \CForm::GetEmailField(
                                        $property["FIELD_NAME"],
                                        $property['VALUE'],
                                        $property["FIELD_WIDTH"],
                                        $property["FIELD_PARAM"]
                                      ) . '<br />';
                                    break;
                                case "date":
                                    echo \CForm::GetDateField(
                                        $property["FIELD_NAME"],
                                        'form1',
                                        $property['VALUE'],
                                        $property["FIELD_WIDTH"],
                                        $property["FIELD_PARAM"]
                                      ) . '<br />';

                                    break;
                                case "image_link":
                                    echo '<img class="printable" src="' . $property['VALUE'] . '"><br />';
                                    echo '<a href="javascript:void(0)" onclick=\'printImage("' . $property['VALUE'] . '")\'>Распечатать</a><br />';

                                    break;
                                case "image":
                                    if (!empty($property["VALUE"])) {
                                        foreach ($property['VALUE'] as $img) {
                                            $file = \CFile::GetFileArray($img);

                                            if (\CFile::IsImage($file['FILE_NAME'])) {
                                                echo \CFile::ShowImage(
                                                  $img,
                                                  200,
                                                  200,
                                                  "border=0 class=\"printable\"",
                                                  "",
                                                  true
                                                );
                                                echo "<br />";
                                                echo '<input type="hidden" name="' . $property['FIELD_NAME'] . '[]" value="' . $img . '">';
                                                echo '<input type="checkbox" value="Y" name="form_image_' . $property['FIELD_NAME'] . '_del[' . $img . ']" id="form_image_' . $property['FIELD_NAME'] . '_del[' . $img . ']" /><label for="form_image_' . $property['FIELD_NAME'] . '_del[' . $img . ']">' . GetMessage(
                                                    'FORM_DELETE_FILE'
                                                  ) . '</label>';
                                                echo '<a style="margin-left: 20px;" href="javascript:void(0)" onclick=\'printImage("' . $file['SRC'] . '")\'>Распечатать</a><br /><br />';
                                            }
                                        }
                                    }

                                    global $APPLICATION;
                                    $APPLICATION->IncludeComponent(
                                      "bitrix:main.file.input",
                                      "drag_n_drop",
                                      array(
                                        "INPUT_NAME" => $property['FIELD_NAME'],
                                        "MULTIPLE" => "Y",
                                        "MODULE_ID" => "form",
                                        "MAX_FILE_SIZE" => "",
                                        "ALLOW_UPLOAD" => "I",
                                        "ALLOW_UPLOAD_EXT" => ""
                                      ),
                                      false
                                    );
                                    echo '<br />';

                                    break;
                            }
                            echo "</div>";
                            if (!empty($property['DESCRIPTION'])) {
                                echo "<div class='" . $property['FIELD_NAME'] . " property_descr' style='width: 450px;margin-left:60px;'>" . $property['DESCRIPTION'] . "</div>";
                            }
                            ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        <?php
        } ?>
        <?php
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Добавление кнопки на скачивание CSV с данными анкеты
     * @param $items
     */
    public function FormQuestionListCSVMenu(&$items)
    {
        if (
          $GLOBALS["APPLICATION"]->GetCurPage(true) == "/bitrix/admin/form_result_edit.php"
          && $_GET['WEB_FORM_ID'] == self::WEB_FORM_ID
          && current($items)['TEXT'] != 'Параметры формы'
          && !empty($_GET['RESULT_ID'])
        ) {
            $link = "/local/php_interface/include/csv_question.php?FORM_ID=" . self::WEB_FORM_ID . '&RESULT_ID=' . $_GET['RESULT_ID'];

            $items[] = array(
              "TEXT" => self::CSV_BUTTON_NAME,
              "ICON" => 'adm-menu-excel',
              "TITLE" => self::CSV_BUTTON_TITLE,
              "LINK" => $link
            );
        }
    }

    /**
     * Сохранение данных анкеты в отдельную таблицу
     * @param $WEB_FORM_ID
     * @param $RESULT_ID
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function WebFormAdminResult($WEB_FORM_ID, $RESULT_ID)
    {
        global $APPLICATION;

        $answer = \CFormResult::GetDataByID(
          $RESULT_ID,
          [],
          $result,
          $arAnswer2
        );


        if ($WEB_FORM_ID == self::WEB_FORM_ID && \CSite::InDir('/bitrix/')) {
            $arData = $_REQUEST['form_text_QUIZ'] + $_REQUEST['form_email_QUIZ'] + $_REQUEST['form_dropdown_QUIZ'] + $_REQUEST['form_date_QUIZ'] + $_REQUEST['form_hidden_QUIZ'];

            if (!empty($_REQUEST['form_checkbox_QUIZ'])) {
                $arData = $arData + $_REQUEST['form_checkbox_QUIZ'];
            }
            if (!empty($_REQUEST['form_radio_QUIZ'])) {
                $arData = $arData + $_REQUEST['form_radio_QUIZ'];
            }

            $arData['DATE_UPDATE'] = date('d.m.Y H:i:s');

            if (!empty($arData['COMPLECT_NAME'])) {
                $answer = FormResultAnswerTable::getList([
                  'select' => [
                    'ANSWER_ID',
                    'FIELD_ID'
                  ],
                  'filter' => [
                    'ANSWER_TEXT' => $arData['COMPLECT_NAME']
                  ]
                ]);

                if ($arAnswer = $answer->fetch()) {
                    $arAnswerList = FormAnswerTable::getList([
                      'select' => [
                        'MESSAGE',
                        'ID'
                      ],
                      'filter' => [
                        'FIELD_ID' => $arAnswer['FIELD_ID'],
                        'MESSAGE' => $arData['COMPLECT_NAME']
                      ]
                    ])->fetch();

                    $resultId = FormResultAnswerTable::getList([
                      'select' => [
                        'ID',
                        '*'
                      ],
                      'filter' => [
                        'FORM_ID' => $WEB_FORM_ID,
                        'RESULT_ID' => $RESULT_ID,
                        'FIELD_ID' => $arAnswer['FIELD_ID']
                      ],
                    ])->fetch();


                    FormResultAnswerTable::update($resultId['ID'], [
                      'ANSWER_ID' => $arAnswerList['ID'],
                      'ANSWER_TEXT' => $arAnswerList['MESSAGE']
                    ]);
                }
            }

            if (!empty($_REQUEST['form_image_QUIZ']['name']['BUILDING_DRAWING'])) {
                $file = [
                  'name' => $_FILES['form_image_QUIZ']['name']['BUILDING_DRAWING'],
                  'type' => $_FILES['form_image_QUIZ']['type']['BUILDING_DRAWING'],
                  'tmp_name' => $_FILES['form_image_QUIZ']['tmp_name']['BUILDING_DRAWING'],
                  'error' => $_FILES['form_image_QUIZ']['error']['BUILDING_DRAWING'],
                  'size' => $_FILES['form_image_QUIZ']['size']['BUILDING_DRAWING'],
                ];

                $fileId = \CFile::SaveFile($file, 'form');

                if ($fileId) {
                    $arData['BUILDING_DRAWING'] = $fileId;
                }
            } elseif (!empty($_REQUEST['BUILDING_DRAWING'])) {
                if (!empty($_REQUEST['form_image_BUILDING_DRAWING_del'])) {
                    foreach ($_REQUEST['form_image_BUILDING_DRAWING_del'] as $key => $img) {
                        unset($_REQUEST['BUILDING_DRAWING'][array_search($key, $_REQUEST['BUILDING_DRAWING'])]);
                        \CFile::Delete($key);
                    }
                }

                $arData['BUILDING_DRAWING'] = $_REQUEST['BUILDING_DRAWING'];
            }

            if (!empty($arData)) {
                $tableData = \Local\Entities\InterraoWebformQuizTable::getList([
                  'select' => [
                    'ID',
                    'RESULT_DATA'
                  ],
                  'filter' => [
                    'FORM_ID' => $WEB_FORM_ID,
                    'FORM_RESULT_ID' => $RESULT_ID
                  ],
                  'limit' => 1
                ]);

                if ($table = $tableData->fetch()) {
                    $data = unserialize($table['RESULT_DATA']);

                    if ($data['MAP_LINK'] != $_POST['form_text_QUIZ']['MAP_LINK']) {
                        $arData['QR_IMAGE_ID'] = self::saveQrCodeFile($_POST['form_text_QUIZ']['MAP_LINK']);
                    }

                    $res = \Local\Entities\InterraoWebformQuizTable::update($table['ID'], [
                      'RESULT_DATA' => serialize($arData)
                    ]);
                } else {
                    $arData['QR_IMAGE_ID'] = self::saveQrCodeFile($_POST['form_text_QUIZ']['MAP_LINK']);

                    $res = \Local\Entities\InterraoWebformQuizTable::add([
                      'FORM_ID' => $WEB_FORM_ID,
                      'FORM_RESULT_ID' => $RESULT_ID,
                      'RESULT_DATA' => serialize($arData)
                    ]);
                }
            }

            //добавление услуги
            self::addServiceData($WEB_FORM_ID, $RESULT_ID);
        }
    }

    /**
     * Сохранение файла с qr кодом в базу
     * @param string $link
     * @return int|string
     */
    protected static function saveQrCodeFile($link)
    {
        $qr = new QRGenerate();
        $qrLink = $qr->generateQR($link);

        $file = \CFile::MakeFileArray($qrLink);
        $file['name'] = $file['name'] . '.png';

        $fileId = \CFile::SaveFile($file, 'form');

        return $fileId;
    }

    /**
     * Добавление поля Выбранная услуга при сохранении формы
     * @param $WEB_FORM_ID
     * @param $RESULT_ID
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function addServiceData($WEB_FORM_ID, $RESULT_ID)
    {
        $answer = \CFormResult::GetDataByID(
          $RESULT_ID,
          [],
          $result,
          $arAnswer2
        );
        if (!empty(current($arAnswer2['subservices'])['ANSWER_VALUE'])) {
            $el = ElementTable::getList([
              'select' => [
                'IBLOCK_SECTION_ID',
                'SECTION_NAME' => 'SECTION.NAME'
              ],
              'filter' => [
                'IBLOCK_ID' => Constants::IBLOCK_CATALOG_SERVICES,
                'ID' => current($arAnswer2['subservices'])['ANSWER_VALUE'],
              ],
              'runtime' => [
                new ReferenceField(
                  'SECTION',
                  SectionTable::getEntity(),
                  [
                    '=ref.ID' => 'this.IBLOCK_SECTION_ID'
                  ]
                )
              ]
            ]);

            if ($element = $el->fetch()) {
                $fieldId = FormFieldTable::getList([
                  'select' => ['ID'],
                  'filter' => [
                    'FORM_ID' => $WEB_FORM_ID,
                    'SID' => 'service'
                  ]
                ])->fetch()['ID'];

                $resultId = FormResultAnswerTable::getList([
                  'select' => [
                    'ID',
                  ],
                  'filter' => [
                    'FORM_ID' => $WEB_FORM_ID,
                    'RESULT_ID' => $RESULT_ID,
                    'FIELD_ID' => $fieldId
                  ],
                ])->fetch();

                FormResultAnswerTable::update($resultId['ID'], [
                  'USER_TEXT' => ($element['SECTION_NAME']) ? $element['SECTION_NAME'] : 'Вызов электрика на дом',
                  'USER_TEXT_SEARCH' => ($element['SECTION_NAME']) ? mb_strtoupper(
                    $element['SECTION_NAME']
                  ) : 'ВЫЗОВ ЭЛЕКТРИКА НА ДОМ',
                ]);

                $subserviceResultId = FormResultAnswerTable::getList([
                  'select' => ['ID',],
                  'filter' => [
                    'FORM_ID' => $WEB_FORM_ID,
                    'RESULT_ID' => $RESULT_ID,
                    'FIELD_ID' => current($arAnswer2['subservices'])['FIELD_ID']
                  ]
                ])->fetch();

                $arSub = FormAnswerTable::getList([
                  'select' => ['MESSAGE'],
                  'filter' => [
                    'VALUE' => current($arAnswer2['subservices'])['ANSWER_VALUE']
                  ],
                  'limit' => 1
                ])->fetch();

                if ($arSub['MESSAGE'] != current($arAnswer2['subservices'])['ANSWER_TEXT']) {
                    $res = FormResultAnswerTable::update($subserviceResultId['ID'], [
                      'ANSWER_ID' => $_REQUEST['form_dropdown_subservices'],
                      'ANSWER_TEXT' => $arSub['MESSAGE'],
                      'ANSWER_TEXT_SEARCH' => $arSub['MESSAGE'],
                      'USER_TEXT_SEARCH' => $arSub['MESSAGE'],
                    ]);
                } else {
                    $res = FormResultAnswerTable::update($subserviceResultId['ID'], [
                      'ANSWER_ID' => $_REQUEST['form_dropdown_subservices'],
                      'USER_TEXT_SEARCH' => current($arAnswer2['subservices'])['ANSWER_TEXT'],
                    ]);
                }
            }
        }
    }

    /**
     * Скрытие поля услуги для не администраторов
     */
    public function EndBuffer()
    {
        if (
          $GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/form_result_edit.php'
        ) {
            global $USER;
            $content = ob_get_contents();
            ob_end_clean();
            Loader::includeModule('form');
            $answer = \CFormResult::GetDataByID(
              $_GET['RESULT_ID'],
              [],
              $result,
              $arAnswer2
            );
            $content = str_replace('Поля результата', 'Данные от клиента', $content);

            if (!$USER->isAdmin()) {
                if (!empty($arAnswer2)) {
                    foreach ($arAnswer2 as $key => $value) {
                        if ($key == 'comment' || $key == 'message') {
                            $content = str_replace(
                              'name="form_textarea_' . key($value) . '"',
                              'name="form_textarea_' . key($value) . '" disabled',
                              $content
                            );
                        }
                        if ($key === 'comment_status') {
                            $content = str_replace(
                              'Комментарий к статусу',
                              '',
                              $content
                            );
                            $content = preg_replace(
                              '#<textarea(.*)name="form_textarea_' . key($value) . '(.*)textarea>#',
                              '',
                              $content
                            );
                        }
                        $content = str_replace(
                          'name="form_text_' . key($value) . '"',
                          'name="form_text_' . key($value) . '" disabled',
                          $content
                        );
                    }
                    $content = str_replace(
                      '] Недозвон</label>',
                      '] Недозвон</label>
<tr>
<td style="width: 40%; vertical-align: top;" class="adm-detail-content-cell-l">
Комментарий к статусу		</td>
<td class="adm-detail-content-cell-r">
<textarea name="form_textarea_' . key(
                        $arAnswer2["comment_status"]
                      ) . '" cols="40" rows="5" class="inputtextarea">' . current(
                        $arAnswer2['comment_status']
                      )['USER_TEXT'] . '</textarea><br>		</td>
</tr>',
                      $content
                    );
                    $content = str_replace(
                      '] Нужен ответ пользователя</label>',
                      '] Нужен ответ пользователя</label>
<tr>
<td style="width: 40%; vertical-align: top;" class="adm-detail-content-cell-l">
Комментарий к статусу		</td>
<td class="adm-detail-content-cell-r">
<textarea name="form_textarea_' . key(
                        $arAnswer2["comment_status"]
                      ) . '" cols="40" rows="5" class="inputtextarea">' . current(
                        $arAnswer2['comment_status']
                      )['USER_TEXT'] . '</textarea><br>		</td>
</tr>',
                      $content
                    );
                } else {
                    $content = str_replace(
                      '<tr>
		<td valign="top">
ФИО оператора		</td><td>
<input type="text"  class="inputtext"  name="form_text_38" value="" size="0" /><br />		</td>
	</tr>',
                      '',
                      $content
                    );
                }
            }

            echo $content;
        }
    }
}