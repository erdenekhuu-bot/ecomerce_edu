@extends('addon:multivendor::seller.layouts.app')
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Order code') }}: {{ $order->combined_order->code }}</h1>
        </div>
    </div>
    <form action="{{ route('seller.refund_request.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="row gutters-5">
            <div class="col-lg-8">
                <div class="card">

                    <div class="card-body">
                        <table class="aiz-table table-bordered">
                            <thead>
                                <tr class="">
                                    <th class="text-center" width="5%" data-breakpoints="lg">#</th>
                                    <th width="40%">{{ translate('Product') }}</th>
                                    <th class="text-center" data-breakpoints="lg">{{ translate('Refund Qty') }}</th>
                                    <th class="text-center" data-breakpoints="lg">{{ translate('Unit Price') }}</th>
                                    <th class="text-center" data-breakpoints="lg">{{ translate('Unit Tax') }}</th>
                                    <th class="text-center" data-breakpoints="lg">{{ translate('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $key => $orderDetail)
                                    <tr>
                                        <td>
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" value="{{ $orderDetail->id }}"
                                                    name="order_detail_ids[]">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </td>
                                        <td>
                                            @if ($orderDetail->product)
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
                                                                            class="opacity-50">{{ $combination->attribute->name }}</span>:
                                                                        {{ $combination->attribute_value->name }}
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
                                        <td class="text-center">
                                            <div class="form-group">
                                                <input type="number" value="{{ $orderDetail->quantity }}" min="1"
                                                    name="quantity_for_{{ $orderDetail->id }}"
                                                    max="{{ $orderDetail->quantity }}" class="form-control">
                                            </div>
                                        </td>
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
                                            <td><strong class="">{{ translate('Sub Total') }} :</strong>
                                            </td>
                                            <td>
                                                {{ format_price($order->orderDetails->sum('total') - $totalTax) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong class="">{{ translate('Tax') }} :</strong></td>
                                            <td>{{ format_price($totalTax) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong class=""> {{ translate('Shipping') }} :</strong>
                                            </td>
                                            <td>{{ format_price($order->shipping_cost) }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong class=""> {{ translate('Coupon discount') }}
                                                    :</strong>
                                                @if ($order->coupon_code)
                                                    <div>({{ $order->coupon_code }})</div>
                                                @endif
                                            </td>
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
            <div class="col col-lg-4 w-lg-300px">
                <div class="card">
                    <div class="card-header">
                        <h3 class="fs-16 mb-0">{{ translate('Refund Info') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label class="mb-0">{{ translate('Refund Amount') }}:</label>
                            <input type="number" class="form-control form-control-sm" name="refund_amount"
                                value="{{ $order->grand_total }}" step="0.01" max="{{ $order->grand_total }}"
                                required>
                        </div>
                        @php $refund_reasons = json_decode(get_setting('refund_reason_types')); @endphp
                        @if ($refund_reasons)
                            <div class="form-group mb-2">
                                <label class="mb-0">{{ translate('Reasons') }}:</label>
                                <select name="refund_reasons[]" class="form-control aiz-selectpicker" multiple
                                    data-live-search="true" data-selected-text-format="count" data-container="body"
                                    required>
                                    @foreach ($refund_reasons as $key => $refund_reason)
                                        <option value="{{ $refund_reason }}">{{ translate($refund_reason) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group mb-2">
                            <label class="mb-0">{{ translate('Note') }}:</label>
                            <textarea type="text" class="form-control form-control-sm" name="refund_note"
                                rows="3"></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-0">{{ translate('Attachments') }}:</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="attachments" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="btn btn-primary">{{ translate('Create Refund Request') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
