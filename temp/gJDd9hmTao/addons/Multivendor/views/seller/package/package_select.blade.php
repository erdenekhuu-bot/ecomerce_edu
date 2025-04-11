@extends('addon:multivendor::seller.layouts.app')

@section('content')
@php
    $shop = auth()->user()->shop;
@endphp
<section class="py-4 py-lg-5">
    <div class="container">
        @if (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'no_package' )
            <div class="alert alert-danger">
                {{ translate("You don't have any active package") }}
            </div>
        @elseif (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'expired' )
            <div class="alert alert-danger">
                {{ translate('Your current package') }}
                <span class="fw-600">{{ $shop->seller_package->name }}</span>
                {{ translate('has been expired at') }}
                {{ $shop->package_invalid_at }}
            </div>
        @else
            <div class="alert alert-info">
                {{ translate('Your current package') }}
                <span class="fw-600">{{ $shop->seller_package->name }}</span>
                {{ translate('will expire at') }}
                {{ $shop->package_invalid_at }}
            </div>
        @endif
        
        <div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-10 justify-content-center">
            @foreach ($seller_packages as $key => $seller_package)
                <div class="col">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="text-center mb-4 mt-3">
                                <img class="mw-100 mx-auto mb-4" src="{{ uploaded_asset($seller_package->logo) }}" height="100">
                                <h5 class="mb-3 h5 fw-600">{{$seller_package->getTranslation('name')}}</h5>
                            </div>
                            <ul class="list-group list-group-raw fs-15">
                                <li class="list-group-item py-2">
                                    <i class="las la-check text-success mr-2"></i>{{ $seller_package->product_upload_limit }} {{translate('Product Upload Limit')}}
                                </li>
                            </ul>
                            <ul class="list-group list-group-raw fs-15 mb-5">
                                <li class="list-group-item py-2">
                                    <i class="las la-check text-success mr-2"></i>{{ $seller_package->commission }}% {{translate('Commission')}}
                                </li>
                            </ul>
                            <div class="mb-5 d-flex align-items-center justify-content-center">
                                @if ($seller_package->amount == 0)
                                    <span class="h2 fw-600 lh-1 mb-0">{{ translate('Free') }}</span>
                                @else
                                    <span class="h2 fw-600 lh-1 mb-0">{{format_price($seller_package->amount)}}</span>
                                @endif
                                <span class="text-secondary border-left ml-2 pl-2">{{$seller_package->duration}} {{translate('Days')}}</span>
                            </div>

                            <div class="text-center">
                                @if ($seller_package->amount == 0)
                                    <button class="btn btn-primary fw-600" onclick="get_free_package({{ $seller_package->id}})">{{ translate('Free Package')}}</button>
                                @else
                                    <button class="btn btn-primary fw-600" onclick="select_payment_type_modal({{ $seller_package->id}})">{{ translate('Purchase Package')}}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@section('modal')
    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="select_payment_type_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Select Payment Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="package_id" name="package_id" value="">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Payment Type') }}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control aiz-selectpicker" onchange="payment_type(this.value)"
                                    data-minimum-results-for-search="Infinity" id="payment_type_option">
                                    <option value="">{{ translate('Select One') }}</option>
                                    <option value="online">{{ translate('Online payment') }}</option>
                                    <option value="offline">{{ translate('Offline payment') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-primary transition-3d-hover mr-1"
                            id="select_type_cancel" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('addon:multivendor::seller.package.modal.online_payment_methods')

    <!-- offline payment Modal -->
    <div class="modal fade" id="offline_seller_package_purchase_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{ translate('Offline Package Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="offline_seller_package_purchase_modal_body"></div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
// for showing payment type modal
        function select_payment_type_modal(package_id){
            $('#seller_package_id').val(package_id)
            $('#select_payment_type_modal').modal('show');
            $("#payment_type_option").prop('selectedIndex', 0).change();
        }

        function payment_type(type) {
            var package_id = $('#seller_package_id').val();
            if (type == 'online') {
                $("#select_type_cancel").click();
                $('#select_payment_method_modal').modal('show');
            } else if (type == 'offline') {
                $("#select_type_cancel").click();
                $.post('{{ route('seller.offline_seller_package_purchase_modal') }}', {
                    _token: '{{ csrf_token() }}',
                    package_id: package_id
                }, function(data) {
                    $('#offline_seller_package_purchase_modal_body').html(data);
                    $('#offline_seller_package_purchase_modal').modal('show');
                });
            }
        }


        function get_free_package(id){
           
            $('input[name=seller_package_id]').val(id);
            $('#package_payment_form').submit();
        }

    </script>
@endsection
