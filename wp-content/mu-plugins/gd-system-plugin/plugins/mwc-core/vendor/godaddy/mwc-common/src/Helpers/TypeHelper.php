<?php

namespace GoDaddy\WordPress\MWC\Common\Helpers;

/**
 * An helper for handling strict types.
 */
class TypeHelper
{
    /**
     * Returns the string value or default.
     *
     * @param mixed $value
     * @param string $default
     * @return string
     */
    public static function string($value, string $default) : string
    {
        return is_string($value) ? $value : $default;
    }

    /**
     * Returns the array value or default.
     *
     * @param mixed $value
     * @param array<mixed> $default
     * @return array<mixed>
     */
    public static function array($value, array $default) : array
    {
        return ArrayHelper::accessible($value) ? $value : $default;
    }

    /**
     * Returns the integer value or default.
     *
     * @param mixed $value
     * @param int $default
     * @return int
     */
    public static function int($value, int $default) : int
    {
        return is_int($value) || (is_numeric($value) && ctype_digit((string) $value)) ? (int) $value : $default;
    }

    /**
     * Returns the float value or default.
     *
     * @param mixed $value
     * @param float $default
     * @return float
     */
    public static function float($value, float $default) : float
    {
        return is_numeric($value) ? (float) $value : $default;
    }

    /**
     * Returns the boolean value or default.
     *
     * @param mixed $value
     * @param bool $default
     * @return bool
     */
    public static function bool($value, bool $default) : bool
    {
        return is_bool($value) ? $value : $default;
    }

    /**
     * Returns the scalar value or default.
     *
     * @param mixed $value
     * @param bool|float|int|string $default
     * @return bool|float|int|string
     */
    public static function scalar($value, $default)
    {
        return is_scalar($value) ? $value : $default;
    }
}
