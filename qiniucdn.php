<?php
/*
*  new getFile($_dir[,$_emptyDir,$_fileType]);
* @parma $_dir 是目录名称
* @parma $_emptyDir 是否获取空文件夹，选填，默认不获取，true则获取空文件夹
* @parma $_fileType 获取文件名称的类型，选填，默认获取只文件名称，true则获取带有路径的文件名称
**/


class getFiles
{
    private $_dir;
    private $_emptyDir;
    private $_fileType;
    public $_files;

    public function __construct($_dir, $_emptyDir = false, $_fileType = false)
    {
        $this->_dir = $_dir;
        $this->_emptyDir = $_emptyDir;
        $this->_fileType = $_fileType;
        if ($this->_emptyDir) {
            $this->getFileEmpty($this->_dir, $this->_files);
        } else {
            $this->getFile($this->_dir, $this->_files);
        }
    }
    //读取文件夹所有文件不包括空文件夹
    private function getFile($_dir, &$_arr)
    {
        if ($_dirs = opendir($_dir)) {
            while (($_file = readdir($_dirs)) != false) {
                if ($_file == '.' || $_file == '..') continue;
                $_files = $_dir . '/' . $_file;
                if (is_dir($_files)) {
                    $this->getFile($_files, $_arr);
                } else {
                    if ($this->_fileType) {
                        $_arr[] = $_files;
                    } else {
                        $_arr[] = $_file;
                    }
                }
            }
        }
        closedir($_dirs);
    }
    //读取文件夹所有文件包括空文件夹
    private function getFileEmpty($_dir, &$_arr)
    {
        if ($_dirs = opendir($_dir)) {
            while (($_file = readdir($_dirs)) != false) {
                if ($_file == '.' || $_file == '..') continue;
                $_files = $_dir . '/' . $_file;
                if (is_dir($_files) && $this->isEmpty($_files)) {
                    $this->getFileEmpty($_files, $_arr);
                } else {
                    if ($this->_fileType) {
                        $_arr[] = $_files;
                    } else {
                        $_arr[] = $_file;
                    }
                }
            }
        }
        closedir($_dirs);
    }
    //判断文件夹是否为空
    private function isEmpty($_dir)
    {
        if ($_dirs = opendir($_dir)) {
            while (($_file = readdir($_dirs)) != false) {
                if ($_file != '.' && $_file != '..') {
                    closedir($_dirs);
                    return true;
                    break;
                }
            }
            closedir($_dirs);
            return false;
        }
    }
}


$res = new getFiles('D:\hexo\public', true, true);

$wwwurlArr = $res->_files;
$urlArr = $res->_files;
foreach ($wwwurlArr as $key => $value) {
    $wwwurlArr[$key] = str_replace('D:\hexo\public', 'https://www.imgl.net', $value);
}
foreach ($urlArr as $key => $value) {
    $urlArr[$key] = str_replace('D:\hexo\public', 'https://imgl.net', $value);
}

require_once "./vendor/autoload.php";

use Qiniu\Auth;
use Qiniu\Cdn\CdnManager;

$auth = new Auth('nCFWiU6P3R2E9FlFYSL8UCbUdk5ZrXnDV4UtWW0P', 'tEqtjVpHtyh-WGY_eB9F9prv0W3DefkbbQlXxfvM');

$CdnManager = new CdnManager($auth);

$sumArr = array_merge($urlArr, $wwwurlArr);

foreach ($sumArr as $key => $value) {

    $res = $CdnManager->refreshUrls([$value]);

    if ($res[0]['code'] != 200) {
        echo "请求出错\n";
        die;
    }
    echo "等待1s\n";
    sleep(1);
}
