<?php
namespace Local\Entities;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class FieldTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> FORM_ID int mandatory
 * <li> TIMESTAMP_X datetime optional
 * <li> ACTIVE bool optional default 'Y'
 * <li> TITLE string optional
 * <li> TITLE_TYPE enum ('text', 'html') optional default 'text'
 * <li> SID string(50) optional
 * <li> C_SORT int optional default 100
 * <li> ADDITIONAL bool optional default 'N'
 * <li> REQUIRED bool optional default 'N'
 * <li> IN_FILTER bool optional default 'N'
 * <li> IN_RESULTS_TABLE bool optional default 'N'
 * <li> IN_EXCEL_TABLE bool optional default 'Y'
 * <li> FIELD_TYPE string(50) optional
 * <li> IMAGE_ID int optional
 * <li> COMMENTS string optional
 * <li> FILTER_TITLE string optional
 * <li> RESULTS_TABLE_TITLE string optional
 * </ul>
 *
 * @package Local\Entities
 **/

class FormFieldTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_form_field';
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
                'title' => Loc::getMessage('FIELD_ENTITY_ID_FIELD'),
            ),
            'FORM_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('FIELD_ENTITY_FORM_ID_FIELD'),
            ),
            'TIMESTAMP_X' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('FIELD_ENTITY_TIMESTAMP_X_FIELD'),
            ),
            'ACTIVE' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('FIELD_ENTITY_ACTIVE_FIELD'),
            ),
            'TITLE' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('FIELD_ENTITY_TITLE_FIELD'),
            ),
            'TITLE_TYPE' => array(
                'data_type' => 'enum',
                'values' => array('text', 'html'),
                'title' => Loc::getMessage('FIELD_ENTITY_TITLE_TYPE_FIELD'),
            ),
            'SID' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSid'),
                'title' => Loc::getMessage('FIELD_ENTITY_SID_FIELD'),
            ),
            'C_SORT' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('FIELD_ENTITY_C_SORT_FIELD'),
            ),
            'ADDITIONAL' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('FIELD_ENTITY_ADDITIONAL_FIELD'),
            ),
            'REQUIRED' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('FIELD_ENTITY_REQUIRED_FIELD'),
            ),
            'IN_FILTER' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('FIELD_ENTITY_IN_FILTER_FIELD'),
            ),
            'IN_RESULTS_TABLE' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('FIELD_ENTITY_IN_RESULTS_TABLE_FIELD'),
            ),
            'IN_EXCEL_TABLE' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('FIELD_ENTITY_IN_EXCEL_TABLE_FIELD'),
            ),
            'FIELD_TYPE' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateFieldType'),
                'title' => Loc::getMessage('FIELD_ENTITY_FIELD_TYPE_FIELD'),
            ),
            'IMAGE_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('FIELD_ENTITY_IMAGE_ID_FIELD'),
            ),
            'COMMENTS' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('FIELD_ENTITY_COMMENTS_FIELD'),
            ),
            'FILTER_TITLE' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('FIELD_ENTITY_FILTER_TITLE_FIELD'),
            ),
            'RESULTS_TABLE_TITLE' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('FIELD_ENTITY_RESULTS_TABLE_TITLE_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for SID field.
     *
     * @return array
     */
    public static function validateSid()
    {
        return array(
            new Main\Entity\Validator\Length(null, 50),
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
            new Main\Entity\Validator\Length(null, 50),
        );
    }
}