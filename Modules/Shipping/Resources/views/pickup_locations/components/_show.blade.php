<div class="modal fade admin-query" id="view_modal">
    <div class="modal-dialog modal_800px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('shipping.pickup_location_details') }}</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                        <div class="QA_section3 QA_section_heading_custom th_padding_l0">
                            <div class="QA_table">
                                <!-- table-responsive -->
                                <div class="table-responsive">
                                    <table class="table pos_table pt-0 shadow_none pb-0 bg-transparent ">
                                        <tbody>
                                            <tr>
                                                <th scope="col">{{__('shipping.pickup_location')}}</th>
                                                <td>{{ $row->pickup_location }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{__('common.name')}}</th>
                                                <td>{{ $row->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{__('common.email')}}</th>
                                                <td>{{ $row->email }}</td>
                                            </tr>

                                            <tr>
                                                <th scope="col">{{__('common.phone')}}</th>
                                                <td>{{ $row->phone }}</td>
                                            </tr>

                                            <tr>
                                                <th scope="col">{{__('common.address')}}</th>
                                                <td>{{ $row->address }}</td>
                                            </tr>

                                            <tr>
                                                <th scope="col">{{__('shipping.address_2')}}</th>
                                                <td>{{ $row->address_2 }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{__('shipping.pin_code')}}</th>
                                                <td>{{ $row->pin_code }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{__('common.country')}}</th>
                                                <td>{{ $row->country->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{__('common.state')}}</th>
                                                <td>{{ $row->state->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="col">{{__('common.city')}}</th>
                                                <td>{{ $row->city->name }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
