@extends('addon:multivendor::seller.layouts.app')

@section('content')
    @php
        $shop = auth()->user()->shop;
    @endphp

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h1 class="h3">{{ translate('All products') }}</h1>
            </div>
            @if (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) == 'active' &&
                    $shop->products->count() < $shop->product_upload_limit)
                <div class="col-md-8 text-md-right">
                    <a href="{{ route('seller.digitalproducts.create') }}" class="btn btn-primary">
                        <span>{{ translate('Add New Digital Product') }}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>


    <div class="card">
        <form class="" id="sort_digital_products" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Digital Products') }}</h5>
            </div>
            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{ translate('Bulk Action') }}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item confirm-alert" href="javascript:void(0)" data-target="#bulk-delete-modal">
                        {{ translate('Delete selection') }}</a>
                </div>
            </div>
            <div class="col-md-4">
                
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type name & Enter') }}">
                    </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </th>
                        <th width="30%">{{ translate('Name') }}</th>
                        <th data-breakpoints="lg">{{ translate('Photo') }}</th>
                        <th data-breakpoints="lg">{{ translate('Price') }}</th>
                        <th data-breakpoints="lg">{{ translate('Published') }}</th>
                        <th data-breakpoints="lg">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                        <tr>
                            <td>
                                <div class="form-group d-inline-block">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-one" name="id[]"
                                            value="{{ $product->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </td>
                            <td><a href="{{ route('product', $product->slug) }}" class="text-muted"
                                    target="_blank"><b>{{ $product->getTranslation('name') }}</b></a></td>
                            <td>
                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image" class="w-50px">
                            </td>
                            <td>{{ number_format($product->highest_price, 2) }}</td>

                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox"
                                        <?php if ($product->published == 1) {
                                            echo 'checked';
                                        } ?>>
                                    <span class="slider round"></span>
                                </label>
                            </td>

                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('seller.digitalproducts.edit', $product->id) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>

                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('seller.digitalproducts.destroy', $product->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>

                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('seller.digitalproducts.download', encrypt($product->id)) }}"
                                    title="{{ translate('Download') }}">
                                    <i class="las la-download"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $products->appends(request()->input())->links() }}
            </div>
        </div>
    </form>

    </div>
@endsection

@section('modal')
        <!-- Delete Modal -->
        @include('backend.inc.delete_modal')
        <!-- Bulk Delete modal -->
        @include('modals.bulk_delete_modal')
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
            var data = new FormData($('#sort_digital_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('seller.bulk-product-delete') }}",
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

        $(document).ready(function() {
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('product.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
