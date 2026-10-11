<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'sku', 'name', 'price', 'stock', 'minimum_stock'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
            ->withPivot('supplier_price');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Business rule: badge low stock when stock <= minimum_stock
    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }
}
