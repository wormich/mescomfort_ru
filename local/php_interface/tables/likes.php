<?php
namespace Bitrix\Likes;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class LikesTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_ARTICLE_ID string optional
 * <li> UF_ARTICLE_LIKES string optional
 * </ul>
 *
 * @package Bitrix\
 **/

class LikesTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'likes';
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
				'title' => Loc::getMessage('_ENTITY_ID_FIELD'),
			),
			'UF_ARTICLE_ID' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('_ENTITY_UF_ARTICLE_ID_FIELD'),
			),
			'UF_ARTICLE_LIKES' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('_ENTITY_UF_ARTICLE_LIKES_FIELD'),
			),
		);
	}
}