<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsNotesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_selected_product_when_requested(): void
    {
        $product = Product::factory()->create([
            'name' => 'Producto de prueba',
        ]);

        $response = $this->getJson('/api/products/notes?selected=' . $product->id);

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => 'Producto de prueba',
        ]);
    }
}
