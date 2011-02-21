<?php
/**
 * Une classe qui simplifie l'utilisation du cache
 * 
 * @package overlord
 * @subpackage cache
 */
class Overlord_Cache
{


	/**
	 * Objet Zend_Cache
	 *
	 * @var Zend_Cache_Core
	 */
	private static $_cache = null;


	/**
	 * Lifetime par défaut
	 *
	 * @var integer
	 */
	private static $_lifetime = 3600;


	/**
	 * Emplacement des fichiers de cache
	 *
	 * @var string
	 */
	private static $_cacheDir = null;


	/**
	 * Utilise APC par défaut, sinon utilise les fichiers
	 * 
	 * @return void
	 */
	private static function init()
	{
		if( self::$_cache === null )
		{
			$frontendOptions = array( 'automatic_serialization' => true, 'lifetime' => self::$_lifetime );
			$backendOptions = array( 'cache_dir' => self::$_cacheDir );
			try
			{
				if( extension_loaded( 'APC' ) )
					self::$_cache = Zend_Cache::factory( 'Core', 'APC', $frontendOptions, array() );
				else
					self::$_cache = Zend_Cache::factory( 'Core', 'File', $frontendOptions, $backendOptions );
			}
			catch( Zend_Cache_Exception $e )
			{
				throw new Overlord_Cache_Exception( $e );
			}
			if( ! self::$_cache )
				throw new Overlord_Cache_Exception( "No cache backend available." );
		}
	}


	/**
	 * Affecte une durée de mise en cache
	 *
	 * @param integer $lifetime
	 */
	public static function setup( $lifetime, $filesCachePath = null )
	{
		if( self::$_cache !== null )
		{
			throw new Overlord_Cache_Exception( "Cache already used." );
		}
		self::$_lifetime = ( integer ) $lifetime;
		if( $filesCachePath !== null )
		{
			self::$_cacheDir = realpath( $filesCachePath );
		}
	}


	/**
	 * Insertion dans le cache
	 *
	 * @param string $key
	 * @param mixed $data
	 * @return boolean
	 */
	public static function set( $data, $key )
	{
		self::init();
		return self::$_cache->save( $data, $key );
	}


	/**
	 * Retrait d'une valeur de cache
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function get( $key )
	{
		self::init();
		return self::$_cache->load( $key );
	}


	/**
	 * Nettoyage du cache
	 *
	 * @param string $key
	 * @return boolean
	 */
	public static function clean( $key = null )
	{
		self::init();
		if( $key === null )
		{
			return self::$_cache->clean();
		}
		return self::$_cache->remove( $key );
	}


	/**
	 * Récupère l'instance sous-jascente du cache
	 * 
	 * @return Zend_Cache_Core
	 * @throws Overlord_Cache_Exception
	 */
	public static function getCacheInstance()
	{
		self::init();
		if( is_null( self::$_cache ) )
		{
			throw new Overlord_Cache_Exception( "Cache not set yet." );
		}
		
		return self::$_cache;
	}
}