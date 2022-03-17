@php
    $unique_id = (auth()->check()) ? auth()->user()->id : rand(1111,99999);
@endphp
<form action="{{route('frontend.order_payment')}}" method="post" id="midtrans_payment_form" class="midtrans_payment_form d-none">
    @csrf
    <input type="hidden" name="amount" value="{{ $total_amount }}">
    <input type="hidden" name="ref_no" value="{{ rand(1111,99999).'-'.date('y-m-d').'-'.$unique_id }}">

    <input type="hidden" name="method" value="Midtrans">

    <button type="submit" class="btn_1 order_submit_btn" id="midtrans_btn">{{__('defaultTheme.process_to_payment')}}</button>
</form>