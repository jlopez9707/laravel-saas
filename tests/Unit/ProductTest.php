<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    
    $role = Role::create(['name' => 'super_admin']);
    
    $permissions = [
        'ViewAny:Product',
        'Create:Product',
        'View:Product',
    ];
    
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
    }
    
    $role->givePermissionTo($permissions);
});

it('filters out of stock products', function () {
    Product::factory()->create(['stock' => 0]);
    $inStock = Product::factory()->create(['stock' => 5]);
    
    expect(Product::inStock()->get())
        ->toHaveCount(1)
        ->first()->id->toBe($inStock->id);
});

it('create a product', function () {
    $product = Product::factory()->create();
    
    expect(Product::all())->toHaveCount(1);
    expect(Product::all()->first()->id)->toBe($product->id);
});

it('update a product', function () {
    $product = Product::factory()->create();
    
    $product->update([
        'name' => 'Updated Product',
    ]);
    
    expect(Product::all())->toHaveCount(1);
    expect(Product::all()->first()->name)->toBe('Updated Product');
});

it('delete a product', function () {
    $product = Product::factory()->create();
    
    $product->delete();
    
    expect(Product::all())->toHaveCount(0);
});