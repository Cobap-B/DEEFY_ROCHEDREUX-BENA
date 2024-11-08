<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit29f7d4ada8e30a3c58c14bc87553fabe
{
    public static $prefixLengthsPsr4 = array (
        'd' => 
        array (
            'deefy\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'deefy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit29f7d4ada8e30a3c58c14bc87553fabe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit29f7d4ada8e30a3c58c14bc87553fabe::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit29f7d4ada8e30a3c58c14bc87553fabe::$classMap;

        }, null, ClassLoader::class);
    }
}
