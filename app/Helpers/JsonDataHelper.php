<?php

namespace App\Helpers;

/**
 * Lee un fichero JSON de datos semilla y lo decodifica en un array.
 */
class JsonDataHelper
{
    public static function readJsonData(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return [];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }
}
