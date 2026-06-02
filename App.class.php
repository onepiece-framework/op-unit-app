<?php
/**	op-unit-app:/App.class.php
 *
 * @created    2018-04-04
 * @version    1.0
 * @package    op-unit-app
 * @author     Tomoaki Nagahara
 * @copyright  Tomoaki Nagahara All right reserved.
 */

/**	Declare strict
 *
 */
declare(strict_types=1);

/**	namespace
 *
 */
namespace OP\UNIT;

/**	Used class.
 *
 */
use OP\OP_CORE;
use OP\IF_APP;
use OP\OP_CI;

/**	App
 *
 * @created    2018-04-04
 * @version    1.0
 * @package    op-unit-app
 * @author     Tomoaki Nagahara
 * @copyright  Tomoaki Nagahara All right reserved.
 */
class App implements IF_APP
{
	/**	trait.
	 *
	 */
	use OP_CORE;
	use OP_CI;

	/**	Content
	 *
	 * @var string
	 */
	static private $_content;

	/**	Destruct
	 *
	 * @created    2025-06-24
	 */
	function __destruct()
	{
		//	...
		if(!empty( self::$_content ) ){
			D("`App::Content()` is not called.");
		}

		//	...
		self::Content();
	}

	/**	Automatically.
	 *
	 */
	function Auto()
	{
		//	Execute the end-point.
		try{
			//	Get End-Point.
			if(!$endpoint = OP()->Unit()->Router()->EndPoint() ){
				return;
			}

			//	For HTML Pass Through
			$extension = substr($endpoint, strrpos($endpoint, '.')+1);

			//	MIME
			$mime = OP()->isHttp() ? OP()->MIME($extension): 'text/plain';

			//	Not text
			if( strpos($mime, 'text/') === false ){
				echo file_get_contents($endpoint);
				return;
			}

			//	Since the full path cannot be used, It is convert to a meta path.
			$endpoint = OP()->Path($endpoint);

			//	Switch to CLI or HTTP
			if( OP()->isShell() ){
				//	...
				OP()->MIME('text/plain');
				//	This change is to support interactive mode.
				OP()->Template($endpoint);
			}else{

			//	Caches the output.
			ob_start();

			//	Execute the End-Point.
			OP()->Template($endpoint);

			//	Get the content and save it, and flush the cache.
			self::$_content = ob_get_contents();

			//	Save memory usage.
			ob_end_clean();

			//	...
			if( OP()->MIME() === 'text/html' ){
				//	Do Layout.
				OP()->Unit()->Layout()->Auto();
			}else{
				//	Do no Layout.
				self::Content();
			}
			}

		}catch( \Throwable $e ){
			OP()->Error($e);
		};
	}

	/**	Output content.
	 *
	 *  This method is intended to be called from a Layout.
	 *
	 * @revival  2024-07-08
	 */
	static public function Content()
	{
		//	...
		if( self::$_content ){
			echo self::$_content;
			self::$_content = null;

			/*	Disabled fingerprint feature
			//	...
			if( OP()->MIME() === 'text/html' ){
				//	finger print
				if(!$fingerprint = OP()->Session()->Get('fingerprint') ){
					$fingerprint = self::FingerPrint();
					OP()->Session()->Set('fingerprint', $fingerprint);
				}
				echo "\t<div data-content-hash=\"{$fingerprint}\"></div>\n";
			}
			*/
		}
	}

	/**	Check if the ETag matches.
	 *
	 * <pre>
	 *  ETag is save on transfer volume.
	 *  Return true is match ETag.
	 *  "HTTP/1.1 304 Not Modified" header was returned.
	 *  If return false, You output the content.
	 * </pre>
	 *
	 * @revival  2024-07-08
	 * @return   bool
	 */
	static private function ETag(string $etag, int $age=3600) : bool
	{
		return include(__DIR__.'/include/ETag.php');
	}

	/**	Get/Set Title for HTML's title tag.
	 *
	 * @param  string  $title
	 * @param  string  $separator
	 * @return string  $title
	 */
	static function Title($title=null, $separator=' | ') : ?string
	{
		require_once(__DIR__.'/function/Title.php');
		return APP\Title($title, $separator);
	}

	/** Get app origin.
	 *
	 * @created    2025-08-24
	 * @return     string
	 */
	static function Origin() : string
	{
		return include(__DIR__.'/include/Origin.php');
	}

	/**	Return encrypted finger print.
	 *
	 * @created    2025-11-19
	 * @return     string
	 */
	/*
	static function FingerPrint() : string
	{
		//	...
		$fingerprint =  [
			'core uuid' => OP()->Cookie()->UserID(),
			'timestamp' => OP()->Timestamp(),
			'ipaddress' => $_SERVER['REMOTE_ADDR'],
		];

		//	...
		return OP()->Encrypt($fingerprint);
	}
	*/
}
