<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

class ProductController extends Controller{
    public function showProducts() {
        $products = Product::all();

        return view('admin.viewProducts', compact('products'));
    }

    public function showProductsCustomer() {
        $products = Product::all();
        return view('customer.dashboard', compact('products'));
    }

    public function addProduct(Request $request) {
        $validatedData = $request->validate([
            'productName' => 'required|string|max:255',
            'productPrice' => 'required|numeric',
        ]);
        $product = new Product;
        $product->productName = $validatedData['productName'];
        $product->productPrice = $validatedData['productPrice'];
        $product->save();
        return redirect('/admin/viewProducts')->with('success', 'Product added successfully.');
    }

    public function cartProduct(Request $request) {
        $request->validate([
            'productId' => 'required|exists:products,id',
            'productName' => 'required|string',
            'productPrice' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);
        $order = Order::where([
            'userID' => Auth::id(),
            'productID' => $request->input('productId'),
            'status' => 1,
        ])->first();
        
        if ($order) {
            $order->update([
                'quantity' => $order->quantity + $request->input('quantity'),
            ]);
        } else {
            Order::create([
                'userID' => Auth::id(),
                'productID' => $request->input('productId'),
                'status' => 1,
                'productName' => $request->input('productName'),
                'productPrice' => $request->input('productPrice'),
                'quantity' => $request->input('quantity'),
            ]);
        }
        return response()->json(['message' => 'Order placed successfully.']);
    }
    
    public function showCart() {
        $userOrder = Order::where('userID', auth()->id())
            ->join('products', 'orders.productID', '=', 'products.id')
            ->select('orders.*', 'products.productName', 'products.productPrice')
            ->where('orders.status', 1)
            ->get();
        $totalOrder = $userOrder->groupBy('productID')->map(function ($group) {
            return [
                'id' => $group->first()->id,
                'productName' => $group->first()->productName,
                'productPrice' => $group->first()->productPrice,
                'quantity' => $group->sum('quantity'),
                'total' => $group->sum('quantity') * $group->first()->productPrice,
            ];
        });
        return view('customer.cart', compact('totalOrder'));
    }
    
    
    public function removeOrder($orderID) {
        $order = Order::find($orderID);
        if ($order) {
            $order->update(['status' => 0]);
            return response()->json(['message' => 'Order removed successfully.']);
        }
        return response()->json(['error' => 'Order not found.'], 404);
    }

    public function checkout() {
        // Update the status of orders to 0 after the purchase
        Order::where('userID', Auth::id())->update(['status' => 0]);
    
        return response()->json(['message' => 'Checkout completed successfully.']);
    }

    public function createCheckoutSession(Request $request) {
        \Stripe\Stripe::setApiKey('sk_test_51MntetCvBL6iYfWCYWDISZaA1HtVSi4LFsUWKcbocZg1HJdzFqssYo1gLi3wqMsmafFq7LN8pWvAebN1Hl4mSAEh00W0VXCN51'); // Replace with your actual secret key
    
        $subtotal = $request->input('subtotal');

        if ($subtotal <= 0) {
            return response()->json(['error' => 'Subtotal must be greater than zero.'], 400);
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Total Order',
                        ],
                        'unit_amount' => $subtotal * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('paymentSuccess'),
            'cancel_url' => route('paymentCancel'),
        ]);
    
        return response()->json(['url' => $session->url]);
    }

    public function paymentSuccess() {
        Order::where('userID', Auth::id())->update(['status' => 0]);
        return view('customer.paymentSuccess');
    }

    public function paymentCancel() {
        return view('customer.paymentCancel');
    }
}