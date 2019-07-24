<?php
/**
* @代码开源，透明，请勿用于商业用途，完全开源模式，供大家一起探讨研究 
* @支持全网（支持跨域）的M3U8资源加速 * @调用举例：http://域名/video/?url= 
*/
error_reporting(0);
header("Content-Type: text/html; charset=utf-8");
$url = $_GET['url'];
if (strpos(wm_https(), 'ps:') !== false) {
    //接口带 S 证书
    if (strpos($url, 'http://') !== false) {
        header("location:http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . '?' . $_SERVER['QUERY_STRING']);
        //判断直链没带 S 证书就跳转到不带 S 证书的接口
        exit;
    }
} else {
    //接口不带 S 证书
    if (strpos($url, 'https://') !== false) {
        header("location:https://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . '?' . $_SERVER['QUERY_STRING']);
        //判断直链带 S 证书就跳转到带 S 证书的接口
        exit;
    }
}
function wm_https()
{
    $http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? 'https://' : 'http://';
    return $http;
}

?>
<!DOCTYPE html>
<html>    
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta http-equiv="content-language" content="zh-CN" />
        <meta http-equiv="X-UA-Compatible" content="chrome=1" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta name="referrer" content="never" />
        <meta name="renderer" content="webkit" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="HandheldFriendly" content="true" />
        <meta name="x5-page-mode" content="app" />
        <meta name="Viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
      	<title>视频API</title>
        <link rel="stylesheet" href="/static/player/dplayer/DPlayer.min.css" type="text/css" />
        <link rel="stylesheet" href="https://cdn.bootcss.com/dplayer/1.25.0/DPlayer.min.css">
        <style type="text/css">body, html, .dplayer { padding: 0; margin: 0; width: 100%; height: 100%; background-color:#000; } a { text-decoration: none; }</style>
  	</head> 
    <body>
        <div id="player" class="dplayer"></div>
        <script src="https://cdn.bootcss.com/flv.js/1.5.0/flv.min.js"></script>
        <script src="https://cdn.bootcss.com/hls.js/8.0.0-beta.3/hls.min.js"></script>
        <script src="https://cdn.bootcss.com/dplayer/1.25.0/DPlayer.min.js"></script>
        <script type="text/javascript">var isiPad = navigator.userAgent.match(/iPad|iPhone|Android|Linux|iPod/i) != null;
            if (isiPad) {
                document.getElementById('player').innerHTML = '<video src="<?php echo $url; ?>" controls="controls" preload="preload" poster="/dplayer/loading.gif" width="100%" height="100%" x-webkit-airplay="allow"></video>';
            } else {
                var webdata = {
                    set: function(key, val) {
                        window.sessionStorage.setItem(key, val);
                    },
                    get: function(key) {
                        return window.sessionStorage.getItem(key);
                    },
                    del: function(key) {
                        window.sessionStorage.removeItem(key);
                    },
                    clear: function(key) {
                        window.sessionStorage.clear();
                    }
                };
                var m3u8url = '<?php echo $url; ?>'
                var pic = "";
                var dp = new DPlayer({
                    element: document.getElementById("player"),
                    autoplay: true,
                    video: {
                        url: '<?php echo $url; ?>',
                        pic: pic
                    },
                    danmaku: {
                        id: '<?php echo $url; ?>',
                        api: 'https://dplayer.moerats.com/',
                    }
                });
                dp.seek(webdata.get('pay' + m3u8url));
                setInterval(function() {
                    webdata.set('pay' + m3u8url, dp.video.currentTime);
                },
                1000);
            }</script>
    </body>
</html>
