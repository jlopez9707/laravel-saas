<?php

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    
    $role = Role::create(['name' => 'super_admin']);
    
    $permissions = [
        'ViewAny:Order',
        'Create:Order',
        'View:Order',
    ];
    
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
    }
    
    $role->givePermissionTo($permissions);
});

it('can render create order page', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    
    $this->actingAs($user);

    Livewire::test(CreateOrder::class)->assertSuccessful();
});

it('can create an order', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);

    $product = Product::factory()->create(['stock' => 10, 'price' => 100]);

    Livewire::test(CreateOrder::class)
        ->assertSchemaExists('form')
        ->fillForm([
            'user_id' => $user->id,
        ])
        ->set('selectedProductIds', [$product->id => true]) // Select product
        ->set('quantities', [$product->id => 2]) // Set quantity
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'total' => 200
    ]);
    
    $this->assertDatabaseHas('order_product', [
        'product_id' => $product->id, 
        'quantity' => 2,
        'price' => 100
    ]);
});

it('validates product selection', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $this->actingAs($user);

    Livewire::test(CreateOrder::class)
        ->fillForm([
            'user_id' => $user->id,
        ])
        ->call('create')
        ->assertNotified(); 
});
