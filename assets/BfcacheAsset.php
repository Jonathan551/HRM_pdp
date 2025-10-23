<?php
namespace app\assets;

use yii\web\AssetBundle;

class BfcacheAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web';
    public $js = [
        'js/bfcache-guard.js',
    ];
    public $depends = [
        'app\assets\AppAsset', // atau asset lain yang kamu pakai
    ];
}
