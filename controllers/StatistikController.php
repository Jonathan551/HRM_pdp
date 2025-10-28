<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\models\MasterDepartement; 
use app\controllers\BaseController;
class StatistikController extends BaseController
{
  
    public function actionIndex($method = 'minmax', $basis = 'perdept')
    {
        $req    = Yii::$app->request;
        $deptA  = (int)$req->get('deptA');
        $deptB  = (int)$req->get('deptB');
        $katId  = $req->get('id_kategori');
        $start  = $req->get('start');
        $end    = $req->get('end');

        $part = ($basis === 'perdept') ? 'PARTITION BY u.id_departement' : '';

        if ($method === 'zscore') {
            $normExpr = new Expression("
                CASE
                WHEN (STDDEV_SAMP(p.nilai_akhir) OVER ($part)) = 0 THEN 0
                ELSE (p.nilai_akhir - AVG(p.nilai_akhir) OVER ($part))
                    / NULLIF(STDDEV_SAMP(p.nilai_akhir) OVER ($part), 0)
                END AS norm_value
            ");
            $normLabel = 'Z-score';
        } else {
            $normExpr = new Expression("
                CASE
                WHEN (MAX(p.nilai_akhir) OVER ($part) - MIN(p.nilai_akhir) OVER ($part)) = 0 THEN 1
                ELSE (p.nilai_akhir - MIN(p.nilai_akhir) OVER ($part))
                    / NULLIF(MAX(p.nilai_akhir) OVER ($part) - MIN(p.nilai_akhir) OVER ($part), 0)
                END AS norm_value
            ");
            $normLabel = 'Min–Max (0–1)';
        }

        $base = (new Query())
            ->select([
                'p.id_penilaian',
                'p.id_users',
                'u.nama',
                'u.id_departement',
                'd.nama_departement',
                'p.nilai_akhir',
                $normExpr,
            ])
            ->from(['p' => 'master_penilaian'])
            ->innerJoin(['u' => 'users'], 'u.id_users = p.id_users')
            ->leftJoin(['d' => 'master_departement'], 'd.id_departement = u.id_departement');

        if ($katId) $base->andWhere(['p.id_kategori' => $katId]);
        if ($start) $base->andWhere(['>=', 'p.periode_awal',  $start]);
        if ($end)   $base->andWhere(['<=', 'p.periode_akhir', $end]);

        $picked = array_filter([$deptA ?: null, $deptB ?: null], fn($v) => $v !== null);
        if (!empty($picked)) $base->andWhere(['u.id_departement' => $picked]);

        $rows = $base->all();

        if (!$deptA || !$deptB) {
            return $this->render('index', [
                'normLabel'  => $normLabel,
                'basis'      => $basis,
                'method'     => $method,
                'deptA'      => $deptA,
                'deptB'      => $deptB,
                'rows'       => [],
                'summary'    => [],
                'top5'       => [],
                'bottom5'    => [],
                'labels'     => [],
                'datasetA'   => [],
                'datasetB'   => [],
                'departemenList' => ArrayHelper::map(
                    MasterDepartement::find()->orderBy('nama_departement')->all(),
                    'id_departement', 'nama_departement'
                ),
            ]);
        }

        // ---- olah data per departemen ----
        $byDept = [];
        foreach ($rows as $r) {
            $byDept[$r['id_departement']]['dept_name'] = $r['nama_departement'];
            $byDept[$r['id_departement']]['items'][]   = $r;
        }

        $summary = $top5 = $bottom5 = [];
        foreach ($byDept as $deptId => $bucket) {
            $items   = $bucket['items'];
            $nRows   = count($items);
            $nPeople = count(array_unique(array_column($items, 'id_users')));
            $avgRaw  = $nRows ? array_sum(array_column($items, 'nilai_akhir')) / $nRows : 0.0;
            $avgNorm = $nRows ? array_sum(array_map('floatval', array_column($items, 'norm_value'))) / $nRows : 0.0;

            $std = 0.0;
            if ($nRows > 1) {
                $mean = $avgRaw; $sumVar = 0.0;
                foreach ($items as $it) $sumVar += pow($it['nilai_akhir'] - $mean, 2);
                $std = sqrt($sumVar / ($nRows - 1));
            }

            $sorted = $items;
            usort($sorted, fn($a,$b) => $b['norm_value'] <=> $a['norm_value']);
            $top5[$deptId]    = array_slice($sorted, 0, 5);
            $bottom5[$deptId] = array_slice(array_reverse($sorted), 0, 5);

            $summary[$deptId] = [
                'dept'     => $bucket['dept_name'],
                'n_rows'   => $nRows,
                'n_people' => $nPeople,
                'avg_raw'  => $avgRaw,
                'std_raw'  => $std,
                'avg_norm' => $avgNorm,
                'min_raw'  => $nRows ? min(array_column($items, 'nilai_akhir')) : null,
                'max_raw'  => $nRows ? max(array_column($items, 'nilai_akhir')) : null,
            ];
        }

        $labels   = ['Rata-rata Normalisasi', 'Rata-rata Nilai'];
        $datasetA = [round($summary[$deptA]['avg_norm'] ?? 0, 3), round($summary[$deptA]['avg_raw'] ?? 0, 3)];
        $datasetB = [round($summary[$deptB]['avg_norm'] ?? 0, 3), round($summary[$deptB]['avg_raw'] ?? 0, 3)];

        return $this->render('index', [
            'normLabel'  => $normLabel,
            'basis'      => $basis,
            'method'     => $method,
            'deptA'      => $deptA,
            'deptB'      => $deptB,
            'rows'       => $rows,
            'summary'    => $summary,
            'top5'       => $top5,
            'bottom5'    => $bottom5,
            'labels'     => $labels,
            'datasetA'   => $datasetA,
            'datasetB'   => $datasetB,
            'departemenList' => ArrayHelper::map(
                MasterDepartement::find()->orderBy('nama_departement')->all(),
                'id_departement', 'nama_departement'
            ),
        ]);
    }
}
