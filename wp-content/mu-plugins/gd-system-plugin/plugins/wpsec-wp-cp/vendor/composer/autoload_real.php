<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitef7712d882d97138ebfc102688cb4ee5
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitef7712d882d97138ebfc102688cb4ee5', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitef7712d882d97138ebfc102688cb4ee5', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitef7712d882d97138ebfc102688cb4ee5::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
