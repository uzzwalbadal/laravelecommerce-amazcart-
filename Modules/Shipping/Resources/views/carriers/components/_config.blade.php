<div class="col-md-12 mb-20">
    <div class="box_header_right">
        <div class=" float-none pos_tab_btn justify-content-start">
            @php
                $methods = '';
                foreach($carriers->where('status', 1) as $method){
                    if($method->slug == 'Shiprocket'){
                        $methods = $method;
                    }
                }
            @endphp
            <ul class="nav nav_list" role="tablist">
                @php
                    $shipRocket = $carriers->where('name', 'Shiprocket')->first();
                @endphp
                @if(isModuleActive('ShipRocket') && $shipRocket && $shipRocket->status == 1)
                    <li class="nav-item mb-2">
                        <a class="nav-link @if(@$methods->slug == 'Shiprocket') active show @endif" href="#ShiprocketTab" role="tab"
                           data-toggle="tab" id="1" aria-selected="true">{{__('shipping.shiprocket')}}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="white_box_30px mb_30">
        <div class="tab-content">
            @if(isModuleActive('ShipRocket') && $shipRocket && $shipRocket->status == 1)
                <div role="tabpanel" class="tab-pane fade   @if(@$methods->slug == 'Shiprocket') active show @endif " id="ShiprocketTab">
                    <div class="box_header common_table_header ">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{__('shipping.shiprocket')}}</h3>
                            <ul class="d-flex">
                                <div class="img_logo_div">
                                    @if($shipRocket->carrierConfig && $shipRocket->carrierConfig->logo)
                                        <img src="{{ showImage($shipRocket->carrierConfig->logo) }}" alt="">
                                    @else
                                        <img src="{{ showImage($shipRocket->logo) }}" alt="">
                                    @endif
                                </div>
                            </ul>
                        </div>
                    </div>

                    @include('shiprocket::config',['shipRocket'=>$shipRocket])
                </div>
            @endif
        </div>
    </div>
</div>
