<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            $newStatus = $order->status;
            $oldStatus = $order->getOriginal('status');

            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                foreach ($order->products as $product) {
                    $product->decrement('stock', $product->pivot->quantity);
                }
            }
            
            if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                foreach ($order->products as $product) {
                    $product->increment('stock', $product->pivot->quantity);
                }
            }
        }
    }
}
