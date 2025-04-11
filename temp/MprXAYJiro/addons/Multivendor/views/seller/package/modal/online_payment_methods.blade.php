<div class="modal fade" id="select_payment_method_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('Purchase Your Package') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="" id="package_payment_form" action="{{ route('seller.packages.purchase') }}"
                method="post">
                @csrf
                <input type="hidden" name="seller_package_id" value="" id="seller_package_id">
                <div class="modal-body">
                    <div class="mb-2 fs-15">
                        <label>{{ translate('Select a Payment Method') }}</label>
                    </div>
                    <div class="row">
                        @if (get_setting('paypal_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="paypal" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/paypal.png') }}"
                                            class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('Paypal') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('stripe_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="stripe" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/stripe.png') }}"
                                            class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('Stripe') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('sslcommerz_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="sslcommerz" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/sslcommerz.png') }}"
                                            class="img-fluid w-100">
                                        <span
                                            class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('sslcommerz') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('paystack_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="paystack" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/paystack.png') }}"
                                            class="img-fluid w-100">
                                        <span
                                            class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('Paystack') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('flutterwave_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="flutterwave" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/flutterwave.png') }}"
                                            class="img-fluid w-100">
                                        <span
                                            class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('Flutterwave') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('razorpay_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="razorpay" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/razorpay.png') }}"
                                            class="img-fluid w-100">
                                        <span
                                            class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('Razorpay') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('paytm_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="paytm" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/paytm.png') }}"
                                            class="img-fluid w-100">
                                        <span class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('Paytm') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('myfatoorah_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="myfatoorah" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/myfatoorah.png') }}"
                                            class="img-fluid w-100">
                                        <span
                                            class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('MyFatoorah') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                        @if (get_setting('phonepe_payment') == 1)
                            <div class="col-6 col-md-4">
                                <label class="aiz-megabox d-block">
                                    <input type="radio" name="payment_option" value="phonepe" required>
                                    <span class="d-block p-3 aiz-megabox-elem text-center">
                                        <img src="{{ static_asset('assets/img/cards/phonepe.png') }}"
                                            class="img-fluid w-100">
                                        <span
                                            class="fw-700 fs-13 mt-2 d-inline-block">{{ translate('phonepe') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif
                    </div>
                    <div class="form-group text-right mt-4">
                        <button type="button" class="btn btn-secondary transition-3d-hover mr-1"
                            data-dismiss="modal">{{ translate('cancel') }}</button>
                        <button type="submit"
                            class="btn btn-primary transition-3d-hover mr-1">{{ translate('Confirm') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
