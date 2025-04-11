@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Zone Information') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('zones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Name') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Countries') }}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="countries[]"
                                data-title="{{ translate('Select Countries') }}" data-live-search="true"
                                data-max-options="10" data-selected-text-format="count" required multiple
                                data-selected-text-format="count" data-actions-box="true" onchange="getStates()">
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('States') }}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="states[]"
                                data-title="{{ translate('Select States') }}" data-live-search="true" data-max-options="50"
                                data-selected-text-format="count" multiple data-selected-text-format="count"
                                data-actions-box="true" onchange="getCities()">
                                {{-- @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{ translate('Standard Delivery Cost') }}
                        </label>
                        <div class="col-md-9">
                            <input type="number" step="0.01" value="0" name="standard_delivery_cost"
                                class="form-control" id="standard_delivery_cost"
                                placeholder="{{ translate('Standard Delivery Cost') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{ translate('Express Delivery Cost') }}
                        </label>
                        <div class="col-md-9">
                            <input type="number" step="0.01" value="0" name="express_delivery_cost"
                                class="form-control" id="express_delivery_cost"
                                placeholder="{{ translate('Express Delivery Cost') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        // get states list based on selected countryies
        function getStates() {
            let selectedCountriesIds = $('select[name="countries[]"]').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let result = $.ajax({
                type: "post",
                url: "{{ route('get_states') }}",
                data: {
                    _token: CSRF_TOKEN,
                    selectedCountriesIds: selectedCountriesIds
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        var element = $('[name="states[]"]');
                        element.html(obj).selectpicker('refresh');
                    }
                }
            });
        }
        // get city list based on selected states
        function getCities() {
            let selectedStatesIds = $('select[name="states[]"]').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let result = $.ajax({
                type: "post",
                url: "{{ route('get_cities') }}",
                data: {
                    _token: CSRF_TOKEN,
                    selectedStatesIds: selectedStatesIds
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        var element = $('[name="cities[]"]');
                        element.html(obj).selectpicker('refresh');
                    }
                }
            });
        }
    </script>
@endsection
