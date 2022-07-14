<?php
namespace Realweb\BaseInclude;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Class BaseIncludeTable extends Main\Entity\DataManager
{
    const TYPE_TEXT = 'text';
    const TYPE_HTML = 'html';

    public static function getTableName()
    {
        return 'realweb_base_include';
    }

    public static function getByCode($CODE)
    {
        return static::getList(array(
            "filter" => array(
                "=CODE" => $CODE
            )
        ));
    }

    public static function getAll()
    {
        return static::getList();
    }


    public static function addUpdateByCode($code, $text, $comment = " ")
    {

        $arText = self::getByCode($code)->fetch();
        $id = false;
        if (is_array($arText)) {
            $id = $arText["ID"];
        }
        $arText = [
            "CODE" => $code, "TEXT" => $text, "COMMENT" => $comment
        ];
        if ($id) {
            $res = self::update($id, $arText);
        } else {
            $res = self::add($arText);
        }
        if (!$res->isSuccess()) {
            throw new \Exception(join($res->getErrorMessages(), ", "));
        }

    }



    public static function getMap()
    {
        return array(
            'ID' => new Main\Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('REALWEB.BASEINCLUDE_ENTITY_ID_FIELD'),
            )),
            'CODE' => new Main\Entity\StringField('CODE', array(
                'required' => true,
                'title' => Loc::getMessage('REALWEB.BASEINCLUDE_ENTITY_CODE_FIELD'),
            )),
            'COMMENT' => new Main\Entity\StringField('COMMENT', array(
                'title' => Loc::getMessage('REALWEB.BASEINCLUDE_ENTITY_PREVIEW_COMMENT_FIELD'),
            )),
            'TEXT' => new Main\Entity\TextField('TEXT', array(
                'title' => Loc::getMessage('REALWEB.BASEINCLUDE_ENTITY_PREVIEW_TEXT_FIELD'),
            )),
            'TEXT_TYPE' => new Main\Entity\EnumField('TEXT_TYPE', array(
                'values' => array(self::TYPE_TEXT, self::TYPE_HTML),
                'default_value' => self::TYPE_HTML,
                'title' => Loc::getMessage('REALWEB.BASEINCLUDE_ENTITY_PREVIEW_TEXT_TYPE_FIELD'),
            )),
        );
    }


}
