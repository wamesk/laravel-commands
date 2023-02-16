<?php

declare(strict_types = 1);

namespace Wame\LaravelCommands\Utils;

class Helpers
{
    /**
     * Create dir recursive
     *
     * @param string $dir
     * @param int $chmod permission
     *
     * @return string
     */
    public static function createDir(string $dir, int $chmod = 0777): string
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, app_path($dir));
        if (!file_exists($path)) {
            mkdir($path, $chmod, true);
        }
        return $path;
    }

    /**
     * @param string $modelName
     * @param string $eventName
     * @return false|resource
     */
    public static function createFile(string $path, string $mode = 'w')
    {
        return fopen(app_path($path), $mode);
    }
    /**
     * Covert camel case
     *
     * @param string $string
     * @param string $separator
     * @param boolean $lower
     *
     * @return string
     */
    public static function camelCaseConvert(string $string, string $separator = '_', bool $lower = true): string
    {
        if (empty($string)) {
            return $string;
        }

        $string = lcfirst($string);
        $string = preg_replace('/[A-Z]/', $separator . '$0', $string);

        return $lower ? mb_strtolower($string) : $string;
    }
}
