<?php
namespace app\components;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;

class NotificationService
{
    public static function fire(
        string $aksi,
        ActiveRecord $model,
        int|array $targets,
        ?string $judul = null,
        ?string $deskripsi = null,
        ?string $overridePk = null
    ): void {
        $targets = is_array($targets) ? $targets : [$targets];
        if (!in_array($aksi, ['create','update','delete'], true)) return;

        $class = get_class($model);
        $pk    = $overridePk ?? (string)$model->getPrimaryKey();
        $kode  = $model->kode ?? $pk;

        if ($judul === null || $deskripsi === null) {
            [$j, $d] = self::defaultText($aksi, $class, $kode, $model);
            $judul     = $judul     ?? $j;
            $deskripsi = $deskripsi ?? $d;
        }

        $rows = [];
        foreach ($targets as $uid) {
            $uid = (int)$uid;
            if ($uid <= 0) continue;
            $rows[] = [
                'target_id_user' => $uid,
                'judul'          => $judul,
                'deskripsi'      => $deskripsi,
                'model_class'    => $class,
                'model_pk'       => $pk,
                'aksi'           => $aksi,
                'dibaca'         => 0,
                'created_at'     => new Expression('CURRENT_TIMESTAMP'),
            ];
        }

        if ($rows) {
            Yii::$app->db->createCommand()->batchInsert(
                '{{%notification}}',
                ['target_id_user','judul','deskripsi','model_class','model_pk','aksi','dibaca','created_at'],
                array_map(fn($r)=>[
                    $r['target_id_user'],$r['judul'],$r['deskripsi'],$r['model_class'],
                    $r['model_pk'],$r['aksi'],$r['dibaca'],$r['created_at']
                ], $rows)
            )->execute();
        }
    }

    private static function modelLabel(ActiveRecord $model): string
    {
        // mapping khusus (tambahkan sesuai kebutuhan)
        if ($model instanceof \app\models\MasterPenilaian) {
            return 'Laporan penilaian';
        }

        // fallback: pecah CamelCase -> "Master Penilaian"
        $short = (new \ReflectionClass($model))->getShortName();
        return trim(preg_replace('/(?<!^)([A-Z])/', ' $1', $short));
    }


    public static function fireCreate(ActiveRecord $m, int|array $targets, ?string $j=null, ?string $d=null): void
    { self::fire('create', $m, $targets, $j, $d); }

    public static function fireUpdate(ActiveRecord $m, int|array $targets, ?string $j=null, ?string $d=null): void
    { self::fire('update', $m, $targets, $j, $d); }

    public static function fireDelete(ActiveRecord $m, int|array $targets, ?string $j=null, ?string $d=null, ?string $pk=null): void
    { self::fire('delete', $m, $targets, $j, $d, $pk); }

    private static function defaultText(string $aksi, string $class, string $kode, ?ActiveRecord $model = null): array
    {
        $label = $model ? self::modelLabel($model) : 'Data';

        return match ($aksi) {
            'create' => ['Laporan penilaian dibuat',    "{$label} {$kode} telah dibuat."],
            'update' => ['Laporan penilaian diperbarui',"{$label} {$kode} telah diperbarui."],
            'delete' => ['Laporan penilaian dihapus',   "{$label} {$kode} telah dihapus."],
            default  => ['Notifikasi',                  "{$label} {$kode}"],
        };
    }

    public static function unreadCount(int $userId): int
    {
        return (int)Yii::$app->db->createCommand("
            SELECT COUNT(*) FROM {{%notification}}
            WHERE target_id_user=:u AND dibaca=0
        ", [':u'=>$userId])->queryScalar();
    }

    public static function latest(int $userId, int $limit=20): array
    {
        return Yii::$app->db->createCommand("
            SELECT id, judul, deskripsi, model_class, model_pk, aksi, dibaca, created_at
            FROM {{%notification}}
            WHERE target_id_user=:u
            ORDER BY created_at DESC
            LIMIT {$limit}
        ", [':u'=>$userId])->queryAll();
    }

    public static function markRead(int $id, int $userId): void
    {
        Yii::$app->db->createCommand()->update('{{%notification}}', ['dibaca'=>1], [
            'id' => $id,
            'target_id_user' => $userId,
        ])->execute();
    }

    public static function markReadAll(int $userId): void
    {
        Yii::$app->db->createCommand()->update('{{%notification}}', ['dibaca'=>1], [
            'target_id_user' => $userId,
            'dibaca' => 0,
        ])->execute();
    }
}
