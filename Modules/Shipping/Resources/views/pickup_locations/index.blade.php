@extends('backEnd.master')
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                @if (permissionCheck('shipping.pickup_locations.store'))
                    <div class="col-lg-3">
                        <div class="create_div">
                            @include('shipping::pickup_locations.components._create')
                        </div>
                    </div>
                @endif

                <div class="col-lg-9">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('shipping.pickup_locations') }}</h3>
                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <div id="item_list">
                                @include('shipping::pickup_locations.components._list')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="append_html"></div>

 @include('backEnd.partials._deleteModalForAjax',['item_name' => __('shipping.pickup_location'),'form_id' =>
'pickup_location_delete_form','modal_id' => 'pickup_location_delete_modal', 'delete_item_id' => 'pickup_location_delete_id'])

@endsection

@include('shipping::pickup_locations.components._scripts')
