=== QQ mood ===
Contributors: webbeast
Donate link: http://www.webucd.com/qq-mood
Tags: qq, sign
Requires at least: 2.5.0.0
Tested up to: 1.0.0
Stable tag: trunk

This pulgin generates qq mood for WordPress Blog. 

== Description ==
This pulgin generates qq mood for WordPress Blog. 
此插件将会自动将QQ心情显示到wordpress博客上, 在QQ空间或者滔滔上的信息将显示到wordpress博客。

主要功能:
支持将QQ签名同步到Wordpress博客。
安装插件后只需在需要显示地方嵌入代码:
<?php echo QQMood_insert();?>

支持自定义模板、样式。
此插件会定期去qq网站抓取QQ签名, 并在本地进行缓存。 

== Installation ==
1. Upload `baidu-tracker.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. admin -> "Settings" -> "QQ mood"

1、下载此插件并上传到wp-content\plugins目录中；
2、登录网站后台激活此插件；
3、然后进入“设置”，“QQ mood” 进行设置。


只需在需要显示的模板嵌入代码:
<?php echo QQMood_insert();?>

== Screenshots ==
1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.


