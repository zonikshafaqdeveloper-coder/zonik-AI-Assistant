<?php

namespace App\Services;

use App\Models\CustomerPrice;
use App\Models\Product;
use App\Models\User;

class OrderableProductValidator
{
    /**
     * Universal authorization boundary for every cartable SKU.
     * Catalogue presence is discovery only; a positive outlet CustomerPrice
     * row is the authority for both approval and price.
     */
    public function validate(?User $customer, ?int $outletId, int $productId): array
    {
        if (!$customer || !$outletId) {
            return $this->rejected($productId, 'OUTLET_NOT_SELECTED');
        }

        $outlet = User::where('id', $outletId)
            ->where('priority', $customer->id)
            ->where('type', 'outlet')
            ->where('verified_status', 'verified')
            ->first();
        if (!$outlet) return $this->rejected($productId, 'OUTLET_NOT_AUTHORIZED');

        $product = Product::where('id', $productId)->where('status', 'active')->first();
        if (!$product) return $this->rejected($productId, 'PRODUCT_INACTIVE_OR_MISSING');

        $approvedPrice = CustomerPrice::where('outlet_id', $outlet->id)
            ->where('product_id', $product->id)
            ->value('product_price');
        if (!$this->isApprovedPrice($approvedPrice)) {
            return $this->rejected($productId, 'PRODUCT_NOT_APPROVED', $product);
        }

        return [
            'approved' => true,
            'reason' => null,
            'customer_id' => (int) $customer->id,
            'outlet_id' => (int) $outlet->id,
            'sku' => (int) $product->id,
            'product' => $product,
            'price' => (float) $approvedPrice,
            // Stock is intentionally unknown until an authoritative stock
            // source is supplied; catalogue/approval never implies stock.
            'availability' => null,
        ];
    }

    public function isApprovedPrice($approvedPrice): bool
    {
        return is_numeric($approvedPrice) && (float) $approvedPrice > 0;
    }

    private function rejected(int $productId, string $reason, ?Product $product = null): array
    {
        return ['approved' => false, 'reason' => $reason, 'sku' => $productId,
            'product' => $product, 'price' => null, 'availability' => null];
    }
}
