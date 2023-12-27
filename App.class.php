<?php
/**
 * op-unit-app:/App.class.php
 *
 * @created   2018-04-04
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2019-02-20
 */
namespace OP\UNIT;

/** Used class.
 *
 * @created   2019-02-20
 */
use OP\OP_CORE;
use OP\OP_UNIT;
use OP\OP_SESSION;
use OP\IF_UNIT;
use OP\IF_APP;
use OP\Env;
use OP\Config;
use OP\Cookie;
use OP\Notice;
use OP\UNIT_APP;
use function OP\Unit;
use function OP\RootPath;
use function OP\ConvertURL;
use function OP\ConvertPath;
use function OP\CompressPath;
use function OP\Args;
use function OP\Content;
use function OP\Template;
use function OP\GetTemplate;
use function OP\UNIT\APP\GetMIME;

/** App
 *
 * @created   2018-04-04
 * @version   1.0
 * @package   op-unit-app
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class App implements IF_UNIT, IF_APP
{
	/** trait.
	 *
	 */
	use OP_CORE, OP_UNIT, OP_SESSION, UNIT_APP;

	/** SmartURL Arguments.
	 *
	 * @var array
	 */
	private $_args;

	/** Automatically.
	 *
	 */
	function Auto()
	{
		try{
			//	Get End-Point.
			$endpoint = Unit('Router')->EndPoint();

			//	Is http?
			if( Env::isHttp() ){

				//	Check end-point if asset directory.
				if( strpos($endpoint, RootPath('asset')) === 0 ){
					//	Overwrite end-point.
					$endpoint = ConvertPath('app:/404.php');
				};

				//	Execute End-Point.
				$hash = Content(CompressPath($endpoint), ['app'=>$this]);

				//	ETag
				if( Config::Get('app')['etag'] ?? null ){
					Unit('ETag')->Auto($hash);
				}

				//	Set mime if empty.
				if(!$mime = Env::Mime() ){
					//	Get extension
					$ext = substr($endpoint, strrpos($endpoint, '.') + 1);

					//	Get MIME
					include(__DIR__.'/function/GetMIME.php');
					$mime = GetMIME($ext);

					//	Set MIME
					Env::Mime($mime);
				}

				//	Do the Layout.
				Unit('Layout')->Auto();

				/*
				//	Check whether to do layout.
				if( $mime === 'text/html' and Env::Get('layout')['execute'] ?? null ){
					//	Do layout.
					$this->__LAYOUT();
				}else{
					//	No layout.
					Content();
				};
				*/

			}else{
				/*
				//	In case of shell
				$root = $_SERVER['PWD'].'/';
				$path = $_SERVER['argv'][1] ?? 'index.php';
				$file = $root . $path;

				//	...
				if(!$endpoint = realpath($file) ){
					throw new Exception("This file has not been exists. ($file)");
				};
				*/

				// TODO: CompressPath() every time is useless.
				Template(CompressPath($endpoint), [], true, true);
			};
		}catch( \Throwable $e ){
			Notice::Set($e);
		};
	}

	/** Register WebPack file.
	 *
	 * @deprecated 2023-01-30
	 * @param  string|array  $path
	 */
	function WebPack($path)
	{
		Unit('WebPack')->Auto($path);
	}

	/** Get/Set title.
	 *
	 * @param  string  $title
	 * @param  string  $separator
	 * @return string  $title
	 */
	static function Title($title=null, $separator=' | ')
	{
		//	...
		static $_title;

		//	...
		if( empty($title) ){
			return $_title;
		}

		//	...
		if( empty($_title) ){
			$_title = Env::Get('app')['title'] ?? null;
		};

		//	...
		$_title = $_title ? $title . $separator . $_title : $title;
	}

	/** Unique User ID.
	 *
	 * @return  string  $uuid
	 */
	static function UUID()
	{
		//	...
		if(!$uuid = Cookie::Get('uuid') ){
			$uuid = substr( md5($_SERVER['REMOTE_ADDR'] . microtime()), 0, 10);
			Cookie::Set('uuid', $uuid);
		}

		//	...
		return $uuid;
	}

	/** Include template file.
	 *
	 *  <pre>
	 *  OP::Template($file_path);
	 *  OP::Unit('Template')->Out($file_path);
	 *  </pre>
	 *
	 * @deprecated
	 * @created   2022-05-07
	 */
	static function Template($template, array $args=[], $throw_exception=true)
	{
		Template($template, $args, $throw_exception);
	}

	/** Content
	 *
	 * @deprecated
	 * @created   2022-05-07
	 */
	static function Content()
	{
		Content();
	}
}
