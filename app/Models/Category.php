<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ImageHelper;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'icon',
        'image',
        'bg_color',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the smart image URL.
     */
    public function getImageUrlAttribute(): string
    {
        return ImageHelper::getUrl($this->image);
    }
}
