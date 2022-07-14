<?
use Bitrix\Main\Application;


$localPath = getLocalPath("");
$bxRoot = strlen($localPath) > 0 ? rtrim($localPath, "/\\") : BX_ROOT;

require_once($_SERVER['DOCUMENT_ROOT'] .$bxRoot. '/modules/realweb.baseinclude/prolog.php'); // пролог модуля

Class realweb_baseinclude extends CModule
{
    // Обязательные свойства.
    /**
     * Имя партнера - автора модуля.
     * @var string
     */
    var $PARTNER_NAME;

    /**
     * URL партнера - автора модуля.
     * @var string
     */
    var $PARTNER_URI;

    /**
     * Версия модуля.
     * @var string
     */
    var $MODULE_VERSION;

    /**
     * Дата и время создания модуля.
     * @var string
     */
    var $MODULE_VERSION_DATE;

    /**
     * Имя модуля.
     * @var string
     */
    var $MODULE_NAME;

    /**
     * Описание модуля.
     * @var string
     */
    var $MODULE_DESCRIPTION;

    /**
     * ID модуля.
     * @var string
     */
    var $MODULE_ID = 'realweb.baseinclude';

    private $bxRoot = BX_ROOT;

    /**
     * Конструктор класса. Задаёт начальные значения свойствам.
     */
    function __construct()
    {
        $this->PARTNER_NAME = 'Realweb';
        $this->PARTNER_URI = 'http://www.realweb.ru';
        $this->errors = array();
        $this->result = array();
        $arModuleVersion = array();

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path . '/version.php');

        $localPath = getLocalPath("");
        $this->bxRoot = strlen($localPath) > 0 ? rtrim($localPath, "/\\") : BX_ROOT;

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('REALWEB.BASEINCLUDE.NAME');
        if (CModule::IncludeModule($this->MODULE_ID)) {
            $this->MODULE_DESCRIPTION = GetMessage('REALWEB.BASEINCLUDE.INSTALL_DESCRIPTION');
        } else {
            $this->MODULE_DESCRIPTION = GetMessage('REALWEB.BASEINCLUDE.PREINSTALL_DESCRIPTION');
        }
    }

    function DoInstall()
    {
        $connection = Application::getInstance()->getConnection();


        $this->InstallFiles();
        $included = false;
        if (!CModule::IncludeModule($this->MODULE_ID)) {
            RegisterModule($this->MODULE_ID);
            $included = CModule::IncludeModule($this->MODULE_ID);
        }
        if($included){
            $tableName = \Realweb\BaseInclude\BaseIncludeTable::getTableName();
            if(!$connection->isTableExists($tableName)){
                $connection->createTable($tableName, \Realweb\BaseInclude\BaseIncludeTable::getMap(), array("ID"), array("ID"));
            }
        }
    }

    function InstallFiles()
    {
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].$this->bxRoot.'/modules/'. $this->MODULE_ID .'/admin'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.' || $item == 'menu.php')
                        continue;

                    file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/admin/'. $item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."'.$this->bxRoot.'/modules/'. $this->MODULE_ID .'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }

        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] .$this->bxRoot. "/modules/". $this->MODULE_ID ."/install/components/",
            $_SERVER["DOCUMENT_ROOT"] .$this->bxRoot.  "/components/", true, true);

        return true;
    }

    function DoUninstall()
    {
        global $DB, $APPLICATION;

        if (CModule::IncludeModule($this->MODULE_ID)) {
            UnRegisterModule($this->MODULE_ID);
        }
        $this->UnInstallFiles();
    }

    function UnInstallFiles()
    {
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].$this->bxRoot.'/modules/'. $this->MODULE_ID .'/admin'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.')
                        continue;
                    unlink($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/admin/'. $item);
                }
                closedir($dir);
            }
        }

        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] .$this->bxRoot.  "/modules/". $this->MODULE_ID ."/install/components/",
            $_SERVER["DOCUMENT_ROOT"] .$this->bxRoot.  "/components/");
        return true;
    }

}

?>
