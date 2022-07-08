<?php
namespace Local\Entities;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class QuizTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> FORM_ID int mandatory
 * <li> FORM_RESULT_ID int mandatory
 * <li> RESULT_DATA string optional
 * </ul>
 *
 * @package Local\Entities
 **/

class InterraoWebformQuizTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'interrao_webform_quiz';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('QUIZ_ENTITY_ID_FIELD'),
            ),
            'FORM_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('QUIZ_ENTITY_FORM_ID_FIELD'),
            ),
            'FORM_RESULT_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('QUIZ_ENTITY_FORM_RESULT_ID_FIELD'),
            ),
            'RESULT_DATA' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('QUIZ_ENTITY_RESULT_DATA_FIELD'),
            ),
        );
    }
}