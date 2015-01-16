<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit45735aeb3e68c60b46aab7214eb631a8
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit45735aeb3e68c60b46aab7214eb631a8', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInit45735aeb3e68c60b46aab7214eb631a8', 'loadClassLoader'));

        $map = require __DIR__ . '/autoload_namespaces.php';
        foreach ($map as $namespace => $path) {
            $loader->set($namespace, $path);
        }

        $map = require __DIR__ . '/autoload_psr4.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }

        $classMap = require __DIR__ . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }

        spl_autoload_register(array('ComposerAutoloaderInit45735aeb3e68c60b46aab7214eb631a8', 'autoload'), true, true);

        $loader->register(true);

        $includeFiles = require __DIR__ . '/autoload_files.php';
        foreach ($includeFiles as $file) {
            composerRequire45735aeb3e68c60b46aab7214eb631a8($file);
        }

        return $loader;
    }

    public static function autoload($class)
    {
        $dir = dirname(dirname(__DIR__)) . '/';
        $prefixes = array('Designplug\\Repository');
        foreach ($prefixes as $prefix) {
            if (0 !== strpos($class, $prefix)) {
                continue;
            }
            $path = $dir . implode('/', array_slice(explode('\\', $class), 2)).'.php';
            if (!$path = stream_resolve_include_path($path)) {
                return false;
            }
            require $path;

            return true;
        }
    }
}

function composerRequire45735aeb3e68c60b46aab7214eb631a8($file)
{
    require $file;
}
