<?php

namespace App\Imports;

use App\Classes\Images;
use App\Models\Picture;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Validators\Failure;

class PicturesImport implements WithCustomCsvSettings, WithStartRow, ToCollection, SkipsEmptyRows, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * Work with a collection
     *
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $id => $row) {
            if (!$this->isValidRow($id, $row)) {
                continue;
            }

            $title = $row[0];
            $imagePath = Images::store(trim($row[1]));
            $description = $row[2];
            $imageExif = '';

            if (!empty($imagePath)) {
                $imageExif = json_encode(Images::readExifData($imagePath));
            }

            if ($picture = Picture::where('url', '=', $imagePath)->first()) {
                $picture->title = $title;
                $picture->url = $imagePath;
                $picture->description = $description;
                $picture->exif = $imageExif;
                $picture->save();
            } else {
                Picture::create([
                    'title' => $title,
                    'url' => $imagePath,
                    'description' => $description,
                    'exif' => $imageExif,
                ]);
            }
        }
    }

    /**
     * Some validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '0' => 'required',
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

    /**
     * Validate the row
     *
     * @param integer $rowId
     * @param Collection $row
     * @return boolean
     */
    private function isValidRow(int $rowId, Collection $row): bool
    {
        if (empty($row[0])) {
            $this->onFailure(new Failure(
                $rowId,
                'Picture Title',
                ['Title cannot be empty'],
                $row->toArray()
            ));

            return false;
        }

        if (!Images::exists(trim($row[1]))) {
            $this->onFailure(new Failure(
                $rowId,
                'Picture Url',
                ['Image URL is not accessible.'],
                $row->toArray()
            ));

            return false;
        }

        return true;
    }
}
