<?php
namespace Local\Entities;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class ResultAnswerTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> RESULT_ID int mandatory
 * <li> FORM_ID int mandatory
 * <li> FIELD_ID int mandatory
 * <li> ANSWER_ID int optional
 * <li> ANSWER_TEXT string optional
 * <li> ANSWER_TEXT_SEARCH string optional
 * <li> ANSWER_VALUE string(255) optional
 * <li> ANSWER_VALUE_SEARCH string optional
 * <li> USER_TEXT string optional
 * <li> USER_TEXT_SEARCH string optional
 * <li> USER_DATE datetime optional
 * <li> USER_FILE_ID int optional
 * <li> USER_FILE_NAME string(255) optional
 * <li> USER_FILE_IS_IMAGE string(1) optional
 * <li> USER_FILE_HASH string(255) optional
 * <li> USER_FILE_SUFFIX string(255) optional
 * <li> USER_FILE_SIZE int optional
 * </ul>
 *
 * @package Bitrix\Form
 **/

class FormResultAnswerTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_form_result_answer';
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
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_ID_FIELD'),
            ),
            'RESULT_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_RESULT_ID_FIELD'),
            ),
            'FORM_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_FORM_ID_FIELD'),
            ),
            'FIELD_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_FIELD_ID_FIELD'),
            ),
            'ANSWER_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_ANSWER_ID_FIELD'),
            ),
            'ANSWER_TEXT' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_ANSWER_TEXT_FIELD'),
            ),
            'ANSWER_TEXT_SEARCH' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_ANSWER_TEXT_SEARCH_FIELD'),
            ),
            'ANSWER_VALUE' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateAnswerValue'),
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_ANSWER_VALUE_FIELD'),
            ),
            'ANSWER_VALUE_SEARCH' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_ANSWER_VALUE_SEARCH_FIELD'),
            ),
            'USER_TEXT' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_TEXT_FIELD'),
            ),
            'USER_TEXT_SEARCH' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_TEXT_SEARCH_FIELD'),
            ),
            'USER_DATE' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_DATE_FIELD'),
            ),
            'USER_FILE_ID' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_FILE_ID_FIELD'),
            ),
            'USER_FILE_NAME' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateUserFileName'),
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_FILE_NAME_FIELD'),
            ),
            'USER_FILE_IS_IMAGE' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateUserFileIsImage'),
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_FILE_IS_IMAGE_FIELD'),
            ),
            'USER_FILE_HASH' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateUserFileHash'),
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_FILE_HASH_FIELD'),
            ),
            'USER_FILE_SUFFIX' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateUserFileSuffix'),
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_FILE_SUFFIX_FIELD'),
            ),
            'USER_FILE_SIZE' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('RESULT_ANSWER_ENTITY_USER_FILE_SIZE_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for ANSWER_VALUE field.
     *
     * @return array
     */
    public static function validateAnswerValue()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for USER_FILE_NAME field.
     *
     * @return array
     */
    public static function validateUserFileName()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for USER_FILE_IS_IMAGE field.
     *
     * @return array
     */
    public static function validateUserFileIsImage()
    {
        return array(
            new Main\Entity\Validator\Length(null, 1),
        );
    }
    /**
     * Returns validators for USER_FILE_HASH field.
     *
     * @return array
     */
    public static function validateUserFileHash()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for USER_FILE_SUFFIX field.
     *
     * @return array
     */
    public static function validateUserFileSuffix()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
}
