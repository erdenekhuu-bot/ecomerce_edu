<?php
namespace App\Models;

use App\Models\Order;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromQuery, WithMapping, WithHeadings
{
    protected $sale_id, $user_id, $date_range;

    /**
     * OrdersExport constructor.
     *
     * @param $sale_id
     * @param $user_id
     * @param $date_range
     */
    public function __construct($sale_id, $user_id, $date_range)
    {
        $this->sale_id = $sale_id;
        $this->user_id = $user_id;
        $this->date_range = $date_range;
    }

    /**
     * Modify the query to filter orders based on sale_id, user_id, and date_range.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Get the admin user
        $admin = User::where('user_type', 'admin')->first();

        // Start the query for orders
        $orders = Order::with(['combined_order'])
            ->where('shop_id', $admin->shop_id)
            ->where('payment_status', 'paid');

        // Apply sale_id filter if it is provided
        if ($this->sale_id > 0) {
            $orders = $orders->where('assign_sale_id', $this->sale_id);
        }

        // Apply user_id filter if it is provided
        if ($this->user_id > 0) {
            $orders = $orders->where('user_id', $this->user_id);
        }

        // Apply date range filter if it is provided
        if ($this->date_range) {
            list($startDate, $endDate) = explode(' to ', $this->date_range);
            $orders = $orders->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Return the query
        return $orders;
    }

    /**
     * Define the headings of the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Захиалгын дугаар',
            'Барааны тоо',
            'Барааны нэр',
            'Хүргэлтийн мэдээлэл',
            'Төлбөрийн төрөл',
            'Борлуулагч',
            'Хэрэглэгч',
            'Үнийн дүн',
        ];
    }

    /**
     * Map each order to a row in the export file.
     *
     * @param \App\Models\Order $order
     * @return array
     */
    public function map($order): array
    {
        $product_names = "";
        $qty = 0;
        foreach ($order->orderDetails as $key => $details) {
            if ($key == 0) {
                $product_names .= 'нэр: ' . $details->product->name . ', тоо: ' . $details->quantity;
            }else{
                $product_names .= ', нэр: ' . $details->product->name . ', тоо: ' . $details->quantity;
            }
        }
        
        return [
            $order->combined_order->code,
            count($order->orderDetails),
            $product_names,
            "Утас: " . json_decode($order->shipping_address)->phone .
            ", Хаяг: " . json_decode($order->shipping_address)->country . ', ' . json_decode($order->shipping_address)->state . ', ' . json_decode($order->shipping_address)->address,
            $order->payment_type,
            $order->saler->name ?? 'Хэрэглэгч',
            $order->user->name,
            $order->grand_total,
        ];
    }
}
