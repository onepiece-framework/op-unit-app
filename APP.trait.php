<?php
/**
 * op-unit-app:/APP.trait.php
 *
 * @created   2019-11-28
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2019-11-28
 */
namespace OP;

/** APP
 *
 * @created   2019-11-28
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
trait UNIT_APP
{

	/** Return Canonical
	 *
	 * @deprecated 2023-01-30
	 * @created   2022-09-30
	 * @return    string
	 */
	static function Canonical()
	{
		return Config::Get('app')['canonical'] ?? null;
	}
}
