@extends('addon:multivendor::seller.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form class="" id="sort_refund_requests" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Refund Requests') }}</h5>
                        </div>
                        <div class="col-xl-2 col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search"
                                    @isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type Order code & hit Enter') }}">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Order Code') }}</th>
                                <th>{{ translate('Products') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Admin Approval') }}</th>
                                <th class="text-right" width="15%">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($refund_requests as $key => $refund_request)
                                <tr>
                                    <td>{{ $key + 1 + ($refund_requests->currentPage() - 1) * $refund_requests->perPage() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('seller.orders_show', $refund_request->order_id) }}">
                                            {{ $refund_request->order->combined_order->code }}
                                        </a>
                                    </td>
                                    <td>
                                        @foreach ($refund_request->refundRequestItems as $key => $refundRequestItem)
                                            <div class="media">
                                                <img src="{{ uploaded_asset($refundRequestItem->orderDetail->product->thumbnail_img ?? '') }}"
                                                    class="size-60px mr-3">
                                                <div class="media-body">
                                                    <h4 class="fs-14 fw-400">
                                                        {{ $refundRequestItem->orderDetail->product->name ?? ''}} -
                                                        ({{ translate('Quantity') . ': ' . $refundRequestItem->quantity }})
                                                    </h4>
                                                    @if ($refundRequestItem->orderDetail->variation)
                                                        <div>
                                                            @foreach ($refundRequestItem->orderDetail->variation->combinations as $combination)
                                                                <span class="mr-2">
                                                                    <span
                                                                        class="opacity-50">{{ $combination->attribute->name }}</span>:
                                                                    {{ $combination->attribute_value->name ?? ''}}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ format_price($refund_request->amount) }}</td>
                                    <td>
                                        @if ($refund_request->seller_approval == 0)
                                            <span
                                                class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                        @elseif($refund_request->seller_approval == 1)
                                            <span
                                                class="badge badge-inline badge-success">{{ translate('Accepted') }}</span>
                                        @elseif($refund_request->seller_approval == 2)
                                            <span
                                                class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($refund_request->admin_approval == 0)
                                            <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                        @elseif($refund_request->admin_approval == 1)
                                            <span
                                                class="badge badge-inline badge-success">{{ translate('Accepted') }}</span>
                                        @elseif($refund_request->admin_approval == 2)
                                            <span
                                                class="badge badge-inline badge-danger">{{ translate('Rejected') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            onclick="show_refund_request_info('{{ $refund_request->id }}');"
                                            title="{{ translate('Show Refund Info') }}" href="javascript:void(0)">
                                            <i class="las la-eye"></i>
                                        </a>
                                        @if ($refund_request->admin_approval == 0 && $refund_request->seller_approval == 0)
                                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                onclick="accept_refund_request('{{ route('seller.refund_request.accept', $refund_request->id) }}')"
                                                title="{{ translate('Accept Refund Request') }}"
                                                href="javascript:void(0)">
                                                <i class="las la-check"></i>
                                            </a>
                                            <a class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                                onclick="reject_refund_request('{{ route('seller.refund_request.reject', $refund_request->id) }}')"
                                                title="{{ translate('Reject Refund Request') }}"
                                                href="javascript:void(0)">
                                                <i class="las la-times"></i>
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination aiz-pagination-center">
                        {{ $refund_requests->appends(request()->input())->links() }}
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
                    <h4 class="modal-title h6">{{ translate('Accpt Refund Request.') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <p class='fs-14'>{{ translate('Do you really want to accept this refund Request?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-2"
                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <a href="" id="accept_refund_request_link" class="btn btn-primary">{{ translate('Accept') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject refund request Modal --}}
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
                    <a href="" id="reject_refund_request_link" class="btn btn-primary">{{ translate('Reject') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function sort_refund_requests(el) {
            $('#sort_refund_requests').submit();
        }

        function show_refund_request_info(id) {
            $.post('{{ route('seller.refund_request.view_details') }}', {
                _token: '{{ @csrf_token() }}',
                id: id
            }, function(data) {
                $('#refund-request-info-modal-content').html(data);
                $('#refund_request_info_modal').modal('show', {
                    backdrop: 'static'
                });
            });
        }

        function accept_refund_request(url) {
            $('#accept_refund_request_modal').modal('show');
            document.getElementById('accept_refund_request_link').setAttribute('href', url);
        }

        function reject_refund_request(url) {
            $('#reject_refund_request_modal').modal('show');
            document.getElementById('reject_refund_request_link').setAttribute('href', url);
        }
    </script>
@endsection
