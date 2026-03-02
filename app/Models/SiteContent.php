<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['key', 'page', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Get the value array for a given key.
     */
    public static function get(string $key, array $default = []): array
    {
        $record = static::where('key', $key)->first();
        return $record ? ($record->value ?? $default) : $default;
    }

    /**
     * Set (create or update) a key with its value.
     */
    public static function set(string $key, array $value, string $page = 'home'): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'page' => $page]
        );
    }

    /**
     * Get a single field from a key's JSON value.
     */
    public static function field(string $key, string $field, mixed $default = null): mixed
    {
        $data = static::get($key);
        return $data[$field] ?? $default;
    }
}
