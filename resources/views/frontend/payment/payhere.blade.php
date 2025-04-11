<html>
<body>
<form style="display: none" method="post" action=" {{get_setting('payhere_sandbox') == 1 ? 'https://sandbox.payhere.lk/pay/checkout' : 'https://www.payhere.lk/pay/checkout' }}" id="payhere-checkout-form">   
    <input type="hidden" name="merchant_id" value="{{ env('PAYHERE_MERCHANT_ID') }}">    <!-- Replace your Merchant ID -->
    <input type="hidden" name="return_url" value="{{route('payhere.return')}}">
    <input type="hidden" name="cancel_url" value="{{route('payhere.cancel')}}">
    <input type="hidden" name="notify_url" value="{{route('payhere.notify')}}">  
    <br><br>Item Details<br>
    <input type="text" name="order_id" value="{{$order_id}}">
    <input type="text" name="items" value="{{$payment_type}}"><br>
    <input type="text" name="currency" value="{{ env('PAYHERE_CURRENCY') }}">
    {{-- <input type="text" name="recurrence" value="1 Month">
    <input type="text" name="duration" value="Forever"> --}}
    <input type="text" name="amount" value="{{$amount}}">  
    <br><br>Customer Details<br>
    <input type="text" name="first_name" value="{{$first_name}}">
    <input type="text" name="last_name" value="{{$last_name}}"><br>
    <input type="text" name="email" value="{{$email}}">
    <input type="text" name="phone" value="{{$phone}}"><br>
    <input type="text" name="address" value="{{$address}}">
    <input type="text" name="city" value="{{$city}}">
    <input type="hidden" name="country" value="Sri Lanka">
    <input type="hidden" name="hash" value="{{$hash_value}}">    <!-- Replace with generated hash -->
    <input type="submit" value="Buy Now">   
</form> 


<script type="text/javascript">
    var payhere_checkout_form =  document.getElementById('payhere-checkout-form');
    payhere_checkout_form.submit();
 </script>
</body>
</html>