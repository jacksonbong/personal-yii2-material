<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1278c0f12d8065a4d7c35e7789b3dd72
{
    public static $prefixLengthsPsr4 = array (
        'y' => 
        array (
            'yii\\materialicons\\' => 18,
            'yii\\composer\\' => 13,
            'yii\\' => 4,
        ),
        'c' => 
        array (
            'cebe\\markdown\\' => 14,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Log\\' => 8,
            'Psr\\Http\\Message\\' => 17,
            'Personal\\Yii2Material\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'yii\\materialicons\\' => 
        array (
            0 => __DIR__ . '/..' . '/mervick/yii2-material-design-icons',
        ),
        'yii\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-composer',
        ),
        'yii\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2',
        ),
        'cebe\\markdown\\' => 
        array (
            0 => __DIR__ . '/..' . '/cebe/markdown',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Personal\\Yii2Material\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Yii' => __DIR__ . '/..' . '/yiisoft/yii2/Yii.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1278c0f12d8065a4d7c35e7789b3dd72::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1278c0f12d8065a4d7c35e7789b3dd72::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1278c0f12d8065a4d7c35e7789b3dd72::$classMap;

        }, null, ClassLoader::class);
    }
}
