<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use HasFactory;

    use HasFactory;

protected $fillable = [
        'unique_reference_id',
        'product_name',
        'hsn_code',
        'description',
        'product_quantity',
        'product_mrp',
        'supplier_traced',
        'vendor_id',
        'category_id',
        'subcategory_id',
        'slug',
        'brands',
        'brand_id',
        'types',
        'unit',
        'peices_per_pack',
        'carton_size',
        'varieties',
        'cost_per_item',
        'gst',
        'sale_price_loose_pcs',
        'sale_price_carton',
        'sale_price_loose_pcs_old',
        'sale_price_carton_old',
        'product_weight_grams',
        'loose_discount_basic',
        'carton_discount_basic',
        'total_discount',
        'status',
        'tags',
        'image',
        'sgst',
        'cgst',
        'igst',
        'cess',
        'total_with_tax',
        'last_update_price',
    ];

    protected $casts = [
        'product_quantity' => 'decimal:2',
        'product_mrp' => 'decimal:2',
        'cost_per_item' => 'decimal:2',
        'sale_price_loose_pcs' => 'decimal:2',
        'sale_price_carton' => 'decimal:2',
        'sale_price_loose_pcs_old' => 'decimal:2',
        'sale_price_carton_old' => 'decimal:2',
        'product_weight_grams' => 'decimal:2',
        'loose_discount_basic' => 'decimal:2',
        'carton_discount_basic' => 'decimal:2',
        'peices_per_pack' => 'integer',
        'carton_size' => 'decimal:2',
        'brand_id' => 'integer',
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function scopeFilterBySubcategory($query, $subcategory_id)
    {
        return $query->where('subcategory_id', $subcategory_id);
    }
    
     public function stock()
    {
        return $this->hasOne(ProductStock::class, 'product_id');
    }
    
     public function rackStocks()
    {
        return $this->hasMany(RackStock::class, 'product_id');
    }
    
    public function units()
    {
        return $this->hasMany(ProductUnit::class);
    }
    
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function scopeFilterByBrandCategory($query, $brand_category_id)
    {
        return $query->whereIn('brand_id', function ($query) use ($brand_id) {
            $query->select('id')
                ->from('brands')
                ->where('id', $brand_id);
        });
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!isset($model->slug)) {
                $model->slug = \Str::slug($model->slug, '-');
            }
        });
    }
}
