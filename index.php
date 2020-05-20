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

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Cdn\V20180606\CdnClient;
use TencentCloud\Cdn\V20180606\Models\PurgeUrlsCacheRequest;

try {

    $cred = new Credential("AKIDc1PhneIo7MFrQtq5PUcXN7ey4walmYCg ", "WsjSxHsHH5pEOQ9e074A5yg9duz0Nfjz");
    $httpProfile = new HttpProfile();
    $httpProfile->setEndpoint("cdn.tencentcloudapi.com");

    $clientProfile = new ClientProfile();
    $clientProfile->setHttpProfile($httpProfile);
    $client = new CdnClient($cred, "", $clientProfile);

    $req = new PurgeUrlsCacheRequest();

    $params =  '{"Urls":' . json_encode(array_merge($urlArr, $wwwurlArr)) . '}';

    $req->fromJsonString($params);


    $resp = $client->PurgeUrlsCache($req);

    print_r($resp->toJsonString());
} catch (TencentCloudSDKException $e) {
    echo $e;
}
