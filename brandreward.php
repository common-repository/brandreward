<?php
/* 
* +--------------------------------------------------------------------------+
* | Copyright (c) 2016 Brandreward, Inc.(support@brandreward.com)            |
* +--------------------------------------------------------------------------+
* | This program is free software; you can redistribute it and/or modify     |
* | it under the terms of the GNU General Public License as published by     |
* | the Free Software Foundation; either version 2 of the License, or        |
* | (at your option) any later version.                                      |
* |                                                                          |
* | This program is distributed in the hope that it will be useful,          |
* | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
* | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
* | GNU General Public License for more details.                             |
* |                                                                          |
* | You should have received a copy of the GNU General Public License        |
* | along with this program; if not, write to the Free Software              |
* | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA |
* +--------------------------------------------------------------------------+
*/

/*
Plugin Name: Brandreward
Version: 1.0.2
Description: The easiest way to monetize the links on your site.  Link directly to other sites, just like you do today.  Brandreward automatically affiliates those links -- even links on posts you've already written -- with no extra editing!  Get stats on which links are making you the most money, which are most clicked, and more.

Author: Brandreward
Author URI: http://www.brandreward.com
*/

define( 'BR_MIN_WORDPRESS_REQUIRED', "2.7" );
define( 'BR_WORDPRESS_VERSION_SUPPORTED', version_compare( get_bloginfo( "version" ), BR_MIN_WORDPRESS_REQUIRED, ">=" ) );
define( 'BR_ENABLED', BR_WORDPRESS_VERSION_SUPPORTED);


//定义前台加载script

function brandreward_script(){

  $brandreward_option_key = get_option('brandreward_option_key');
  $html_script = '';

  if($brandreward_option_key){
    $html_script = <<<EOF
<script type="text/javascript">
  var _BRConf = { key: '{$brandreward_option_key}' };

  (function(d, t) {
    var s = d.createElement(t); s.type = 'text/javascript'; s.async = true;
    var scheme = (document.location.protocol == 'https:')?'https':'http';
    s.src = scheme+'://n.brandreward.com/js/br.js';
    var r = d.getElementsByTagName(t)[0]; r.parentNode.insertBefore(s, r);
  }(document, 'script'));
</script>
EOF;
  }

  echo $html_script;
}

add_action( "wp_footer", "brandreward_script" );

//定义后台菜单相关

/** 第1步：定义添加菜单选项的函数 */
function brandreward_plugin_menu() {
    add_options_page( 'Brandreward Options', 'Brandreward', 'manage_options', 'brandreward-', 'brandreward_options' );
}

/** 第2步：将函数注册到钩子中 */
add_action( 'admin_menu', 'brandreward_plugin_menu' );

/** 第3步：定义选项被点击时打开的页面 */
function brandreward_options() {
if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

if(!empty($_POST) && isset($_POST['brandreward_option_key'])){
  if(strlen($_POST['brandreward_option_key']) == 32){
    update_option('brandreward_option_key', $_POST['brandreward_option_key']);
    $html_update_res = <<<EOF
<div id="message" class="updated">
    <p><strong>Key was saved for Brandreward.</strong></p>
</div>
EOF;
  }else{
    $html_update_res = <<<EOF
<div id="message" class="updated" style="border-color:red;">
    <p><strong>Key is wrong.Please check it again</strong></p>
</div>
EOF;
  }
  echo $html_update_res;
}

$brandreward_option_key = get_option('brandreward_option_key');

$html_options = <<<EOF
<div class="wrap">
  <h2>Brandreward Settings</h2>
  <p>Copy your API key from <a href="http://www.brandreward.com/b_account.php">Brandreward</a> and paste it below.</p>
  <form method="post" action="">
    <table>
      <tr>
        <td><strong style="margin-right:10px;">KEY</strong></td>
        <td><input type="text" name="brandreward_option_key" value="{$brandreward_option_key}" maxlength="32" style="margin-right:10px;"></td>
        <td><i style="color:red;">Required</i></td>
      </tr>
    </table>
    <p><input type="submit" value="Save" class="button button-primary button-large"/></p>
  </form>
</div>
EOF;

echo $html_options;
}

?>
