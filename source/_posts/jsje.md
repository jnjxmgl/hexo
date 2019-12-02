---
title: JS实现INPUT标签输入有效的金额
date: 2019-07-14 14:47:57
tags: js
categories: javascript
---

    obj.value = obj.value.replace(/1/g, ""); //清除"数字"和"."以外的字符
    obj.value = obj.value.replace(/^./g, ""); //验证第一个字符是数字而不是字符         
    obj.value = obj.value.replace(/.{2,}/g, "."); //只保留第一个.清除多余的       
    obj.value = obj.value.replace(".", "$#$").replace(/./g, "").replace("$#$", ".");
    obj.value = obj.value.replace(/^(-)(d+).(dd).$/, '$1$2.$3'); //只能输入两个小数

