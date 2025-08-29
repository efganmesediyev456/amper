<?php

namespace App\Http\Resources;

use App\Http\Resources\Products\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class SessionComparisonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Basic product information using the existing ProductResource
        $productData = (new ProductResource($this->resource))->toArray($request);
        
        // Get the category ID
        $categoryId = $this->category_id;
        
        // Get the session ID
        $sessionId = $this->getSessionIdentifier($request);
        
        // Get the grouped properties for comparison
        $comparisonData = $this->getComparisonData($categoryId, $sessionId);
        
        // Merge with product data
        return array_merge($productData, $comparisonData);
    }
    
    /**
     * Get comparison data with similar and different properties
     *
     * @param int $categoryId
     * @param string $sessionId
     * @return array
     */
    private function getComparisonData(int $categoryId, string $sessionId): array
    {
        // Get all properties of the current product
        $currentProductProperties = $this->subProperties->map(function ($property) {
            return [
                'property_id' => $property->property_id,
                'property_name' => $property->property->title,
                'value' => $property->title,
            ];
        })->keyBy('property_id');
        
        // Get product IDs from session
        $sessionKey = 'comparisons_' . $sessionId;
        $sessionProductIds = Session::get($sessionKey, []);
        
        if (empty($sessionProductIds)) {
            return [
                'similar_properties' => [],
                'different_properties' => $currentProductProperties->values()->toArray(),
            ];
        }
        
        // Filter out current product
        $otherProductIds = array_filter($sessionProductIds, function($id) {
            return $id != $this->id;
        });
        
        if (empty($otherProductIds)) {
            return [
                'similar_properties' => [],
                'different_properties' => $currentProductProperties->values()->toArray(),
            ];
        }
        
        // Get other products in the same category
        $categoryProducts = \App\Models\Product::whereIn('id', $otherProductIds)
            ->where('category_id', $categoryId)
            ->with(['subProperties.property'])
            ->get();
        
        if ($categoryProducts->isEmpty()) {
            return [
                'similar_properties' => [],
                'different_properties' => $currentProductProperties->values()->toArray(),
            ];
        }
        
        $allPropertiesInCategory = $this->collectPropertiesFromProducts($categoryProducts);
        
        $similarProperties = [];
        $differentProperties = [];
        
        foreach ($currentProductProperties as $propertyId => $property) {
            $isPropertySimilar = true;
            
            foreach ($categoryProducts as $product) {
                $productProperty = $product->subProperties->firstWhere('property_id', $propertyId);
                
                if (!$productProperty || $productProperty->title !== $property['value']) {
                    $isPropertySimilar = false;
                    break;
                }
            }
            
            if ($isPropertySimilar) {
                $similarProperties[] = $property;
            } else {
                $differentProperties[] = $property;
            }
        }
        
        $allPropertyIds = $allPropertiesInCategory->keys()->toArray();
        $currentPropertyIds = $currentProductProperties->keys()->toArray();
        $missingPropertyIds = array_diff($allPropertyIds, $currentPropertyIds);
        
        foreach ($missingPropertyIds as $propertyId) {
            $propertyData = $allPropertiesInCategory->get($propertyId);
            
            foreach ($propertyData['values'] as $productId => $valueData) {
                $differentProperties[] = [
                    'property_id' => $valueData['subproperty_id'],
                    'property_name' => $propertyData['property_name'],
                    'value' => $valueData['value'],
                ];
                break;
            }
        }
        
        return [
            'similar_properties' => $similarProperties,
            'different_properties' => $differentProperties,
        ];
    }
    
    private function collectPropertiesFromProducts(Collection $products): Collection
    {
        $allProperties = collect();
        
        foreach ($products as $product) {
            foreach ($product->subProperties as $property) {
                if (!$allProperties->has($property->property_id)) {
                    $allProperties->put($property->property_id, [
                        'property_id' => $property->property_id,
                        'property_name' => $property->property->title,
                        'values' => []
                    ]);
                }
            }
        }
        
        foreach ($products as $product) {
            foreach ($product->subProperties as $property) {
                $propertyId = $property->property_id;
                
                $propertyData = $allProperties->get($propertyId);
                
                $propertyData['values'][$product->id] = [
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'value' => $property->title,
                    'subproperty_id' => $property->id
                ];
                
                $allProperties->put($propertyId, $propertyData);
            }
        }
        
        return $allProperties;
    }
    
    private function getSessionIdentifier(Request $request): string
    {
        $ipAddress = $request->ip();
        $sessionKey = 'comparison_session_' .$ipAddress;
        return $sessionKey;
    }
}