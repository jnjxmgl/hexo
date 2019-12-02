---
title: 在php使用curl进行post请求接口
date: 2019-12-02 13:12:27
tags:
---

```php
    /**
     * post请求
     *
     * @param string $url 请求接口地址
     * @param array $data post参数数据
     * @return array 返回接口json数据
     */
    function posturl($url, $data = array())
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url

        curl_setopt($curl, CURLOPT_URL, $url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        //设置post方式提交 
        if (!is_array($data)) {

            $headers = array("Content-Type: application/json");
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);//不输出头信息
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //执行命令
        $res = curl_exec($curl);


        //关闭URL请求
        curl_close($curl);
        //显示获得的数据

        return  json_decode($res, true);
    }
```