@extends('backend.layouts.app')

@section('content')
    <h1 class="h4 fw-700 mb-3">{{ translate('Order code') }}: {{ $order->order_no }}</h1>
    <div class="row gutters-5">
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    <h2 class="h2 fs-16 fw-600 mb-0">{{ translate('Order Details') }}</h2>
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
                        <div class="col-md-6 col-xl-4">
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                        <td class="">{{ translate('Order code') }}:</td>
                                        <td class="text-right text-info fw-700">{{ $order->quote_no }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Order Date') }}:</td>
                                        <td class="text-right fw-700">{{ $order->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Delivery type') }}:</td>
                                        <td class="text-right fw-700">
                                            {{ ucfirst(str_replace('_', ' ', $order->type_of_delivery == 'pickup' ? $order->type_of_delivery : $order->delivery_type)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Payment method') }}:</td>
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
                <div class="card-header">
                    <div class="flex-grow-1 row align-items-start">
                        <div class="col-6">
                            <div class="row"> 
                                <ul class="nav nav-tabs col-12" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Нийт хямдрал</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Барааны хямдрал</button>
                                    </li>
                                </ul>
                                <div class="tab-content col-md-12 mt-4" id="myTabContent">
                                    <div class="tab-pane fade show active col-md-12" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6 mr-auto ml-0">
                                                    <form action="{{ route('price-quote.update.status', $order->quote_no) }}" method="post">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label class="mb-0">Төлөв</label>
                                                            <select class="form-control aiz-selectpicker" id="quote_status" name="quote_status"
                                                                data-minimum-results-for-search="Infinity" data-selected="{{ $order->quote_status }}"
                                                                    @if ($order->quote_status == 'cancelled') disabled @endif required>
                                                                <option value="{{$order->quote_status == 'user_send' ? 'user_send' : 'admin_send'}}" @if ($order->quote_status == 'admin_send' || $order->quote_status == 'user_send') selected @endif>{{ ($order->quote_status == 'admin_send') ? "Илгээсэн" : "Хүлээгдэж буй"}}</option>
                                                                <option value="admin_change" @if ($order->quote_status == 'admin_change') selected @endif>Зассах</option>
                                                                <option value="cancelled" @if ($order->quote_status == 'cancelled') selected @endif>Цуцлах</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="mb-0">Хямдрал</label>
                                                                <div class="input-group">
                                                                    <input type="number" min="0" placeholder="Amount"
                                                                        name="discount" class="form-control"
                                                                        value="{{$order->coupon_discount}}" required
                                                                        >
                                                                    <div class="input-group-append">
                                                                        <input type="hidden" id="discount_type" name="discount_type" value="{{$order->discount_type}}">
                                                                        <span id="radioLabel" class="input-group-text discount_type">{{$order->discount_type}}</span>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="mb-0">Борлуулагч</label>
                                                            <select class="form-control aiz-selectpicker" id="quote_status" name="saler_id"
                                                                data-minimum-results-for-search="Infinity" data-selected="{{ $order->assign_sale_id }}"
                                                                    @if ($order->quote_status == 'cancelled') disabled @endif required>
                                                                @foreach (\App\Models\User::join('roles', 'roles.id' , '=', 'users.role_id')->where('roles.name', 'Sales Manager')->select('users.*')->get() as $user)
                                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <button type="submit" class="btn btn-success">Шинэчлэх</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-5 mr-auto ml-0">
                                                    <div class="d-flex flex-column align-items-start">
                                                        <div class="d-flex flex-row justify-content-between w-100 mb-2">
                                                            <span>Үндсэн үнэ:</span>
                                                            <span>{{format_price($order->seller_earning)}}</span>
                                                        </div>
                                                        <div class="d-flex flex-row justify-content-between w-100 mb-2">
                                                            <span>Хямдрал:</span>
                                                            <span>{{$order->discount_type != "₮" ? $order->coupon_discount . "%" : format_price($order->coupon_discount)}}</span>
                                                        </div>
                                                        <div class="d-flex flex-row justify-content-between w-100 mb-2">
                                                            <span>Нийт:</span>
                                                            <span>{{format_price($order->grand_total)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade col-md-12" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <form action="{{ route('price-quote.update.status', $order->quote_no) }}" method="post">
                                            <div class="mb-3">
                                                <label class="mb-0">Борлуулагч</label>
                                                <select class="form-control aiz-selectpicker" id="quote_status" name="saler_id"
                                                    data-minimum-results-for-search="Infinity" data-selected="{{ $order->assign_sale_id }}"
                                                        @if ($order->quote_status == 'cancelled') disabled @endif required>
                                                    @foreach (\App\Models\User::join('roles', 'roles.id' , '=', 'users.role_id')->where('roles.name', 'Sales Manager')->select('users.*')->get() as $user)
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <table class="aiz-table table-bordered">
                                                @csrf
                                                <thead>
                                                    <tr class="">
                                                        <th class="text-center" width="5%" data-breakpoints="lg">#</th>
                                                        <th width="40%">{{ translate('Product') }}</th>
                                                        <th class="text-center" data-breakpoints="lg">{{ translate('Qty') }}</th>
                                                        <th class="text-center" data-breakpoints="lg">{{ translate('Unit Price') }}</th>
                                                        <th class="text-center" data-breakpoints="lg">Хямдрал</th>
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
                                                            <td class="text-center">
                                                                <div class="input-group">
                                                                    <input type="number" min="0" placeholder="Amount"
                                                                        name="discountTab[]" class="form-control"
                                                                        value="{{$orderDetail->discount}}" required
                                                                        >
                                                                    <div class="input-group-append" onclick="toggleDiscountType(this)">
                                                                        <input type="hidden" id="discount_type" name="discount_typeTab[]" value="{{$orderDetail->discount_type}}">
                                                                        <span id="radioLabel" class="input-group-text discount_type">{{$orderDetail->discount_type}}</span>
                                                                    </div>
                                                                    <input type="hidden" name="id[]" value="{{ $orderDetail->id }}">
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ format_price($orderDetail->total) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="my-3">
                                                <button type="submit" class="btn btn-success">Шинэчлэх</button>
                                            </div>
                                        </form>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mr-auto ml-0">
                        </div>
                        @if ($order->type_of_delivery == 'pickup')
                            <div class="col-md-auto w-md-250px">
                                @php
                                    $pickup_address = App\Models\PickupPoint::find($order->pickup_point_id);
                                @endphp
                                <h5 class="fs-14 mb-3">{{ translate('Pick Up Point') }}</h5>
                                <address class="">
                                    {{ $pickup_address?->name }}<br>
                                    <a href="{{ route('staffs.edit', encrypt($pickup_address?->user?->id)) }}">
                                        {{ $pickup_address?->user?->name }}<br></a>
                                    {{ $pickup_address?->phone }}<br>
                                    {{ $pickup_address?->location }}<br>
                                </address>
                            </div>
                        @else
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
                        @endif
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
                                <th class="text-center" data-breakpoints="lg">Дэд Үнэ</th>
                                @if ($order->orderDetails->sum('discount') > 0)
                                    <th class="text-center" data-breakpoints="lg">Хямдрал</th>
                                @endif
                               
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
                                    <td class="text-center">{{ format_price($orderDetail->price * $orderDetail->quantity) }}</td>
                                    @if ($order->orderDetails->sum('discount') > 0)
                                    <td class="text-center">{{ $orderDetail->discount_type == "₮" ? format_price($orderDetail->discount) : ($orderDetail->discount . '%') }}</td>
                                    @endif
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
                                        <td>
                                            <strong class=""> {{ translate('Coupon discount') }} :</strong>
                                            @if ($order->coupon_code)
                                                <div>({{ $order->coupon_code }})</div>
                                            @endif
                                        </td>
                                        <td>{{ format_price($order->coupon_discount) }}</td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong class=""> Гишүүнчлэл хямдрал :</strong>
                                            @if ($order->coupon_code)
                                                <div>({{ $order->coupon_code }})</div>
                                            @endif
                                        </td>
                                        <td>{{ format_price($order->membership) }}</td>
                                    </tr>
                                    @if ($order->orderDetails->sum('discount') == 0)
                                        <tr>
                                            <td>
                                                <strong class=""> Борлуулагч хямдрал :</strong>
                                                @if ($order->coupon_code)
                                                    <div>({{ $order->coupon_code }})</div>
                                                @endif
                                            </td>
                                            <td>{{ $order->discount_type == "₮" ? format_price($order->discount) : ($order->discount. "%")}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong class="">{{ translate('TOTAL') }} :</strong></td>
                                        <td class=" h4">
                                            {{ format_price($order->grand_total) }}
                                        </td>
                                    </tr>
                                    @if (addon_is_activated('refund') && $order->refund_amount > 0)
                                        <tr>
                                            <td>
                                                <strong class="text-danger"> {{ translate('Refunded') }} :</strong>
                                            </td>
                                            <td><span class="text-danger">-
                                                    {{ format_price($order->refund_amount) }}</span></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('modal')
    <!-- Refund Information Modal -->
    <div class="modal fade" id="refund_request_info_modal">
        <div class="modal-dialog">
            <div class="modal-content" id="refund-request-info-modal-content">

            </div>
        </div>
    </div>

    {{-- Accept refund request Modal --}}
    <div id="accept_refund_request_modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Accept Refund Request.') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form class="form-horizontal member-block" action="{{ route('admin.refund_request.accept') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="refund_request_id" id="refund_request_id" value="">

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="amount">{{ translate('Amount') }}</label>
                            <div class="col-md-9">
                                <input type="number" lang="en" min="0" step="0.01" name="amount"
                                    id="amount" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            {{ translate('Select Pay in Wallet to refund in the customer wallet. And select Pay Manually to refund customer manually.') }}
                        </div>
                        <div class="alert alert-info">
                            {{ translate('This amount is without shipping cost. If you want to add shipping cost you can change this amount.') }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mt-2"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" name="button" value="manual"
                            class="btn btn-success">{{ translate('Pay Manually') }}</button>
                        <button type="submit" name="button" value="wallet"
                            class="btn btn-primary">{{ translate('Pay in Wallet') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Refund request Modal --}}
    <div id="reject_refund_request_modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Reject Refund Request.') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <p class='fs-14'>{{ translate('Do you really want to reject this refund Request?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-2"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <a href="" id="reject_refund_request_link"
                        class="btn btn-primary">{{ translate('Reject') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.discount_type').click(function() {
                // Toggle the discount type between ₮ and %
                const currentValue = $(this).text() === "₮";
                $(this).text(currentValue ? "%" : "₮");
                
                // Set the hidden input value based on the discount type
                $('#discount_type').val(currentValue ? "%" : "₮");
            });
        });
        function toggleDiscountType(element) {
    // Find the span and hidden input inside the clicked .discount_type_tab element
    const span = $(element).find('.discount_type');
    const hiddenInput = $(element).find('#discount_type');
    
    // Toggle the text of the span between ₮ and %
    const currentValue = span.text() === "₮";
    span.text(currentValue ? "%" : "₮");
    
    // Update the value of the hidden input accordingly
    hiddenInput.val(currentValue ? "%" : "₮");
}
    </script>
@endsection
