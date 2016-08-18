<?php
namespace Codex\Addon\Phpdoc;

use Laradic\Support\Str;

final class Util
{

    public static function toFileName($fullName, $ext = '')
    {
        $fileName = str_replace('\\', '.', $fullName);
        $fileName = Str::removeLeft($fileName, '.');
        if ( $ext ) {
            $fileName .= $ext;
        }
        return $fileName;
    }

    public static function exportArray(array $array)
    {
        return '<?php return ' . var_export($array, true) . ';';
    }

}
