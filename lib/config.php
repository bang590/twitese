<?php
//twitter api地址，如果是国外空间，请用http://twitter.com，国内空间需要用第三方API proxy
define('API_URL', 'http://api.twitter.com/1');
//“随便看看”与“排行榜”的api地址，由架设在GAE的twitese提供
define('TWITESE_API_URL', 'http://twiteseapi.appspot.com');
//网站名称
define('SITE_NAME', '推特中文圈');

//加密用户名密码用的密匙，随便输入一字符串。
//需要mcrypt模块支持，如果值为空则不加密。视空间支持情况选择开启与否
define('SECURE_KEY', 'webbang');

//附加密码，如果密码不为空，登录时会要求用户输入附加密码。
define('TWITESE_PASSWORD', '');

//是否允许游客查看用户的推(如果允许请改为true)
define('VISITOR_ALLOW', false);

//-------------------------------------------------------------
//以下为OAuth 配置，如果不需要请留空
//如果需要请先到 http://twitter.com/apps/new 申请
//-------------------------------------------------------------

//你的 Consumer Key
define("CONSUMER_KEY", "Y7rcjO9wMUZyBfhYBfgKow");

//你的 Consumer Secret
define("CONSUMER_SECRET", "3Xj8wki5MG1hrZppc1R6ZJaNZdxp2UJJ8A7s772E");
?>
