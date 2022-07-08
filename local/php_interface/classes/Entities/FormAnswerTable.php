<?php
namespace Local\Entities;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class AnswerTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> FIELD_ID int mandatory
 * <li> TIMESTAMP_X datetime optional
 * <li> MESSAGE string optional
 * <li> C_SORT int optional default 100
 * <li> ACTIVE bool optional default 'Y'
 * <li> VALUE string(255) optional
 * <li> FIELD_TYPE string(255) mandatory default 'text'
 * <li> FIELD_WIDTH int optional
 * <li> FIELD_HEIGHT int optional
 * <li> FIELD_PARAM string optional
 * </ul>
 *
 * @package Local\Entities
 **/

class FormAnswerTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_form_answer';
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
                'title' => Loc::getMessage('ANSWER_ENTITY_ID_FIELD'),
            ),
            'FIELD_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('ANSWER_ENTITY_FIELD_ID_FIELD'),
            ),
            'TIMESTAMP_X' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('ANSWER_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'MESSAGE' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('ANSWER_ENTITY_MESSAGE_FIELD'),
            ),
            'C_SORT' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('ANSWER_ENTITY_C_SORT_FIELD'),
            ),
            'ACTIVE' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('ANSWER_ENTITY_ACTIVE_FIELD'),
            ),
            'VALUE' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateValue'),
                'title' => Loc::getMessage('ANSWER_ENTITY_VALUE_FIELD'),
            ),
            'FIELD_TYPE' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateFieldType'),
                'title' => Loc::getMessage('ANSWER_ENTITY_FIELD_TYPE_FIELD'),
            ),
            'FIELD_WIDTH' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('ANSWER_ENTITY_FIELD_WIDTH_FIELD'),
            ),
            'FIELD_HEIGHT' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('ANSWER_ENTITY_FIELD_HEIGHT_FIELD'),
            ),
            'FIELD_PARAM' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('ANSWER_ENTITY_FIELD_PARAM_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for VALUE field.
     *
     * @return array
     */
    public static function validateValue()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for FIELD_TYPE field.
     *
     * @return array
     */
    public static function validateFieldType()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
}