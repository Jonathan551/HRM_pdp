<?php
namespace app\models;

use yii\base\Model;
use app\models\MasterAnchor;

class BatchAnchorInput extends Model
{
    public $id_departement;
    public $id_kriteria;
    public $skala;

    public $anchors = [];

    public function rules()
    {
        return [
            [['id_departement', 'id_kriteria', 'skala'], 'required'],
            [['id_departement', 'id_kriteria', 'skala'], 'integer'],

            // anchors itu harus ada
            ['anchors', 'required', 'message' => 'Mohon isi nilai anchor'],
            ['anchors', 'validateAnchors'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_departement' => 'Departemen',
            'id_kriteria'    => 'Nama Kriteria',
            'skala'          => 'Skala (Jumlah Level)',
        ];
    }

    public function validateAnchors($attribute)
    {
        if (!is_array($this->anchors) || empty($this->anchors)) {
            $this->addError($attribute, 'Tidak ada data level anchor.');
            return;
        }

        if (count($this->anchors) != (int)$this->skala) {
            $this->addError($attribute, 'Jumlah level tidak sesuai skala.');
        }

        $seenLevels = [];

        $existingLevels = [];
        if ($this->id_kriteria) {
            $existingInDb = MasterAnchor::find()
                ->select(['level_anchor'])
                ->where(['id_kriteria' => $this->id_kriteria])
                ->asArray()
                ->all();

            foreach ($existingInDb as $rowDb) {
                $existingLevels[] = (int)$rowDb['level_anchor'];
            }
        }

        $maxExisting = empty($existingLevels) ? 0 : max($existingLevels);

        foreach ($this->anchors as $row) {
            if (
                !isset($row['level_anchor']) ||
                !isset($row['deskripsi']) ||
                !isset($row['nilai_anchor']) ||
                $row['deskripsi'] === '' ||
                $row['nilai_anchor'] === ''
            ) {
                $this->addError(
                    $attribute,
                    'Semua kolom deskripsi dan nilai anchor wajib diisi.'
                );
                break;
            }

            $levelVal = (int)$row['level_anchor'];

            if (in_array($levelVal, $seenLevels, true)) {
                $this->addError(
                    $attribute,
                    'Level '.$levelVal.' muncul lebih dari satu kali dalam input.'
                );
                break;
            }
            $seenLevels[] = $levelVal;
            if (!preg_match('/^\d+(\.\d{1,3})?$/', (string)$row['nilai_anchor'])) {
                $this->addError(
                    $attribute,
                    'Nilai Anchor harus angka dengan maksimal 3 angka di belakang koma.'
                );
                break;
            }

            $numericVal = (float)$row['nilai_anchor'];
            if ($numericVal > (float)$this->skala) {
                $this->addError(
                    $attribute,
                    'Nilai Anchor level '.$levelVal.' (' . $numericVal . ') tidak boleh lebih besar dari skala (' . $this->skala . ').'
                );
                break;
            }

            if (in_array($levelVal, $existingLevels, true)) {
                $this->addError(
                    $attribute,
                    'Level '.$levelVal.' sudah ada untuk kriteria ini. Tidak boleh duplikat.'
                );
                break;
            }

            if ($maxExisting > 0 && $levelVal <= $maxExisting) {
                $this->addError(
                    $attribute,
                    'Level '.$levelVal.' sudah didefinisikan sebelumnya. ' .
                    'Hanya level baru di atas level '.$maxExisting.' yang boleh ditambahkan.'
                );
                break;
            }
        }
    }
}
