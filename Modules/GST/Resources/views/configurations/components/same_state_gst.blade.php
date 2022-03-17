
@foreach($lists as $list)
    @php
        $gst = \Modules\GST\Entities\GstTax::find($list);
    @endphp
    <div class="row">
        <div class="col-lg-6">
            <div class="primary_input mb-25">
                
                <input class="primary_input_field name" type="text" value="{{$gst->name}}" autocomplete="off"  placeholder="" readonly>
                <input type="hidden" name="same_state_gst[]" value="{{$gst->name}}-{{$gst->id}}">
            </div>
            <span class="text-danger" id="error_name"></span>
        </div>
        <div class="col-lg-6">
            <div class="primary_input mb-25">
                <input class="primary_input_field name" type="number" name="same_state_gst_percent[]" value="{{$gst->tax_percentage}}" autocomplete="off"  placeholder="">
            </div>
            <span class="text-danger" id="error_name"></span>
        </div>
    </div>
@endforeach