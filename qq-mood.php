<?php
/*
Plugin Name: QQ mood
Plugin URI: http://www.webucd.com/qq-mood/
Description: This pulgin generates qq mood for WordPress Blog. 此插件将会自动将QQ心情显示到wordpress博客上, 在QQ空间或者滔滔上的信息将显示到wordpress博客。 
Version: 1.0.0
Author: webbeast
Author URI: http://www.webucd.com
*/

/*  Copyright 2010 webbeast  (email : admin _at_ webucd.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

/**
 * 常量
 */
//define (QQ_MOOD_VERSION_PLUGIN, '1.0.0', true);


/**
 * 依赖的类
 */
include ("include/class.QQsign.php");

/**
 * 设置配置项
 * @param unknown_type $option_name
 * @param unknown_type $option_value
 */
function QQMood_set_option($option_name, $option_value) {
	$QQMood_options = get_option ( 'QQMood_options' );
	$QQMood_options [$option_name] = $option_value;
	update_option ( 'QQMood_options', $QQMood_options );
}

/**
 * 获取配置项
 * @param unknown_type $option_name
 * @param unknown_type $option_value
 */
function QQMood_get_option($option_name) {
	$QQMood_options = get_option ( 'QQMood_options' );
	if (! $QQMood_options || ! array_key_exists ( $option_name, $QQMood_options )) {
		$QQMood_default_options = array ();
		$QQMood_default_options ["template_head"] = "<ul>";
		$QQMood_default_options ["template_loop"] = "<li>{0}<span>{1}</span></li>";
		$QQMood_default_options ["template_bottom"] = "</ul>";
		$QQMood_default_options ['qq'] = "4641856";
		$QQMood_default_options ['timeout'] = "1800";
		$QQMood_default_options ['num'] = "10";
		$QQMood_default_options ['pos'] = "0";
		add_option ( 'QQMood_options', $QQMood_default_options, 'Settings for QQ mood plugin' );
		$result = $QQMood_options [$option_name];
	} else {
		$result = $QQMood_options [$option_name];
	}
	return $result;
}

/**
 * 字符串格式化
 * @param {String} $string  需要替换的模板对象
 * @param {Array} $db 数据源
 */
function string_format($string, $db) {
	$patterns = array ();
	$patterns [0] = "/\{(0)\}/";
	$patterns [1] = "/\{(1)\}/";
	$str = preg_replace ( $patterns, $db, $string );
	return $str;
}

/**
 * 直接PHP代码输出QQ心情
 */
function QQMood_insert() {
	if (QQMood_get_option ( 'qq' )) {
		$QQMood_options = get_option ( 'QQMood_options' );
		$test = new QQsign ( $QQMood_options ["qq"], $QQMood_options ["timeout"] );
		$test->getSign ( 0, $QQMood_options ["num"] );
		$db = json_decode ( $test->data );
		$str = '';
		$str .= $QQMood_options ["template_head"];
		foreach ( $db as $key => $value ) {
			$str .= string_format ( $QQMood_options ["template_loop"], $value );
		}
		$str .= $QQMood_options ["template_bottom"];
		echo $str;
	}
}

/**
 * 管理菜单
 */
function QQMoodAdmin() {
	if (function_exists ( 'add_options_page' )) {
		add_options_page ( 'QQ Mood Install', 'QQ Mood', 8, basename ( __FILE__ ), 'QQMood_admin_init' );
	}
}

/**
 * 管理菜单初始化
 */
function QQMood_admin_init() {
	$qq = trim ( $_POST ['qq'] );
	$submit = trim ( $_POST ['Submit'] );
	if ($submit) {
		if (isset ( $qq )) {
			QQMood_set_option ( 'qq', $qq );
		}
		if (isset ( $_POST ['template_head'] )) {
			QQMood_set_option ( 'template_head', $_POST ['template_head'] );
		}
		if (isset ( $_POST ['template_loop'] )) {
			QQMood_set_option ( 'template_loop', $_POST ['template_loop'] );
		}
		if (isset ( $_POST ['template_bottom'] )) {
			QQMood_set_option ( 'template_bottom', $_POST ['template_bottom'] );
		}
		if (isset ( $_POST ['timeout'] )) {
			QQMood_set_option ( 'timeout', $_POST ['timeout'] );
		}
		if (isset ( $_POST ['num'] )) {
			QQMood_set_option ( 'num', $_POST ['num'] );
		}
		echo QQMood_Tip ( "设置成功！" );
	} else {
		QQMood_get_option ( 'qq' );
	}
	QQMood_admin_html ( get_option ( 'QQMood_options' ) );
}

/**
 * 输出提示信息
 * @param {String} $msg 提示信息
 */
function QQMood_Tip($msg) {
	return '<div class="updated"><p><strong>' . $msg . '</strong></p></div>';
}

/**
 * 输出错误提示信息
 * @param {String} $msg 提示信息
 */

function QQMood_error($msg) {
	return '<div class="error settings-error"><p><strong>' . $msg . '</strong></p></div>';

}

/**
 * 输出设置页HTMl
 * @param {Array} $options 配置项信息
 */
function QQMood_admin_html($options) {
	echo '<div class=wrap>';
	echo '<form method="post">';
	echo '<h2>QQ心情插件设置</h2>';
	echo '<p>QQ号码:<br><input type="text" value="' . stripslashes ( $options ['qq'] ) . '" id="QQ_number_string" name="qq"></p>';
	echo '<p>显示条数:<br><input type="text" value="' . stripslashes ( $options ['num'] ) . '" id="QQ_number" name="num"></p>';
	echo '<p>间隔时间:(单位:秒)<br><input type="text" value="' . stripslashes ( $options ['timeout'] ) . '" id="QQ_timeout" name="timeout">';
	echo '<br><span style="color:grey">默认设置是每半个小时更新一次QQ心情，并缓存到本地。请谨慎设置间隔时间，此值会影响网站的性能。</span></p>';
	echo '<p>模板开始标签:<textarea rows="1" class="large-text code" id="QQ_template_head" name="template_head">' . stripslashes ( $options ['template_head'] ) . '</textarea></p>';
	echo '<p>模板循环片段:<textarea rows="3" class="large-text code" id="QQ_template_loop" name="template_loop">' . stripslashes ( $options ['template_loop'] ) . '</textarea></p>';
	echo '<p>模板结束标签:<textarea rows="1" class="large-text code" id="QQ_template_bottom" name="template_bottom">' . stripslashes ( $options ['template_bottom'] ) . '</textarea></p>';
	echo '<p class="submit"><input type="submit" value="保存设置" class="button-primary" name="Submit"></p>';
	echo '</form>';
	echo '</div>';
}

add_action ( 'admin_menu', 'QQMoodAdmin' );
?>