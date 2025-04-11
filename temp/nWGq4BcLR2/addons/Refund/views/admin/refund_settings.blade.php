@extends('backend.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Set Refund Time') }}</h5>
                </div>
                <form class="form-horizontal" action="{{ route('settings.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="refund_request_time_period">
                            <label class="col-lg-4 col-from-label">{{ translate('Set Time Period for Refund Request Generation') }}</label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="number" min="0" step="1" value="{{ get_setting('refund_request_time_period') }}" placeholder=""name="refund_request_time_period" class="form-control" required>
                                    <div class="input-group-append"><span class="input-group-text">{{ translate('Days') }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Select Order Status') }}</h5>
                </div>
                <form class="form-horizontal" action="{{ route('settings.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="refund_request_order_status">
                            <label
                                class="col-lg-4 col-from-label">{{ translate('Select Order Status for Enabling Refund Request') }}</label>
                            <div class="col-lg-8">
                                <select name="refund_request_order_status[]" class="form-control aiz-selectpicker" multiple
                                    data-live-search="true" data-selected-text-format="count" data-container="body"
                                    data-selected="{{ get_setting('refund_request_order_status') }}" required>
                                    <option value="confirmed">{{ translate('Confirmed') }}</option>
                                    <option value="processed">{{ translate('Processed') }}</option>
                                    <option value="shipped">{{ translate('Shipped') }}</option>
                                    <option value="delivered">{{ translate('Delivered') }}</option>
                                    <option value="cancelled">{{ translate('Cancel') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Refund Reasons') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row gutters-10">
                            <div class="col-lg-3">
                                <label class="from-label d-block">{{ translate('Refund Reason Types') }}</label>
                            </div>
                            <div class="col-lg-9">
                                <div class="home-banner-1-target">
                                    <input type="hidden" name="types[]" value="refund_reason_types">
                                    @if (get_setting('refund_reason_types'))
                                        @foreach (json_decode(get_setting('refund_reason_types'), true) as $key => $value)
                                            <div class="row">
                                                <div class="col mb-2">
                                                    <input type="text" placeholder="" name="refund_reason_types[]"
                                                        value="{{ json_decode(get_setting('refund_reason_types'), true)[$key] }}"
                                                        class="form-control">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                        data-content='<div class="row gutters-5">
                                                        <div class="col mb-2">
                                                            <input type="text" placeholder="" name="refund_reason_types[]" class="form-control">
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                                <i class="las la-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>' data-target=".home-banner-1-target">
                                        {{ translate('Add New') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
