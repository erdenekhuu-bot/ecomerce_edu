@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
    @endphp
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Нийт дүн</h5>
                    </div>
                    <div class="card-body text-right" style="font-weight: bold; font-size:18px">
                        {{format_price($orders->sum('grand_total'))}}
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Нийт захиалгын тоо</h5>
                    </div>
                    <div class="card-body text-right" style="font-weight: bold; font-size:18px">
                        {{count($orders)}}
                    </div>
                </div>
            </div>
        </div>
    <div class="card">
       
            <div class="card-header row gutters-5">
                
                <form class="d-flex row col-xl-11" id="sort_orders" action="" method="GET">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>
                    </div>

                    {{-- <div class="dropdown mb-2 mb-md-0">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="bulk-action-list">
                            @can('delete_orders')
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item confirm-alert" href="javascript:void(0)"
                                        data-target="#bulk-delete-modal">
                                        {{ translate('Delete selection') }}</a>
                                </div>
                            @endcan

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item confirm-alert" href="javascript:void(0)"
                                    data-target="#bulk-delivered-modal">
                                    {{ translate('Order Delivered') }}</a>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item confirm-alert" href="javascript:void(0)" data-target="#bulk-paid-modal">
                                    {{ translate('Order Paid') }}</a>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-xl-2 col-md-3 ml-auto">
                        <select class="form-control aiz-selectpicker" name="sale_id" id="sale_id"
                            data-selected="{{$sale_id}}">
                            <option value="">Борлуулагч сонгох</option>
                            @foreach (\App\Models\User::join('roles', 'roles.id' , '=', 'users.role_id')->where('roles.name', 'Sales Manager')->select('users.*')->get() as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-3">
                        <select class="form-control aiz-selectpicker" name="user_id" id="user_id"
                            data-selected="{{ $user_id }}">
                            <option value="">Хэрэглэгч сонгох</option>
                            @foreach (\App\Models\User::where('user_type', 'customer')->get() as $user)
                                <option value="{{$user->id}}" {{$user_id == $user->id ? "selected" : ""}}>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-3">
                        <input type="text" class="form-control aiz-date-range" name="date_range" id="date_range"
                            placeholder="Select Date" data-time-picker="true" data-format="Y-MM-DD"
                            data-separator=" to " autocomplete="off" value="{{$date_range}}">
                    </div>

                    <div class="col-xl-1 col-md-2">
                        <div class="input-group">
                            <button type="submit" class="btn btn-success w-100">Шүүх</button>
                        </div>
                    </div>
                </form>
                <div class="col-xl-1 col-md-1">
                    <div class="input-group">
                        <form action="{{route('reports.export')}}" method="GET">
                            <input type="hidden" name="user_id" value="{{$user_id}}">
                            <input type="hidden" name="sale_id" value="{{$sale_id}}">
                            <input type="hidden" name="date_range" value="{{$date_range}}">
                            <button type="submit" class="btn btn-warning w-100">Export</button>
                        </form>
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
                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="lg">{{ translate('Customer') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                            <th data-breakpoints="lg">{{ translate('Payment Status') }}</th>
                            <th data-breakpoints="lg">Борлуулагч</th>
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
                                    @if (addon_is_activated('multi_vendor'))
                                        <div>{{ translate('Package') }} {{ $order->code }} {{ translate('of') }}</div>
                                    @endif
                                    <div class="fw-600">{{ $order->combined_order->code ?? '' }}</div>
                                </td>
                                <td>
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td>
                                    @if ($order->user != null)
                                        {{ $order->user->name }}
                                    @else
                                        Guest ({{ $order->guest_id }})
                                    @endif
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
                                <td>
                                    {{$order->saler->name ?? "Хэрэглэгч"}}
                                </td>
                                <td class="text-right">
                                    @can('view_orders')
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('orders.show', $order->id) }}" title="{{ translate('View') }}">
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

    </div>
@endsection

@section('modal')
    <!--  Delete modal -->
    @include('backend.inc.delete_modal')
    <!-- Bulk  modal -->
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
        // bulk delete orders
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
        // Sorting Orders 
        function sort_orders(el) {
            $('#sort_orders').submit();
        }
        // print invoice
        function print_invoice(url) {
            var h = $(window).height();
            var w = $(window).width();
            window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
        }
    </script>
@endsection
