<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UuidModel extends Model
{
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) self::generateUuid();
            }
        });
    }

    /**
     * Disable auto-incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Set the key type to string.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Generate a UUID.
     *
     * @return string
     */
    public static function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Check if a string is a UUID.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isUuid($value): bool
    {
        return preg_match('/^[a-f0-9-]{36}$/i', $value) === 1;
    }
}
