<?php

declare(strict_types = 1);

namespace App\Nova;

use Wame\Utils\Helpers\Strings;

abstract class BaseResource extends Resource
{
    /**
     * {@inheritdoc}
     */
    public static $perPageViaRelationship = 50;

    /**
     * {@inheritdoc}
     */
    public static $perPageOptions = [25, 50, 100, 150, 250, 500];

    /**
     * Get Resource name from class
     *
     * @return string
     */
    public static function resourceName(): string
    {
        return class_basename(static::class);
    }

    /**
     * Get Resource singluar name
     *
     * @return string
     */
    public static function singular(): string
    {
        return mb_strtolower(Strings::camelCaseConvert(static::resourceName()));
    }

    /**
     * {@inheritDoc}
     */
    public static function singularLabel(): string
    {
        return __(static::singular() . '.singular');
    }

    /**
     * {@inheritDoc}
     */
    public static function label(): string
    {
        return __(static::singular() . '.label');
    }

    /**
     * {@inheritdoc}
     */
    public static function createButtonLabel(): string
    {
        return __(static::singular() . '.create.button');
    }

    /**
     * {@inheritdoc}
     */
    public static function updateButtonLabel(): string
    {
        return __(static::singular() . '.update.button');
    }

    /**
     * {@inheritdoc}
     */
    public function title(): string
    {
        if (is_array(static::$title)) {
            $return = '';

            foreach (static::$title as $item) {
                if (str_contains($item, '->')) {
                    $val = $this;
                    foreach (explode('->', $item) as $itm) {
                        $val = $val?->{$itm};
                        if (null === $val) {
                            break;
                        }
                    }
                    $return .= $val;
                } else {
                    $value = data_get($this, $item);
                    $return .= $value ? (string) $value : $item;
                }
            }

            return $return;
        }

        return (string) data_get($this, static::$title);
    }
}
