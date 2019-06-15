<?php
/**
* @代码开源，透明，请勿用于商业用途，完全开源模式，供大家一起探讨研究 
* @支持全网（支持跨域）的M3U8资源加速 * @调用举例：http://域名/video/?url= 
* @DPlayer播放器由于P2P原因只支持M3U8格式的视频
* @本项目地址:https://github.com/GreatSatan79/Video-api
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
function ismobile()
{
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|\\(.*?\\)|', $useragent, $matches) > 0 ? $matches[0] : '';
    function CheckSubstrs($substrs, $text)
    {
        foreach ($substrs as $substr) {
            if (false !== strpos($text, $substr)) {
                return true;
            }
        }
        return false;
    }
    $mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
    $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');
    $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) || CheckSubstrs($mobile_token_list, $useragent);
    if ($found_mobile) {
        return true;
    } else {
        return false;
    }
}
?><?php
$flag = ismobile();
if ($flag) {
?><!DOCTYPE html>
<html lang="en">   
    <head>
        <meta charset="UTF-8">
        <title>video.js播放器</title>
        <link rel="stylesheet" type="text/css" href="css/video-js.min.css">
        <script src="./js/video.min.js"></script>
        <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>
    </head>
    <body style="margin:0;padding:0;overflow:hidden" scroll="no">
        <section id="videoPlayer">
            <video id="example-video" class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" controls preload="auto" poster="" data-setup='{}'>
                <source src="<?php echo $url; ?>" type="application/x-mpegURL" id="target"></video>
        </section>
        <script type="text/javascript">
          var player = videojs('example-video', {
                "poster": "",
                "controls": "true"
            },
            function() {
                this.on('play',
                function() {
                    console.log('正在播放');
                });
                //暂停--播放完毕后也会暂停
                this.on('pause',
                function() {
                    console.log("暂停中")
                });
                // 结束
                this.on('ended',
                function() {
                    console.log('结束');
                })
            });
      </script>
    </body>
</html><?php } else { ?>
<!DOCTYPE html>
<html>
    <head>
        <title>P2P版DPlayer播放器</title>
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
        <link rel="stylesheet" href="/static/player/dplayer/DPlayer.min.css" type="text/css" />
        <style type="text/css">body,html{width:100%;height:100%;background:#000;padding:0;margin:0;overflow-x:hidden;overflow-y:hidden}*{margin:0;border:0;padding:0;text-decoration:none}#stats{position:fixed;top:5px;left:8px;font-size:12px;color:#fdfdfd;text-shadow:1px 1px 1px #000, 1px 1px 1px #000}#dplayer{position:inherit}</style></head>   
    <body style="background:#000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" oncontextmenu="window.event.returnValue=false">
        <div id="dplayer"></div>
        <div id="stats"></div>
        <script src="https://cdn.jsdelivr.net/npm/cdnbye@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/p2p-dplayer@latest"></script>
        <script type="text/javascript">
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
            var m3u8url =  '<?php echo $url; ?>'  
            var dp = new DPlayer({    autoplay: true,
                    container: document.getElementById('dplayer'),
                    video: {      url: m3u8url,
                    type: 'hls'     
                },
                autoplay: true,
                theme: '#FADFA3',
                preload: true,
                loop: true,
                screenshot: true,
                hotkey: true,
                live: false,
                logo: '',
                volume: '0.7',
                danmaku: {
                    id: '<?php echo $url; ?>',
                    api: 'https://dplayer.moerats.com/',
                },
                hlsjsConfig: {
                    // debug: false,
                    // Other hlsjsConfig options provided by hls.js
                    p2pConfig: {
                        logLevel: true,
                        live: false,
                        // Other p2pConfig options provided by CDNBye
                        // https://docs.cdnbye.com/#/API
                    }
                }
            });
            dp.seek(webdata.get('pay' + m3u8url));
            setInterval(function() {
                webdata.set('pay' + m3u8url, dp.video.currentTime);
            },
            1000);
            var _peerId = '',
            _peerNum = 0,
            _totalP2PDownloaded = 0,
            _totalP2PUploaded = 0;
            dp.on('stats',
            function(stats) {
                _totalP2PDownloaded = stats.totalP2PDownloaded;
                _totalP2PUploaded = stats.totalP2PUploaded;
                updateStats();
            });
            dp.on('peerId',
            function(peerId) {
                _peerId = peerId;
            });
            dp.on('peers',
            function(peers) {
                _peerNum = peers.length;
                updateStats();
            });
            function updateStats() {
                var text = 'P2P正在为您加速' + (_totalP2PDownloaded / 1024).toFixed(2) + 'MB 已分享' + (_totalP2PUploaded / 1024).toFixed(2) + 'MB' + ' 连接节点' + _peerNum + '个';
                document.getElementById('stats').innerText = text
            }
      </script>
    </body>
</html><?php } ?>
