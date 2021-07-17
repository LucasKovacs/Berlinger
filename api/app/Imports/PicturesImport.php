<?php

namespace App\Imports;

use App\Classes\Images;
use App\Models\Picture;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PicturesImport implements ToModel, WithBatchInserts, WithValidation, WithCustomCsvSettings, WithStartRow, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): Picture
    {
        $title = $row[0];
        $imagePath = Images::store(trim($row[1]));
        $description = $row[2];
        $imageExif = '';

        if (!empty($imagePath)) {
            $imageExif = json_encode(Images::readExifData($imagePath));
        }

        return new Picture([
            'title' => $title,
            'url' => $imagePath,
            'description' => $description,
            'exif' => $imageExif,
        ]);
    }

    /**
     * Some validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '1' => function ($attribute, $value, $onFailure) {
                if (is_null($value)) {
                    $value = '';
                }

                if (!Images::exists(trim($value))) {
                    $onFailure('Picture does not exist');
                }
            },
        ];
    }

    /**
     * Limit the amount of inserts
     *
     * @return integer
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Set the start row on 2, so we ignore the header
     *
     * @return integer
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * We define here our CSV settings
     *
     * @return array
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => '|',
        ];
    }
}
