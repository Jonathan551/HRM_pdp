<?php

use deyraka\materialdashboard\widgets\Menu;
use app\components\UserAccess;
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
            [
                'label' => 'Dashboard',
                'icon' => 'dashboard',
                'url' => ['/site/index'],
                'visible' => UserAccess::hasPermission('akses_dashboard')
            ],
            [
                'label' => 'Master Data',
                'icon' => 'folder',
                'items' => [
                    [
                        'label' => 'Master Data User',
                        'icon' => 'account_circle',
                        'url' => ['/user/index'],
                        'visible' => UserAccess::hasPermission('akses_user')
                    ],
                    [
                        'label' => 'Master Data Jabatan',
                        'icon' => 'badge',
                        'url' => ['/master-jabatan/index'],
                        'visible' => UserAccess::hasPermission('akses_jabatan')
                    ],
                    [
                        'label' => 'Master Data Depatement',
                        'icon' => 'business',
                        'url' => ['/master-departement/index'],
                        'visible' => UserAccess::hasPermission('akses_departement')
                    ],
                    [
                        'label' => 'Master Data Kriteria',
                        'icon' => 'content_paste',
                        'url' => ['/master-kriteria/index'],
                        'visible' => UserAccess::hasPermission('akses_kriteria')
                    ],
                    [
                        'label' => 'Master Data Anchor',
                        'icon' => 'anchor',
                        'url' => ['/master-anchor/index'],
                        'visible' => UserAccess::hasPermission('akses_anchor')
                    ],
                    [
                        'label' => 'Master Data Kategori',
                        'icon' => 'category',
                        'url' => ['/master-kategori/index'],
                        'visible' => UserAccess::hasPermission('akses_kategori')
                    ],
                    [
                        'label' => 'Manajemen User',
                        'icon' => 'account_circle', 
                        'url' => ['/role-permission/index'],
                        'visible' => UserAccess::hasPermission('akses_manajemen')
                    ],
                ],
                'visible' => (
                    UserAccess::hasPermission('akses_user') ||
                    UserAccess::hasPermission('akses_jabatan') ||
                    UserAccess::hasPermission('akses_departement') ||
                    UserAccess::hasPermission('akses_kriteria') ||
                    UserAccess::hasPermission('akses_anchor') ||
                    UserAccess::hasPermission('akses_kategori') ||
                    UserAccess::hasPermission('akses_manajemen') 
                )
            ],
            [
                'label' => 'Master Penilaian',
                'icon' => 'assignment',
                'items' => [
                    [
                        'label' => 'Penilaian Karyawan',
                        'icon' => 'assignment_ind',
                        'url' => ['/master-penilaian/index'],
                        'visible' => UserAccess::hasPermission('akses_penilaian')
                    ],
                    [
                        'label' => 'Laporan Penilaian',
                        'icon' => 'analytics',
                        'url' => ['/penilaian/index'],
                        'visible' => UserAccess::hasPermission('akses_laporan')
                    ],
                ],
                'visible' => (
                    UserAccess::hasPermission('akses_penilaian') ||
                    UserAccess::hasPermission('akses_laporan')
                )
            ],
            [
                'label' => 'Event',
                'icon' => 'event',
                'items' => [
                    [
                        'label' => 'Event',
                        'icon' => 'event',
                        'url' => ['/master-event/index'],
                        'visible' => UserAccess::hasPermission('akses_event')
                    ],
                ],
                'visible' => UserAccess::hasPermission('akses_event')
            ],
            [
                'label' => 'Banding Penilaian',
                'icon' => 'pending_actions',
                'items' => [
                    [
                        'label' => 'Master Banding Penilaian',
                        'icon' => 'assignment',
                        'url' => ['/banding-penilaian/index'],
                        'visible' => UserAccess::hasPermission('akses_banding')
                    ],
                    [
                        'label' => 'Banding Penilaian',
                        'icon' => 'content_paste_search',
                        'url' => ['/pengajuan-banding/index'],
                        'visible' => UserAccess::hasPermission('akses_banding')
                    ],
                ],
                'visible' => UserAccess::hasPermission('akses_banding')
            ],
        ]
    ]); ?>
    </div>
</div>
