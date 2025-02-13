<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit23bbd7469491b5780d6a61f29d55edf3
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Akobiabiolaabdbarr\\ScrutinApp\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Akobiabiolaabdbarr\\ScrutinApp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit23bbd7469491b5780d6a61f29d55edf3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit23bbd7469491b5780d6a61f29d55edf3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit23bbd7469491b5780d6a61f29d55edf3::$classMap;

        }, null, ClassLoader::class);
    }
}
