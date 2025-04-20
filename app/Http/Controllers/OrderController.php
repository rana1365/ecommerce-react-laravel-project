<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function saveOrder (Request $request)
    {
        if (!empty($request->cart)) {
            // Save orders in database
            $order = new Order();
            $order->name = $request->name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->user_id = $request->user()->id;
            $order->address = $request->address;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->grand_total = $request->grand_total;
            $order->subtotal = $request->subtotal;
            $order->shipping = $request->shipping;
            $order->discount = $request->discount;
            $order->payment_status = $request->payment_status;
            $order->status = $request->status;
            $order->save();

            // Save order items in database
            foreach ($request->cart as $item) {
                $orderItem = new OrderItem();
                $orderItem->name = $item['title'];
                $orderItem->order_id = $order->id;
                $orderItem->price = $item['qty'] * $item['price'];
                $orderItem->unit_price = $item['price'];
                $orderItem->qty = $item['qty'];
                $orderItem->product_id = $item['product_id'];
                $orderItem->size = $item['size'];
                $orderItem->save();
            }
            
            return response()->json([
                'status' => 200,
                'id' => $order->id,
                'message' => 'You have successfully placed order!',
            ], 200);

        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Your cart is empty',
            ], 400);
        }
    }
}
