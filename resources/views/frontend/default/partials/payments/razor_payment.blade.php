<form action="{{route('frontend.order_payment')}}" method="POST" id="razor_form" class="razor_form d-none">
    <input type="hidden" name="method" value="RazorPay">
    <input type="hidden" name="amount" value="{{ $total_amount * 100 }}">


    <button type="submit" class="btn_1 order_submit_btn" id="razorpay_btn">{{ __('defaultTheme.process_to_payment') }}</button>
    @csrf
    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ env('RAZOR_KEY') }}"
        data-amount="{{ $total_amount * 100 }}" data-name="{{str_replace('_', ' ',config('app.name') ) }}"
        data-description="Order Total" data-image="{{asset(asset_path(app('general_setting')->favicon))}}"
        data-prefill.name="{{$address->name}}"
        data-prefill.email="{{$address->email}}"
        data-theme.color="#ff7529">
    </script>
</form>
