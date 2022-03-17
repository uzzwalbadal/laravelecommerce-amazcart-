<div class="modal fade admin-query" id="add_carrier_modal">
    <div class="modal-dialog modal_800px modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('shipping.add_new_carrier')}}</h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="create_form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="name"> {{__('common.name')}} <span class="required_mark_theme">*</span></label>
                                <input class="primary_input_field" id="name" name="name" placeholder="{{__('common.name')}}" type="text" value="{{old('name')}}">
                                <span class="text-danger" id="error_name"></span>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="tracking_url"> {{__('shipping.tracking_url')}} <a href="#" class="required_mark_theme" data-toggle="tooltip" title="'@' will be replaced by the dynamic tracking number"><i class="fas fa-question-circle"></i></a></label>
                                <input class="primary_input_field" id="tracking_url" name="tracking_url" placeholder="{{__('shipping.tracking_url')}}" type="text" value="{{old('tracking_url')}}">
                                <span class="required_mark_theme">e.g.: http://example.com/track.php?num=@</span>
                                <span class="text-danger" id="error_tracking_url"></span>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('common.logo') }}</label>
                                <div class="primary_file_uploader">
                                    <input class="primary-input" type="text" id="logo_name"
                                           placeholder="{{__('common.browse_image')}}" readonly="">
                                    <button class="" type="button">
                                        <label class="primary-btn small fix-gr-bg"
                                               for="logo">{{ __('common.browse') }} </label>
                                        <input type="file" class="d-none" name="logo" id="logo">
                                    </button>
                                </div>
                            </div>
                            <span class="text-danger" id="error_logo"></span>

                        </div>
                        <div class="col-lg-4 mt-25">
                            <div class="flag_img_div">
                                <img id="logo_preview" src="{{ showImage('flags/no_image.png') }}" alt="">
                            </div>
                        </div>

                        <div class="col-lg-12 text-center">
                            <div class="d-flex justify-content-center">
                                <button class="primary-btn semi_large2  fix-gr-bg mr-10"  type="submit"><i class="ti-check"></i>{{__('common.submit') }}</button>
                                <button class="primary-btn semi_large2  fix-gr-bg" id="save_button_parent" data-dismiss="modal" type="button"><i class="ti-check"></i>{{__('common.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
