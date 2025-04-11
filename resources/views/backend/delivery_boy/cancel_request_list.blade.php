@extends('backend.layouts.app')

@section('content')
    <div class="card shadow-none rounded-0 border">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark">{{ translate('All Cancel Request') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead class="text-gray fs-12">
                    <tr>
                        <th class="pl-0">#</th>
                        <th>{{translate('Code')}}</th>
                        <th>{{translate('Request By')}}</th>
                        <th>{{translate('Request At')}}</th>
                        <th class="text-right pr-0">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody class="fs-14">
                    @foreach($cancel_requests as $key => $cancel_request)
                    <tr>
                        <!-- count -->
                        <td class="pl-0" style="vertical-align: middle;">
                            {{ ($key+1) + ($cancel_requests->currentPage() - 1) * $cancel_requests->perPage() }}
                        </td>
                        <!-- code -->
                        <td style="vertical-align: middle;">
                            {{-- <a href="{{route('orders.show', $cancel_request->id)}}">{{ $cancel_request->combined_order->code }}</a> --}}
                            {{ $cancel_request->combined_order->code }}
                        </td>
                        <!-- Delivery boy -->
                        <td style="vertical-align: middle;">
                            {{ $cancel_request->delivery_boy->name }}
                        </td>
                        <!-- Date -->
                        <td class="text-secondary" style="vertical-align: middle;">
                            {{ date('d-m-Y h:i A', strtotime($cancel_request->cancel_request_at)) }}
                        </td>
                        <!-- Options -->
                        <td class="text-right pr-0" style="vertical-align: middle;">
                            <a href="{{route('orders.show', $cancel_request->id)}}" class="btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white" title="{{ translate('Order Details') }}">
                                <i class="las la-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="aiz-pagination mt-2">
                {{ $cancel_requests->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
