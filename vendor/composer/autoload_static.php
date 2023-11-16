<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit376104e423f706f5284434ff3d8382fd
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit376104e423f706f5284434ff3d8382fd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit376104e423f706f5284434ff3d8382fd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit376104e423f706f5284434ff3d8382fd::$classMap;

        }, null, ClassLoader::class);
    }
}
