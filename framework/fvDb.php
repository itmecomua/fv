<?php
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;
require FV_ROOT . '/framework/Db/Doctrine/ORM/Tools/Setup.php';

class fvDb extends fvUnit
{
    private $_lib;
    private $_mode;
    private $_cache;
    private $_configuration;
    private $_driverImpl;
    private $_entityDir;
    private $_proxyDir;
    private $_proxyNamespace;
    private $_connectionOptions;
    private $_entityManager;

    
    
    public function __construct()
    {
        Doctrine\ORM\Tools\Setup::registerAutoloadDirectory( $this->getLib() );
        if ($this->getMode() == "development") 
        {
            $this->setCache( new \Doctrine\Common\Cache\ArrayCache );
        } 
        else 
        {
            $this->setCache( new \Doctrine\Common\Cache\ApcCache );
        }
        
        $this->setConfiguration( new Configuration() );
        $this->getConfiguration()->setMetadataCacheImpl( $this->getCache() );
        $this->getConfiguration()->setDriverImpl( $this->getConfiguration()->newDefaultAnnotationDriver( $this->getEntityDir() ) );
        $this->getConfiguration()->setMetadataDriverImpl( $this->getDriverImpl() );
        $this->getConfiguration()->setQueryCacheImpl( $this->getCache() );
        $this->getConfiguration()->setProxyDir( $this->getProxyDir() );
        $this->getConfiguration()->setProxyNamespace( $this->getProxyNamespace() );
        
        if ($this->getMode() == "development") 
        {
            $this->getConfiguration()->setAutoGenerateProxyClasses( true );
        } 
        else 
        {
            $this->getConfiguration()->setAutoGenerateProxyClasses( false );
        }
        
        $this->setEntityManager( new EntityManager( $this->getConnectionOptions(), $this->getConfiguration() ) );
        
    }
    
    public function setLib( $lib )
    {
        $this->_lib = $lib;
    }
    
    public function getLib()
    {
        return $this->_lib;
    }
    
    public function setMode( $mode )
    {
        $this->_mode = $mode;
    }
    
    public function getMode()
    {
        return $this->_mode;
    }
    
    public function setCache( $cache )
    {
        $this->_cache = $cache;
    }
    
    public function getCache()
    {
        return $this->_cache;
    }
    
    public function setConfiguration( Configuration $Configuration )
    {
        $this->_configuration = $Configuration;
    }
    
    public function getConfiguration()
    {
        return $this->_configuration;
    }
    
    public function setDriverImpl( $driverImpl )
    {
        $this->_driverImpl = $driverImpl;
    }
    
    public function getDriverImpl()
    {
        return $this->_driverImpl;
    }
       
    public function setEntityDir( $entityDir )
    {
        $this->_entityDir = $entityDir;
    }
    
    public function getEntityDir()
    {
        return $this->_entityDir;
    }
    
    public function setProxyDir( $proxyDir )
    {
        $this->_proxyDir = $proxyDir;
    }
    
    public function getProxyDir()
    {
        return $this->_proxyDir;
    }
    
    public function setProxyNamespace( $proxyNamespace )
    {
        $this->_proxyNamespace = $proxyNamespace;
    }
    
    public function getProxyNamespace()
    {
        return $this->_proxyNamespace;
    }

    public function setConnectionOptions( array $connectionOptions )
    {
        $this->_connectionOptions = $connectionOptions;
    }
    
    public function getConnectionOptions()
    {
        return $this->_connectionOptions;
    }
    
    public function setEntityManager( EntityManager $EntityManager )
    {
        $this->_entityManager = $EntityManager;
    }
    
    public function getEntityManager()
    {
        return $this->_entityManager;
    }
    
    
}