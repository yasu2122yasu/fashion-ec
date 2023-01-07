<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $cart_id = Session::get('cart');
        $cart = Cart::find($cart_id);

        $total_price = 0;
        foreach ($cart->products as $product) {
            $total_price += $product->price * $product->pivot->quantity;
        }

        return view('cart.index')
            ->with('line_items', $cart->products)
            ->with('total_price', $total_price);
    }

    public function checkout()
    {
        $cart_id = Session::get('cart');
        $cart = Cart::find($cart_id);


        $line_items = [];
        foreach ($cart->products as $product) {
            $line_item = [
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $product->price,
                    'product_data' => [
                        'name' => $product->name,
                        'description' => $product->description,
                    ],
                ],
                'quantity' => $product->pivot->quantity,
            ];
            // array_pushメソッドは配列の末尾に値を追加することができる。
            array_push($line_items, $line_item);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // \Stripe\Checkout\Session クラスはStripe Checkoutのセッションに関するクラス。
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$line_items],
            'success_url' => route('cart.success'),
            'cancel_url' => route('cart.index'),
            // 今回はクレジットカードでの実装をする予定
            'mode' => 'payment',
        ]);

        return view('cart.checkout', [
            // 配列を使うとwithを利用せずに渡すこともできる！
            'session' => $session,
            'publicKey' => env('STRIPE_PUBLIC_KEY')
        ]);
    }

    public function success()
    {
        $cart_id = Session::get('cart');
        LineItem::where('cart_id', $cart_id)->delete();

        return redirect(route('product.index'));
    }
}
