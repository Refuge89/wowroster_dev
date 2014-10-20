<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id: add.php 2393 2012-02-15 19:56:10Z ulminia@gmail.com $
 * @link       http://www.wowroster.net
 * @package    News
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

if( !$roster->auth->getAuthorized( $addon['config']['news_add'] ) )
{
	echo $roster->auth->getLoginForm($addon['config']['news_add']);
	return; //To the addon framework
}

// Assign template vars
$roster->tpl->assign_vars(array(
	'S_ADD_NEWS'     => false,
	'U_BACK'   => makelink('guild-main'),
	'S_HTML_ENABLE' => false,
	'S_NEWS_HTML'   => $addon['config']['news_html'],
	'S_POSTER'				=> $_COOKIE['roster_user'],
	'U_FORMACTION'  => makelink('guild-main'),
	)
);

if($addon['config']['news_html'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE', true);

	if($addon['config']['news_nicedit'] > 0)
	{
		roster_add_js('js/ckeditor/ckeditor.js');
	}
}

$roster->tpl->set_filenames(array(
	'head' => $addon['basename'] . '/news_head.html',
	'body' => $addon['basename'] . '/add.html',
	'foot' => $addon['basename'] . '/news_foot.html'
	)
);
$roster->tpl->display('head');
$roster->tpl->display('body');
$roster->tpl->display('foot');
