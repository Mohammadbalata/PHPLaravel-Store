<?php

namespace App\Facades;

use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Repositories\Cart\CartRepository get()
 * @see \App\Repositories\Cart\CartRepository
 */

class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CartRepository::class;
    }
}