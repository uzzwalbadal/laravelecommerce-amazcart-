@php
    $same_state_gst = json_decode($group->same_state_gst);
    $same_state_gst = (array)$same_state_gst;
    $outsite_state_gst = json_decode($group->outsite_state_gst);
    $outsite_state_gst = (array)$outsite_state_gst;
@endphp
<table class="table-borderless clone_line_table">
    <tr>
        <td><strong>{{__('Same State TAX/GST List For: ')}} {{$group->name}}</strong></td>
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
        <td><strong>{{__('Outsite State TAX/GST List For: ')}} {{$group->name}}</strong></td>
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