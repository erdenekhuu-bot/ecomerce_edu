@extends('backend.layouts.app')

@section('content')
    {{-- <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Cash Payment Activation') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="col-from-label">{{ translate('Activation') }}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'cash_payment')"
                                    @if (get_setting('cash_payment') == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div> --}}
    {{-- <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                    </div> --}}
    {{-- </form>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">Pocket Pay Мэдээлэл</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Activation') }}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="updateSettings(this, 'pocket_payment')"
                                        @if (get_setting('pocket_payment') == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="POCKET_USERNAME">
                            <div class="col-md-4">
                                <label class="col-from-label">Username</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="POCKET_USERNAME"
                                    value="{{ env('POCKET_USERNAME') }}" placeholder="POCKET USERNAME" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="POCKET_PASSWORD">
                            <div class="col-md-4">
                                <label class="col-from-label">Password</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="POCKET_PASSWORD"
                                    value="{{ env('POCKET_PASSWORD') }}" placeholder="POCKET PASSWORD" required>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">Store Pay Мэдээлэл</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Activation') }}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="updateSettings(this, 'storepay_payment')"
                                        @if (get_setting('storepay_payment') == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="STOREPAY_USERNAME">
                            <div class="col-md-4">
                                <label class="col-from-label">Username</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="STOREPAY_USERNAME"
                                    value="{{ env('STOREPAY_USERNAME') }}" placeholder="STOREPAY USERNAME" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="STOREPAY_PASSWORD">
                            <div class="col-md-4">
                                <label class="col-from-label">Password</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="STOREPAY_PASSWORD"
                                    value="{{ env('STOREPAY_PASSWORD') }}" placeholder="STOREPAY PASSWORD" required>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Paypal --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">Qpay Мэдээлэл</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Activation') }}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="updateSettings(this, 'qpay_payment')"
                                        @if (get_setting('qpay_payment') == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="QPAY_USERNAME">
                            <div class="col-md-4">
                                <label class="col-from-label">Username</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="QPAY_USERNAME"
                                    value="{{ env('QPAY_USERNAME') }}" placeholder="QPAY USERNAME" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="QPAY_PASSWORD">
                            <div class="col-md-4">
                                <label class="col-from-label">Password</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="QPAY_PASSWORD"
                                    value="{{ env('QPAY_PASSWORD') }}" placeholder="QPAY PASSWORD" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="QPAY_INVOICE_NO">
                            <div class="col-md-4">
                                <label class="col-from-label">Invoice NO</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="QPAY_INVOICE_NO"
                                    value="{{ env('QPAY_INVOICE_NO') }}" placeholder="QPAY INVOICE NO" required>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sslcommerz --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header ">
                    <h5 class="mb-0 h6">Голомт банк Корпорэйт Мэдээлэл</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Activation') }}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="updateSettings(this, 'golomt_payment')"
                                        @if (get_setting('golomt_payment') == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="GOLOMT_USERNAME">
                            <div class="col-md-4">
                                <label class="col-from-label">Username</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="GOLOMT_USERNAME"
                                    value="{{ env('GOLOMT_USERNAME') }}" placeholder="Golomt Username" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="GOLOMT_NAME">
                            <div class="col-md-4">
                                <label class="col-from-label">Organization name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="GOLOMT_NAME"
                                    value="{{ env('GOLOMT_NAME') }}" placeholder="Golomt Organization Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="GOLOMT_CLIENT_ID">
                            <div class="col-md-4">
                                <label class="col-from-label">Client ID</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="GOLOMT_CLIENT_ID"
                                    value="{{ env('GOLOMT_CLIENT_ID') }}" placeholder="Golomt Client ID" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="GOLOMT_IV_KEY">
                            <div class="col-md-4">
                                <label class="col-from-label">IV Key</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="GOLOMT_IV_KEY"
                                    value="{{ env('GOLOMT_IV_KEY') }}" placeholder="Golomt IV key" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="GOLOMT_SESSION_KEY">
                            <div class="col-md-4">
                                <label class="col-from-label">Session key</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="GOLOMT_SESSION_KEY"
                                    value="{{ env('GOLOMT_SESSION_KEY') }}" placeholder="Golomt Session key" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="GOLOMT_PASSWORD">
                            <div class="col-md-4">
                                <label class="col-from-label">Password</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="GOLOMT_PASSWORD"
                                    value="{{ env('GOLOMT_PASSWORD') }}" placeholder="Golomt password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="GOLOMT_ACCOUNT_NUMBER">
                            <div class="col-md-4">
                                <label class="col-from-label">Account number</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="GOLOMT_ACCOUNT_NUMBER"
                                    value="{{ env('GOLOMT_ACCOUNT_NUMBER') }}" placeholder="Golomt account number"
                                    required>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function updateSettings(el, type) {
            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
            $.post('{{ route('settings.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == '1') {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
