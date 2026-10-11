<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_database_seeder_populates_initial_data(): void
    {
        $this->assertDatabaseHas('users', ['username' => 'admin', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['username' => 'staff', 'role' => 'staff']);
        $this->assertGreaterThan(0, Category::count());
        $this->assertGreaterThan(0, Supplier::count());
        $this->assertGreaterThan(0, Product::count());
        $this->assertGreaterThan(0, StockMovement::count());
    }

    public function test_user_authentication(): void
    {
        // Login with correct credentials
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        // Logout
        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        // Login with wrong credentials
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'wrong_pass',
        ]);
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $staff = User::where('username', 'staff')->first();

        $response = $this->actingAs($staff)->get('/users');
        $response->assertForbidden();
    }

    public function test_admin_can_access_all_management_pages(): void
    {
        $admin = User::where('username', 'admin')->first();

        $this->actingAs($admin)->get('/')->assertOk();
        $this->actingAs($admin)->get('/products')->assertOk();
        $this->actingAs($admin)->get('/categories')->assertOk();
        $this->actingAs($admin)->get('/suppliers')->assertOk();
        $this->actingAs($admin)->get('/users')->assertOk();
        $this->actingAs($admin)->get('/transactions')->assertOk();
    }

    public function test_product_search_filter_and_sorting(): void
    {
        $admin = User::where('username', 'admin')->first();

        // Search by keyword
        $response = $this->actingAs($admin)->get('/products?search=Laptop');
        $response->assertOk();
        $response->assertSee('Laptop');

        // Filter by category
        $category = Category::first();
        $response = $this->actingAs($admin)->get("/products?category_id={$category->id}");
        $response->assertOk();

        // Sort by price descending
        $response = $this->actingAs($admin)->get('/products?sort=price&dir=desc');
        $response->assertOk();
    }

    public function test_product_creation_and_sku_uniqueness(): void
    {
        $admin = User::where('username', 'admin')->first();
        $category = Category::first();

        // Valid product creation
        $response = $this->actingAs($admin)->post('/products', [
            'sku' => 'PRD-UNIQUE-999',
            'category_id' => $category->id,
            'name' => 'Keyboard Wireless RGB',
            'price' => 350000,
            'stock' => 15,
            'minimum_stock' => 5,
        ]);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['sku' => 'PRD-UNIQUE-999']);

        // Duplicate SKU rejected
        $response = $this->actingAs($admin)->post('/products', [
            'sku' => 'PRD-UNIQUE-999',
            'category_id' => $category->id,
            'name' => 'Keyboard Wireless Copy',
            'price' => 300000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);
        $response->assertSessionHasErrors('sku');
    }

    public function test_product_cannot_be_deleted_if_has_stock_movements(): void
    {
        $admin = User::where('username', 'admin')->first();
        // Product with movement
        $movement = StockMovement::first();
        $product = $movement->product;

        $response = $this->actingAs($admin)->delete("/products/{$product->id}");
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_product_can_be_deleted_if_no_stock_movements(): void
    {
        $admin = User::where('username', 'admin')->first();
        $category = Category::first();

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'PRD-DELETE-TEST',
            'name' => 'Item Disposable',
            'price' => 10000,
            'stock' => 0,
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($admin)->delete("/products/{$product->id}");
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_category_cannot_be_deleted_if_has_products(): void
    {
        $admin = User::where('username', 'admin')->first();
        $categoryWithProducts = Category::whereHas('products')->first();

        $response = $this->actingAs($admin)->delete("/categories/{$categoryWithProducts->id}");
        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $categoryWithProducts->id]);
    }

    public function test_supplier_cannot_be_deleted_if_linked_to_products(): void
    {
        $admin = User::where('username', 'admin')->first();
        $supplierWithProducts = Supplier::whereHas('products')->first();

        $response = $this->actingAs($admin)->delete("/suppliers/{$supplierWithProducts->id}");
        $response->assertRedirect(route('suppliers.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('suppliers', ['id' => $supplierWithProducts->id]);
    }

    public function test_admin_cannot_delete_or_demote_self(): void
    {
        $admin = User::where('username', 'admin')->first();

        // Cannot delete self
        $response = $this->actingAs($admin)->delete("/users/{$admin->id}");
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Cannot demote self to staff
        $response = $this->actingAs($admin)->put("/users/{$admin->id}", [
            'username' => $admin->username,
            'full_name' => 'Administrator Updated',
            'role' => 'staff',
        ]);
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error');
        $this->assertEquals('admin', $admin->fresh()->role);
    }

    public function test_stock_in_increases_stock_and_creates_movement(): void
    {
        $staff = User::where('username', 'staff')->first();
        $product = Product::first();
        $initialStock = $product->stock;

        $response = $this->actingAs($staff)->post('/transactions/stock-in', [
            'product_id' => $product->id,
            'quantity' => 10,
            'note' => 'Penerimaan barang baru dari vendor',
        ]);

        $response->assertRedirect(route('transactions.index'));
        $this->assertEquals($initialStock + 10, $product->fresh()->stock);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id' => $staff->id,
            'type' => 'IN',
            'quantity' => 10,
        ]);
    }

    public function test_stock_out_decreases_stock_and_prevents_negative_stock(): void
    {
        $staff = User::where('username', 'staff')->first();
        $product = Product::where('stock', '>', 5)->first();
        $initialStock = $product->stock;

        // Valid stock out
        $response = $this->actingAs($staff)->post('/transactions/stock-out', [
            'product_id' => $product->id,
            'quantity' => 2,
            'note' => 'Pengeluaran inventaris',
        ]);

        $response->assertRedirect(route('transactions.index'));
        $this->assertEquals($initialStock - 2, $product->fresh()->stock);

        // Exceeding stock out rejected
        $currentStock = $product->fresh()->stock;
        $response = $this->actingAs($staff)->post('/transactions/stock-out', [
            'product_id' => $product->id,
            'quantity' => $currentStock + 50,
            'note' => 'Pengeluaran melebihi stok',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals($currentStock, $product->fresh()->stock);
    }

    public function test_low_stock_business_rule(): void
    {
        $product = Product::first();
        $product->update(['stock' => 3, 'minimum_stock' => 5]);
        $this->assertTrue($product->isLowStock());

        $product->update(['stock' => 10, 'minimum_stock' => 5]);
        $this->assertFalse($product->isLowStock());
    }
}
