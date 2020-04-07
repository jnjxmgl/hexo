---
title: 在php使用curl进行get请求接口
date: 2019-12-02 13:14:49
tags: php
categories: linux
keywords:
- php
- php使用curl
- curl进行get
- 在php使用curl进行get请求接口

---
```php
    /**
     * get请求
     *
     * @param string $url 请求地址
     * @param array $data get参数数据
     * @return array 返回接口json数据
     */
    function geturl($url, $data = array())
    {


        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $param = '';

        if ($data) {
            foreach ($data as $k => $v) {

                $param .= '&' . $k . '=' . $v;
            }
            $param = substr_replace($param, '?', 0, 1);
        }

        $url .= $param;

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);      //不输出请求头信息
        $data = curl_exec($curl);


        //执行命令
        curl_close($curl);                            //关闭URL请求
        return json_decode($data, true);              //显示获得的数据

    }

```
