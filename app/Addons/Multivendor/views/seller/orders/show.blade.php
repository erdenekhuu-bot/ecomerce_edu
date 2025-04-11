@extends('addon:multivendor::seller.layouts.app')

@section('content')
    <div class="row gutters-5">
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    <h1 class="h2 fs-18 mb-0">{{ translate('Order Details') }}</h1>
                </div>
                <div class="card-header">
                    <div class="flex-grow-1 row">
                        <div class="col-md mb-3">
                            <div>
                                <div class="fs-15 fw-600 mb-2">{{ translate('Customer info') }}</div>
                                <div><span class="opacity-80 mr-2 ml-0">{{ translate('Name') }}:</span>
                                    {{ $order->user->name ?? '' }}</div>
                                <div><span class="opacity-80 mr-2 ml-0">{{ translate('Email') }}:</span>
                                    {{ $order->user->email ?? '' }}</div>
                                <div><span class="opacity-80 mr-2 ml-0">{{ translate('Phone') }}:</span>
                                    {{ $order->user->phone ?? '' }}</div>
                            </div>
                        </div>
                        @if (get_setting('order_manage_by_admin') == 0)
                            <div class="col-md-3 ml-auto mr-0 mb-3">
                                <label>{{ translate('Payment Status') }}</label>
                                <select class="form-control aiz-selectpicker" id="update_payment_status"
                                    data-minimum-results-for-search="Infinity" data-selected="{{ $order->payment_status }}"
                                    @if (
                                        $order->payment_status == 'paid' ||
                                            $order->delivery_status == 'delivered' ||
                                            $order->delivery_status == 'cancelled') disabled @endif>
                                    <option value="paid">{{ translate('Paid') }}</option>
                                    <option value="unpaid">{{ translate('Unpaid') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>{{ translate('Delivery Status') }}</label>
                                <select class="form-control aiz-selectpicker" id="update_delivery_status"
                                    data-minimum-results-for-search="Infinity"
                                    data-selected="{{ $order->delivery_status }}"
                                    @if ($order->delivery_status == 'delivered' || $order->delivery_status == 'cancelled') disabled @endif>
                                    <option value="confirmed">{{ translate('Confirmed') }}</option>
                                    <option value="processed">{{ translate('Processed') }}</option>
                                    <option value="shipped">{{ translate('Shipped') }}</option>
                                    <option value="delivered">{{ translate('Delivered') }}</option>
                                    <option value="cancelled">{{ translate('Cancel') }}</option>
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-header">
                    <div class="flex-grow-1 row align-items-start">
                        <div class="col-md-auto w-md-250px">
                            @php
                                $shipping_address = json_decode($order->shipping_address);
                            @endphp
                            <h5 class="fs-14 mb-3">{{ translate('Shipping address') }}</h5>
                            <address class="">
                                {{ $shipping_address->phone }}<br>
                                {{ $shipping_address->address }}<br>
                                {{ $shipping_address->city }}, {{ $shipping_address->postal_code }}<br>
                                {{ $shipping_address->state }}, {{ $shipping_address->country }}
                            </address>
                        </div>
                       
                        <div class="col-md-4 col-xl-3 ml-auto mr-0">
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                        <td class="">{{ translate('Order code') }}</td>
                                        <td class="text-right text-info fw-700">{{ $order->combined_order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Order Date') }}</td>
                                        <td class="text-right fw-700">{{ $order->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Delivery type') }}</td>
                                        <td class="text-right fw-700">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery_type)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Payment method') }}</td>
                                        <td class="text-right fw-700">
                                            {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                    </tr>

                                    @if ($order->manual_payment == 1 && $order->manual_payment_data !== null)
                                        @php
                                            $manual_payment_data = json_decode($order->manual_payment_data);
                                        @endphp
                                        <tr>
                                            <td class="">{{ translate('Transaction ID') }}:</td>
                                            <td class="text-right fw-700">
                                                {{ $manual_payment_data->transactionId }}</td>
                                        </tr>

                                        <tr>
                                            <td class="">{{ translate('Paid Via') }}:</td>
                                            <td class="text-right fw-700">
                                                {{ $manual_payment_data->payment_method }}</td>
                                        </tr>

                                        @if ($manual_payment_data->reciept)
                                            <tr>
                                                <td class="">{{ translate('Receipt') }}:</td>
                                                <td class="text-right fw-700">
                                                    <a href="{{ my_asset($manual_payment_data->reciept) }}" target="_blank"
                                                        rel="noopener noreferrer">{{ translate('Download') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="aiz-table table-bordered">
                        <thead>
                            <tr class="">
                                <th class="text-center" width="5%" data-breakpoints="lg">#</th>
                                <th width="40%">{{ translate('Product') }}</th>
                                <th class="text-center" data-breakpoints="lg">{{ translate('Qty') }}</th>
                                <th class="text-center" data-breakpoints="lg">{{ translate('Unit Price') }}</th>
                                <th class="text-center" data-breakpoints="lg">{{ translate('Unit Tax') }}</th>
                                <th class="text-center" data-breakpoints="lg">{{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <div class="media">
                                                <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                                    class="size-60px mr-3">
                                                <div class="media-body">
                                                    <h4 class="fs-14 fw-400">{{ $orderDetail->product->name }}</h4>
                                                    @if ($orderDetail->variation)
                                                        <div>
                                                            @foreach ($orderDetail->variation->combinations as $combination)
                                                                <span class="mr-2">
                                                                    <span
                                                                        class="opacity-50">{{ optional($combination->attribute)->name }}</span>:
                                                                    {{ optional($combination->attribute_value)->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">{{ format_price($orderDetail->price) }}</td>
                                    <td class="text-center">{{ format_price($orderDetail->tax) }}</td>
                                    <td class="text-center">{{ format_price($orderDetail->total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-xl-4 col-md-6 ml-auto mr-0">
                            <table class="table">
                                <tbody>
                                    @php
                                        $totalTax = 0;
                                        foreach ($order->orderDetails as $item) {
                                            $totalTax += $item->tax * $item->quantity;
                                        }
                                    @endphp
                                    <tr>
                                        <td><strong class="">{{ translate('Sub Total') }} :</strong></td>
                                        <td>
                                            {{ format_price($order->orderDetails->sum('total') - $totalTax) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="">{{ translate('Tax') }} :</strong></td>
                                        <td>{{ format_price($totalTax) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class=""> {{ translate('Shipping') }} :</strong></td>
                                        <td>{{ format_price($order->shipping_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class=""> {{ translate('Coupon discount') }} :</strong></td>
                                        <td>{{ format_price($order->coupon_discount) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="">{{ translate('TOTAL') }} :</strong></td>
                                        <td class=" h4">
                                            {{ format_price($order->grand_total) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-lg-auto w-lg-300px">
            <div class="card">
                <div class="card-header">
                    <h3 class="fs-16 mb-0">{{ translate('Tracking information') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.orders.add_tracking_information') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="form-group mb-1">
                            <label class="mb-0">{{ translate('Courier name') }}:</label>
                            <input type="text" class="form-control form-control-sm" name="courier_name"
                                value="{{ $order->courier_name }}" required>
                        </div>
                        <div class="form-group mb-1">
                            <label class="mb-0">{{ translate('Tracking number') }}:</label>
                            <input type="text" class="form-control form-control-sm" name="tracking_number"
                                value="{{ $order->tracking_number }}" required>
                        </div>
                        <div class="form-group mb-1">
                            <label class="mb-0">{{ translate('Tracking url') }}:</label>
                            <input type="text" class="form-control form-control-sm" name="tracking_url"
                                value="{{ $order->tracking_url }}" required>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-sm btn-primary" type="submit">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="fs-16 mb-0">{{ translate('Order updates') }}</h3>
                </div>
                <div class="card-body">
                    @foreach ($order->order_udpates as $order_udpate)
                        <div class="mb-3">
                            <div class="p-2 bg-soft-secondary rounded">
                                {{ $order_udpate->translatable_note ? translate($order_udpate->note) : $order_udpate->note }}
                            </div>
                            <span
                                class="fs-12 opacity-60">{{ translate('by') . ' ' . ($order_udpate->user->name ?? translate('Deleted user')) . ' at ' . $order_udpate->created_at->format('h:ia, d-m-Y') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('seller.orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('seller.orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });
    </script>
@endsection
