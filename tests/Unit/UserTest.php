<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    expect(User::count())->toBe(1)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com');
});

it('can update a user', function () {
    $user = User::factory()->create();

    $user->update([
        'name' => 'Jane Doe',
    ]);

    expect($user->fresh()->name)->toBe('Jane Doe');
});

it('can delete a user', function () {
    $user = User::factory()->create();

    $user->delete();

    expect(User::count())->toBe(0);
});
