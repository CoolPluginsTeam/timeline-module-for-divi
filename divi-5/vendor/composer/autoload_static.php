<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc194c023846e5a62a3596de3d5ce45f6
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DTMC\\Modules\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DTMC\\Modules\\' => 
        array (
            0 => __DIR__ . '/../..' . '/server/Modules',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc194c023846e5a62a3596de3d5ce45f6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc194c023846e5a62a3596de3d5ce45f6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc194c023846e5a62a3596de3d5ce45f6::$classMap;

        }, null, ClassLoader::class);
    }
}
