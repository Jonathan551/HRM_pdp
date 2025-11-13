<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;

class DashboardUserController extends Controller
{
    public function actionUser()
    {
        $uid = Yii::$app->user->identity->id_users ?? null;
        if (!$uid) { return $this->redirect(['/site/login']); }

        $myAvg = (float)((new Query())
            ->from('master_penilaian mp')
            ->where(['mp.id_users' => $uid])
            ->average('mp.nilai_akhir')) ?? 0;
        $rows = (new Query())
            ->select([
                'label' => new \yii\db\Expression("COALESCE(mk.nama_kategori, 'Tidak Terkategori')"),
                'value' => new \yii\db\Expression('COUNT(*)'),
            ])
            ->from(['mp' => 'master_penilaian'])
            ->leftJoin(
                ['mk' => 'master_kategori'],
                'CAST(mp.nilai_akhir AS DECIMAL(10,3)) BETWEEN mk.nilai_min AND mk.nilai_max'
            )
            ->where(['mp.id_users' => $uid])  
            ->groupBy(['mk.id_kategori','mk.nama_kategori'])
            ->orderBy(['mk.nilai_min' => SORT_ASC])
            ->all();
        $myKategori = $rows;
        $myLatestPenilaian = (new Query())
            ->select([
                'mp.id_penilaian',
                'mp.nilai_akhir',
                'p.nama            AS periode_nama',
                'p.tanggal_mulai   AS periode_mulai',
                'p.tanggal_selesai AS periode_selesai',
            ])
            ->from(['mp' => 'master_penilaian'])
            ->innerJoin(['p' => 'master_periode'], 'p.id_periode = mp.id_periode')
            ->where(['mp.id_users' => $uid])
            ->orderBy([
                'p.tanggal_selesai' => SORT_DESC,
                'p.tanggal_mulai'   => SORT_DESC,
                'mp.id_penilaian'   => SORT_DESC,
            ])
            ->limit(5)
            ->all();

        return $this->render('user', [
            'myAvg'             => $myAvg,
            'myKategori'        => $myKategori,
            'myLatestPenilaian' => $myLatestPenilaian,
        ]);
    }
}
