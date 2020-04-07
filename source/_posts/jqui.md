---
title: JQUERY UI 日期控件
date: 2019-07-14 14:17:11
tags: jquery ui
categories: javascript
keywords:
- JQUERY
- JQUERY UI 
- 日期控件
- datepicker

---

``` html
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
</script>
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
```

``` javascript
$("#end_date").datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    yearRange: "2000:2050",
    dayNamesMin: ["天", "一", "二", "三", "四", "五", "六"],
    monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
});
```

