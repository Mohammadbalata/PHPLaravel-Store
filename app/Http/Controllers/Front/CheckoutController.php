<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        // $items = $cart->get()->groupBy('product.store_id')->all();
        // dd($items);

        if($cart->get()->count() == 0 ){
            return redirect()->route('home');
        }

        return view("front.Checkout", [
            "cart" => $cart,
            "countries" => Countries::getNames()
        ]);
    }

    public function store(Request $request, CartRepository $cart)
    {
        // $request->validate([
        //     'addr.shiping.first_name' => ['required','string','max:255'],
        //     'addr.shiping.last_name' => ['required','string','max:255'],
        //     'addr.shiping.email' => ['required','string','max:255'],
        //     'addr.shiping.phone_number' => ['required','string','max:255'],
        //     'addr.shiping.city' => ['required','string','max:255'],

        // ]);
        $items = $cart->get()->groupBy('product.store_id')->all();
        
        DB::beginTransaction();
        try {
            foreach ($items as $store_id => $cart_item){
                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => "cod",
                ]);
                foreach ($cart_item as $item) {
                    OrderItem::create([
                        "order_id" => $order->id,
                        "product_id" => $item->product_id,
                        "product_name" => $item->product->name,
                        "price" => $item->product->price,
                        "quantity" => $item->quantity,
                    ]);
                }

                foreach ($request->post("addr") as $type  => $address) {
                    $address['type'] = $type;
                    $order->addresses()->create($address);
                }
            }
            DB::commit();
            event(new OrderCreated($order));

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return Redirect::route('home');
    }

    
}
