---
title: 阿里云直播推流播流url鉴权地址生成
date: 2020-05-04 16:43:32
tags:
- 直播
- 阿里云
- php
categories:
- 直播
- php
keywords:
- 阿里云直播推流url生成
- 直播推流url生成
- 阿里云播流url鉴权地址生成
- url鉴权地址
- 直播url鉴权地址
- 阿里云直播推流播流
- 播流url地址生成
- php 阿里 推流地址生成
- php 阿里 播流地址生成
---

>创建用户唯一标识

```php
if (!function_exists('create_iden')) {
    /**
     *创建用户唯一标识 
     */
    function create_iden($data = '')
    {
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', uniqid("", true) . md5($data)));
        return $hash;
    }
}
```
>获取推流地址
```php

/**
     * 获取推流地址
     *
     */
    public function get_push_live_url()
    {



        try {

            #配置
            $video_live['aliyun']['appname'] ='zhifubao';//应用名称-支付宝
            $video_live['aliyun']['push_validity'] =30;//推流鉴权有效时间(分钟)-30分钟有效
            $video_live['aliyun']['push_key'] ='QNBkjiRogF';//推流鉴权key
            $video_live['aliyun']['push_domain'] ='tuiliu.zhifubao.com';//推流地址

            //$user_live是直播间信息集合,这里只说明直播间号
            $user_live['live_num'] =100000001 ;//直播间号,一般从数据库中读取

            if ($user_live['status'] != 1) {
                $this->error('直播申请未通过,不能进行直播');
            }
            if ($user_live['flag'] == 'Y') {
                $this->error('您已被管理员限制直播权限,不能进行直播');
            }
            //开始生成地址
            $uri =   "/" . $video_live['aliyun']['appname'] . "/"  . $user_live['live_num'];
            $timestamp = time() + 60 * $video_live['aliyun']['push_validity'];
            $rand = create_iden();//创建用户唯一标识,见上面函数
            $uid = 0;
            $hashValue = md5($uri . "-" . $timestamp . "-" . $rand . "-" . $uid . "-" . $video_live['aliyun']['push_key']);
            $server_url = "rtmp://" . $video_live['aliyun']['push_domain'] . "/" .  $video_live['aliyun']['appname'] . "/";
            $stream_sign =   $user_live['live_num'] . "?auth_key=" . $timestamp . "-" . $rand . "-" . $uid . "-" . $hashValue;
            $push_url = $server_url . $stream_sign;
            /* 输出结果 */
            $data = array(
                'server_url'  => $server_url, //推流服务器
                'stream_sign' => $stream_sign, //推流串流密钥
                'push_url'      => $push_url,//完整的推流地址
                'live_num'      => $user_live['live_num'],//直播间号
            );
            $this->success('请求成功', $data);
        } catch (Exception $th) {
            save_live_logs('get_push_live_url接口异常:' . $th->getMessage());//记录错误
            $this->error($th->getMessage());
        }
    }
```
>根据房间号获取播流地址
```php


/**
     * 获取播流地址
     *@param string $live_num 直播房间号
     */
    public function get_play_live_url()
    {


        try {
            $live_num = $this->request->get('live_num');//前台传过来的直播间号
            if (!$live_num) {

                $this->error('房间号不存在');
            }


            #配置
            $video_live['aliyun']['appname'] = 'zhifubao';
            $video_live['aliyun']['play_validity'] = 30;
            $video_live['aliyun']['play_key'] = 'jTOrVA3u8N';
            $video_live['aliyun']['play_domain'] = 'boliu.zhifubao.com';



            $user_live =   //获取直播间信息集合,包括直播间号
            /* 推流地址 */
            if (empty($user_live)) {
                $this->error('房间号不存在');
            }
            $timestamp = time() + 60 * $video_live['aliyun']['play_validity'];
            $rand = create_iden();
            $uid = 0;
            /* m3u8格式播放地址,延迟最高 */
            $uri =  "/" . $video_live['aliyun']['appname'] . "/" .  $user_live['live_num'] . ".m3u8";
            $hashValue = md5($uri . "-" . $timestamp . "-" . $rand . "-" . $uid . "-" . $video_live['aliyun']['play_key']);
            $play_url1 = 'http://' . $video_live['aliyun']['play_domain'] . $uri . "?auth_key=" . $timestamp . "-" . $rand . "-" . $uid . "-" . $hashValue;
      /* flv播放地址 */
            $uri =  "/" . $video_live['aliyun']['appname'] . "/" .  $user_live['live_num'] . ".flv";
            $hashValue = md5($uri . "-" . $timestamp . "-" . $rand . "-" . $uid . "-" . $video_live['aliyun']['play_key']);
            $play_url2 = 'http://' . $video_live['aliyun']['play_domain'] . $uri . "?auth_key=" . $timestamp . "-" . $rand . "-" . $uid . "-" . $hashValue;
      /* rtmp播放地址 ,应该是延迟最低的*/
            $uri =   "/" . $video_live['aliyun']['appname'] . "/"  .  $user_live['live_num'];
            $hashValue = md5($uri . "-" . $timestamp . "-" . $rand . "-" . $uid . "-" . $video_live['aliyun']['play_key']);
            $play_url3 = 'rtmp://' . $video_live['aliyun']['play_domain'] . $uri . "?auth_key=" . $timestamp . "-" . $rand . "-" . $uid . "-" . $hashValue;

            Db::name('user_live_list')->where('live_num', $live_num)->setInc('online_num', 1);

            /* 输出结果 */
            $user_live['play_url1'] = $play_url1;
            $user_live['play_url2'] = $play_url2;
            $user_live['play_url3'] = $play_url3;
          

            $user_live['goods_info'] = $goods_info;//直播间展示的商品
            $this->success('请求成功', $user_live);
        } catch (Exception $th) {
            save_live_logs('get_play_live_url接口异常:' . $th->getMessage());
            $this->error($th->getMessage());
        }
    }
```

>推流后回调

```php

/**
     * 推流回调     
     * 
     *@param string $time unix 时间戳
     *@param string $usrargs 用户推流的参数
     *@param string $action publish 表示推流，publish_done 表示断流
     *@param string $app 默认为自定义的推流域名，如果未绑定推流域名即为播放域名
     *@param string $appname 应用名称
     *@param string $id 每页显示数量,默认10
     *@param string $node CDN 接受流的节点或者机器名
     *@param string $ip 推流的客户端IP,注意接收时参数名是小写
     */
    public function push_notify()
    {
        // https: //live.aliyunlive.com/pub? action=publish & app=xc.cdnpe.com & appname=test01 & id=test01 & ip=42.120.74.183 & node=cdnvideocenter010207116011.cm3

        try {

            $data = input();



            if (empty($data['id'])) {

                $this->error('Oh,NO!');
            }

            if ($data['action'] == 'publish') {//开启直播
                Db::name('user_live_list')->where('live_num', $data['id'])->setField('is_open', 'Y');
            }
            if ($data['action'] == 'publish_done') {//主动关闭或者断开
                Db::name('user_live_list')->where('live_num', $data['id'])->setField('is_open', 'N');
               
            }

            Db::name('push_notify')->insert($data);//记录动态
            $this->success('Yes,OK!');
        } catch (Exception $th) {

            save_live_logs('直播推流回调push_notify接口异常:' . $th->getMessage());
            $this->error($th->getMessage());
        }
    }

```