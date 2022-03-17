
@if(isset($prev_list))
    @foreach($lists as $key => $list)
    @php
        $gst  = \Modules\GST\Entities\GstTax::find($list);
        $exsist = null;
        foreach ($prev_list as $key => $value) {
            if($key == $list){
                $exsist = $value;
            }
        }
    @endphp
    <div class="row">
        <div class="col-lg-6">
            <div class="primary_input mb-25">
                <input type="hidden" name="same_state_gst[]" value="{{$gst->name}}-{{$gst->id}}">
                <input class="primary_input_field name" type="text" value="{{$gst->name}}" autocomplete="off"  placeholder="" readonly>
            </div>
            <span class="text-danger" id="error_name"></span>
        </div>
        <div class="col-lg-6">
            <div class="primary_input mb-25">
                <input class="primary_input_field name" type="number" name="same_state_gst_percent[]" value="{{$exsist?$exsist:$gst->tax_percentage}}" autocomplete="off"  placeholder="">
            </div>
            <span class="text-danger" id="error_name"></span>
        </div>
    </div>
    @endforeach

@else
    @foreach($lists as $key => $amount)
        @php
            $gst  = \Modules\GST\Entities\GstTax::find($key);
        @endphp
        <div class="row">
            <div class="col-lg-6">
                <div class="primary_input mb-25">
                    <input type="hidden" name="same_state_gst[]" value="{{$gst->name}}-{{$gst->id}}">
                    <input class="primary_input_field name" type="text" value="{{$gst->name}}" autocomplete="off"  placeholder="" readonly>
                </div>
                <span class="text-danger" id="error_name"></span>
            </div>
            <div class="col-lg-6">
                <div class="primary_input mb-25">
                    <input class="primary_input_field name" type="number" name="same_state_gst_percent[]" value="{{$amount}}" autocomplete="off"  placeholder="">
                </div>
                <span class="text-danger" id="error_name"></span>
            </div>
        </div>
    @endforeach
@endif