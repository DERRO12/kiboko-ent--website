<?php

namespace App\Support;

class FieldOptions
{
    /** Options for select / belongs / many fields defined in config/cms.php. */
    public static function for(array $f): array
    {
        if ($f['type'] === 'select') {
            return $f['opt'] ?? [];
        }
        [$model, $label] = $f['opt'];

        return $model::query()->orderBy($label)->pluck($label, 'id')->all();
    }
}
