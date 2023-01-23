<?php

namespace App\Nova;

use Wame\Utils\Helpers\Strings;

abstract class BaseResource extends Resource
{
    /** {@inheritdoc} */
    public static $perPageViaRelationship = 50;

    /** {@inheritdoc} */
    public static $perPageOptions = [25, 50, 100, 150, 250, 500];

    /**
     * Get Reource name from class
     *
     * @return string
     */
    public static function resourceName()
    {
        return class_basename(static::class);
    }

    /** {@inheritDoc} */
    public static function singularLabel()
    {
        return Strings::camelCaseConvert(static::resourceName());
    }

    /** {@inheritDoc} */
    public static function label()
    {
        return __(strtolower(static::singularLabel()) . '.label');
    }

    /** {@inheritdoc} */
    public static function createButtonLabel()
    {
        return __(strtolower(static::singularLabel()) . '.create.button');
    }

    /** {@inheritdoc} */
    public static function updateButtonLabel()
    {
        return __(strtolower(static::singularLabel()) . '.update.button');
    }

    /** {@inheritdoc} */
    public function title()
    {
        if (is_array(static::$title)) {
            $return = '';

            foreach (static::$title as $item) {
                if (str_contains($item, '->')) {
                    $val = $this;
                    foreach (explode('->', $item) as $itm) {
                        $val = $val?->{$itm};
                        if ($val === null) break;
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
