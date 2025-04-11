@extends('backend.layouts.app')

@section('content')
    <style>
        #map {
            width: 100%;
            height: 250px;
        }
    </style>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Payment Configuration') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row" id="commission_div">
                            <label class="col-sm-4 col-from-label">{{ translate('Commission Rate') }}</label>
                            <div class="col-sm-8">
                                <input type="hidden" name="types[]" value="delivery_boy_commission">
                                <div class="input-group">
                                    <input type="number" name="delivery_boy_commission" class="form-control"
                                        value="{{ get_setting('delivery_boy_commission') ? get_setting('delivery_boy_commission') : '0' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">
                                            {{ \App\Models\Currency::find(get_setting('system_default_currency'))->code }}
                                        </span>
                                    </div>
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

@section('script')

@endsection
