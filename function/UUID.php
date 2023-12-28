<?php
/** op-unit-app:/function/UUID.php
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

/** Unique User ID.
 *
 * @return  string  $uuid
 */
function UUID() : string
{
	//	...
	if(!$uuid = \OP\Cookie::Get('uuid') ){
		$uuid = substr( md5($_SERVER['REMOTE_ADDR'] . microtime()), 0, 10);
		\OP\Cookie::Set('uuid', $uuid);
	}

	//	...
	return $uuid;
}
