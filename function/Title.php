<?php
/** op-unit-app:/function/Title.php
 *
 * @created		2023-12-27
 * @version		1.0
 * @package		op-unit-app
 * @author		Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright	Tomoaki Nagahara All right reserved.
 */

/** Declare strict
 *
 */
declare(strict_types=1);

/** namespace
 *
 */
namespace OP\UNIT\APP;

/** Get/Set Title for HTML's title tag.
 *
 * @param  string  $title
 * @param  string  $separator
 * @return string  $title
 */
function Title($title=null, $separator=' | ') : ?string
{
	//	define
	static $_title;

	//	Init
	if( empty($_title) ){
		$_title = OP()->Config('app')['title'] ?? null;
	};

	//	Get
	if( empty($title) ){
		return $_title;
	}

	//	Set
	$_title = $_title ? $title . $separator . $_title : $title;

	//	Return
	return $_title;
}
