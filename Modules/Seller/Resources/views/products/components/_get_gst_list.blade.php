
@if($product->gstGroup)
@php
    $same_state_gst = json_decode($product->gstGroup->same_state_gst);
    $same_state_gst = (array)$same_state_gst;
    $outsite_state_gst = json_decode($product->gstGroup->outsite_state_gst);
    $outsite_state_gst = (array)$outsite_state_gst;
@endphp
<table class="table-borderless clone_line_table">
    <tr>
        <td><strong>{{__('Same State TAX/GST List')}}</strong></td>
    </tr>
    @foreach($same_state_gst as $gst_id => $percent)
        @php
            $gst = \Modules\GST\Entities\GstTax::find($gst_id);
        @endphp
        <tr>
            <td class="info_tbl">{{$gst->name}}</td>
            <td>: {{$percent}} %</td>
        </tr>
    @endforeach
    <tr>
        <td><strong>{{__('Outsite State TAX/GST List')}}</strong></td>
    </tr>
    @foreach($outsite_state_gst as $gst_id => $percent)
        @php
            $gst = \Modules\GST\Entities\GstTax::find($gst_id);
        @endphp
        <tr>
            <td class="info_tbl">{{$gst->name}}</td>
            <td>: {{$percent}} %</td>
        </tr>
    @endforeach
</table>
@else
    @if(app('gst_config')['enable_gst'] == "gst")
    @php
        $same_state_gst = app('gst_config')['within_a_single_state'];
        $same_state_gst = (array)$same_state_gst;
        $outsite_state_gst = app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'];
        $outsite_state_gst = (array)$outsite_state_gst;
    @endphp
    <table class="table-borderless clone_line_table">
        <tr>
            <td><strong>{{__('Same State TAX/GST List')}}</strong></td>
        </tr>
        @foreach($same_state_gst as $gst_id)
            @php
                $gst = \Modules\GST\Entities\GstTax::find($gst_id);
            @endphp
            <tr>
                <td class="info_tbl">{{$gst->name}}</td>
                <td>: {{$gst->tax_percentage}} %</td>
            </tr>
        @endforeach
        <tr>
            <td><strong>{{__('Outsite State TAX/GST List')}}</strong></td>
        </tr>
        @foreach($outsite_state_gst as $gst_id)
            @php
                $gst = \Modules\GST\Entities\GstTax::find($gst_id);
            @endphp
            <tr>
                <td class="info_tbl">{{$gst->name}}</td>
                <td>: {{$gst->tax_percentage}} %</td>
            </tr>
        @endforeach
    </table>
    @else
        @php
            $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
        @endphp
        <table class="table-borderless clone_line_table">
            <tr>
                <td><strong>{{__('TAX/GST')}}</strong></td>
            </tr>
            <tr>
                <td class="info_tbl">{{$flatTax->name}}</td>
                <td>: {{$flatTax->tax_percentage}} %</td>
            </tr>
        </table>
    @endif

@endif