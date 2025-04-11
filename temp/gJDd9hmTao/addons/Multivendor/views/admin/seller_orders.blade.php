@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" id="sort_orders" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>
                </div>
                {{-- <div class="dropdown mb-2 mb-md-0">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item confirm-alert" href="javascript:void(0)" data-target="#bulk-delete-modal">
                                {{ translate('Delete selection') }}</a>
                        </div>
                    </div> --}}

                <div class="dropdown mb-2 mb-md-0">
                    <div class="dropdown">
                        <button class="btn border dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Bulk Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item confirm-alert" href="javascript:void(0)"
                                data-target="#bulk-delete-modal">
                                {{ translate('Delete selection') }}</a>

                            <a class="dropdown-item confirm-alert" href="javascript:void(0)" data-target="#bulk-paid-modal">
                                {{ translate('Order Paid') }}</a>

                            <a class="dropdown-item confirm-alert" href="javascript:void(0)"
                                data-target="#bulk-delivered-modal">
                                {{ translate('Order Delivered') }}</a>

                            <a class="dropdown-item confirm-alert" href="javascript:void(0)"
                                data-target="#bulk-cancelled-modal">
                                {{ translate('Order Cancelled') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-3 ml-auto">
                    <select class="form-control aiz-selectpicker" name="payment_status" onchange="sort_orders()"
                        data-selected="{{ $payment_status }}">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid">{{ translate('Paid') }}</option>
                        <option value="unpaid">{{ translate('Unpaid') }}</option>
                    </select>
                </div>

                <div class="col-xl-2 col-md-3">
                    <select class="form-control aiz-selectpicker" name="delivery_status" onchange="sort_orders()"
                        data-selected="{{ $delivery_status }}">
                        <option value="">{{ translate('Filter by Deliver Status') }}</option>
                        <option value="order_placed">{{ translate('Order placed') }}</option>
                        <option value="confirmed">{{ translate('Confirmed') }}</option>
                        <option value="processed">{{ translate('Processed') }}</option>
                        <option value="shipped">{{ translate('Shipped') }}</option>
                        <option value="delivered">{{ translate('Delivered') }}</option>
                        <option value="cancelled">{{ translate('Cancelled') }}</option>
                    </select>
                </div>
                <div class="col-md-2 ml-auto">
                    <select id="demo-ease" class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="shop_id"
                        onchange="sort_orders()" data-selected="{{ $shop_id }}">
                        <option value="">{{ translate('Choose Shop') }}</option>
                        @foreach (\App\Models\Shop::with('user')->get() as $key => $shop)
                            @if ($shop->user->user_type != 'admin')
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search"
                            @isset($sort_search)
                            value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            @if (auth()->user()->can('delete_orders'))
                                <th>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-all">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </th>
                            @else
                                <th data-breakpoints="lg">#</th>
                            @endif
                            <th>{{ translate('Shop') }}</th>
                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="lg">{{ translate('Customer') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                            <th data-breakpoints="lg">{{ translate('Payment Status') }}</th>
                            <th data-breakpoints="lg" class="text-right" width="15%">{{ translate('options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                @if (auth()->user()->can('delete_orders'))
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $order->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                @endif
                                <td>
                                    {{ $order->shop->name ?? translate('Deleted Shop') }}
                                </td>
                                <td>
                                    <div>{{ translate('Package') }} {{ $order->code }} {{ translate('of') }}</div>
                                    <div class="fw-600">{{ $order->combined_order->code }}</div>
                                </td>
                                <td>
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td>
                                    {{ $order->user->name ?? translate('Deleted Customer') }}
                                </td>
                                <td>
                                    {{ format_price($order->grand_total) }}
                                </td>
                                <td>
                                    <span
                                        class="text-capitalize">{{ translate(str_replace('_', ' ', $order->delivery_status)) }}</span>
                                </td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @can('view_orders')
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('admin.seller_orders.show', $order->id) }}"
                                            title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endcan
                                    @can('invoice_download')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            title="{{ translate('Print Invoice') }}" href="javascript:void(0)"
                                            onclick="print_invoice('{{ route('orders.invoice.print', $order->id) }}')">
                                            <i class="las la-print"></i>
                                        </a>
                                    @endcan
                                    @can('invoice_download')
                                        <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            href="{{ route('orders.invoice.download', $order->id) }}"
                                            title="{{ translate('Download Invoice') }}">
                                            <i class="las la-download"></i>
                                        </a>
                                    @endcan
                                    @can('delete_orders')
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('orders.destroy', $order->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $orders->appends(request()->input())->links() }}
                </div>
            </div>
        </form>

    </div>
@endsection

@section('modal')
    @include('backend.inc.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
    @include('modals.bulk_paid_modal')
    @include('modals.bulk_delivered_modal')
    @include('modals.bulk_cancelled_modal')
@endsection

@section('script')
    <script type="text/javascript">
        //select all items or bulk delete
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }

        //end of bulk delete
        function sort_orders(el) {
            $('#sort_orders').submit();
        }

        function print_invoice(url) {
            var h = $(window).height();
            var w = $(window).width();
            window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
        }

        // bulk paid orders
        function bulk_paid() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-paid') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }

        // bulk delivered orders
        function bulk_delivered() {

            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-delivered') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
        // bulk cancelled orders
        function bulk_cancelled() {

            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-cancelled') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
