@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h1 class="h3">{{ translate('Seller Products') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Seller Products') }}</h5>
                </div>
                @if (get_setting('product_manage_by_admin') == 1)
                    @can('product_delete')
                        <div class="dropdown mb-2 mb-md-0">
                            <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                                {{ translate('Bulk Action') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item confirm-alert" href="javascript:void(0)"
                                    data-target="#bulk-delete-modal">
                                    {{ translate('Delete selection') }}</a>
                            </div>
                        </div>
                    @endcan
                @endif
                <div class="col-md-2 ml-auto">
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="type" id="type"
                        onchange="sort_products()">
                        <option value="">{{ translate('Sort By') }}</option>
                        <option value="rating,desc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'desc') selected @endif @endisset>
                            {{ translate('Rating (High > Low)') }}</option>
                        <option value="rating,asc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'asc') selected @endif @endisset>
                            {{ translate('Rating (Low > High)') }}</option>
                        <option value="num_of_sale,desc"
                            @isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'desc') selected @endif
                            @endisset>
                            {{ translate('Num of Sale (High > Low)') }}</option>
                        <option value="num_of_sale,asc"
                            @isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'asc') selected @endif
                            @endisset>
                            {{ translate('Num of Sale (Low > High)') }}</option>
                        <option value="lowest_price,desc"
                            @isset($col_name, $query) @if ($col_name == 'lowest_price' && $query == 'desc') selected @endif
                            @endisset>
                            {{ translate('Base Price (High > Low)') }}</option>
                        <option value="highest_price,asc"
                            @isset($col_name, $query) @if ($col_name == 'highest_price' && $query == 'asc') selected @endif
                            @endisset>
                            {{ translate('Base Price (Low > High)') }}</option>
                    </select>
                </div>
                <div class="col-md-2 ml-auto">
                    <select id="demo-ease" class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="shop_id"
                        onchange="sort_products()" data-selected="{{ $shop_id }}">
                        <option value="">{{ translate('Choose Shop') }}</option>
                        @foreach (\App\Models\Shop::with('user')->get() as $key => $shop)
                            @if ($shop->user->user_type != 'admin')
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                            @isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type & Enter') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            @if (auth()->user()->can('delete_products') && get_setting('product_manage_by_admin') == 1)
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
                                <th class="w-40px">#</th>
                            @endif
                            <th class="col-xl-2">{{ translate('Name') }}</th>
                            <th data-breakpoints="md">{{ translate('Info') }}</th>
                            <th data-breakpoints="md" width="20%">{{ translate('Categories') }}</th>
                            <th data-breakpoints="md">{{ translate('Brand') }}</th>
                            @if (get_setting('product_approve_by_admin') == 1)
                                <th data-breakpoints="md">{{ translate('Approval') }}</th>
                            @endif
                            <th data-breakpoints="md">{{ translate('Published status') }}</th>
                            @if (get_setting('product_manage_by_admin') == 1)
                                <th data-breakpoints="md" class="text-right">{{ translate('Options') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                @if (auth()->user()->can('delete_products') && get_setting('product_manage_by_admin') == 1)
                                    <td>
                                        <div class="form-group d-inline-block">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]"
                                                    value="{{ $product->id }}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                @endif
                                <td>
                                    <a href="{{ route('product', $product->slug) }}" target="_blank"
                                        class="text-reset d-block">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image"
                                                class="size-60px size-xxl-80px mr-2"
                                                onerror="this.onerror=null;this.src='{{ static_asset('/assets/img/placeholder.jpg') }}';" />
                                            <span class="flex-grow-1 minw-0">
                                                <div class=" text-truncate-2 fs-12">
                                                    {{ $product->getTranslation('name') }}</div>
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <div><span>{{ translate('Rating') }}</span>: <span
                                                class="rating rating-sm my-2">{{ renderStarRating($product->rating) }}</span>
                                        </div>
                                        <div><span>{{ translate('Toal Sold') }}</span>: <span
                                                class="fw-600">{{ $product->num_of_sale }}</span></div>
                                        <div>
                                            <span>{{ translate('Price') }}</span>:
                                            @if ($product->highest_price != $product->lowest_price)
                                                <span class="fw-600">{{ format_price($product->lowest_price) }} -
                                                    {{ format_price($product->highest_price) }}</span>
                                            @else
                                                <span class="fw-600">{{ format_price($product->lowest_price) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @foreach ($product->categories as $category)
                                        <span
                                            class="badge badge-inline badge-md bg-soft-dark mb-1">{{ $category->getTranslation('name') }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($product->brand)
                                        <div class="h-50px w-100px d-flex align-items-center justify-content-center">
                                            <img src="{{ uploaded_asset($product->brand->logo) }}"
                                                alt="{{ translate('Brand') }}" class="mw-100 mh-100"
                                                onerror="this.onerror=null;this.src='{{ static_asset('/assets/img/placeholder.jpg') }}';" />
                                        </div>
                                    @else
                                        <span>{{ translate('No brand') }}</span>
                                    @endif
                                </td>
                                @if (get_setting('product_approve_by_admin') == 1)
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_approved(this)" value="{{ $product->id }}"
                                                type="checkbox" <?php if ($product->approved == 1) {
                                                    echo 'checked';
                                                } ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                @endif
                                <td>
                                    @if ($product->published == 1)
                                        <span class="badge badge-inline badge-success">{{ translate('Published') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ translate('Unpublished') }}</span>
                                    @endif
                                </td>
                                @if (get_setting('product_manage_by_admin') == 1)
                                    <td class="text-right">
                                        @can('view_products')
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                href="{{ route('product.show', $product->id) }}"
                                                title="{{ translate('View') }}">
                                                <i class="las la-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit_products')
                                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                                href="{{ route('product.edit', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_products')
                                            <a href="#"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('product.destroy', $product->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                @endif
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
    <!-- Delete modal -->
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
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-product-delete') }}",
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

        function sort_products(el) {
            $('#sort_products').submit();
        }

        function update_approved(el) {
            if (el.checked) {
                var approved = 1;
            } else {
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                approved: approved
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
