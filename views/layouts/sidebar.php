<?php

use deyraka\materialdashboard\widgets\Menu;
use yii\helpers\Html;

$this->registerJsFile('@web/js/sidebar.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);

?>
<div class="sidebar" data-color="purple" data-background-color="black" data-image="<?= $directoryAsset; ?>/img/sidebar-1.jpg">
    <div class="logo">
        <a href="#" class="simple-text logo-normal">
            HRM App
        </a>
    </div>
    <div class="sidebar-wrapper">
        <?= Menu::widget([
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/']],
                ['label' => 'Master Data', 'icon' => 'folder', 'items' => [
                    ['label' => 'Master Data User', 'icon' => 'account_circle', 'url' => ['/user/index']],
                    ['label' => 'Master Data Jabatan', 'icon' => 'badge', 'url' => ['/master-jabatan/index']],
                    ['label' => 'Master Data Depatement', 'icon' => 'business', 'url' => ['/master-departement/index']],
                    ['label' => 'Master Data Kriteria', 'icon' => 'content_paste', 'url' => ['/master-kriteria/index']],
                    ['label' => 'Master Data Anchor', 'icon' => 'anchor', 'url' => ['/master-anchor/index']],
                    ['label' => 'Master Data Status', 'icon' => 'fact_check', 'url' => ['/status/index']],
            ]],
            ['label' => 'Master Penilaian', 'icon' => 'assignment', 'items' => [
                    ['label' => 'Penilaian Karyawan', 'icon' => 'assignment_ind', 'url' => ['/master-penilaian/index']],
            ]]
        ]]); ?>    
    </div>
</div>
