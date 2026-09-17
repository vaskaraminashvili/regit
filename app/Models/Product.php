<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'in_stock' => 'boolean',
            'status' => 'boolean',
            'price' => 'integer',
        ];
    }

    public function unitPrice(bool $withInstallation = false): int
    {
        return (int) $this->price + ($withInstallation ? (int) config('shop.installation_fee') : 0);
    }

    protected function getMediaCollectionName(): string
    {
        return 'products';
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
