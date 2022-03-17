<form action="{{route('frontend.order_payment')}}" method="post" class="paypal_form_payment_23 d-none">
    @csrf
    <input type="hidden" name="method" value="Paypal">
    <input type="hidden" name="purpose" value="order_payment">
    <input type="hidden" name="amount" value="{{ $total_amount }}">

    <button type="submit" class="btn_1 order_submit_btn paypal_btn d-none">{{ __('defaultTheme.process_to_payment') }}</button>
</form>