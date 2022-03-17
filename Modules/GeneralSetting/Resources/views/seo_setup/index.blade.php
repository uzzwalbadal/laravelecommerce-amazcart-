@extends('backEnd.master')

@section('mainContent')

    <section class="admin-visitor-area up_st_admin_visitor">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="white_box_30px">
                            <!-- information_form  -->
                            <div class="main-title mb-25">
                                <h3 class="mb-0">{{__('general_settings.homepage_seo_setup')}}</h3>
                            </div>
                            <form action="{{ route('generalsetting.seo-setup-update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="meta_site_title">{{__('general_settings.meta_site_title')}}</label>
                                            <input class="primary_input_field" placeholder="-" type="text" id="meta_site_title" name="meta_site_title"
                                                value="{{app('general_setting')->meta_site_title}}">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="meta_tags">{{__('general_settings.meta_keywords')}} ({{__('product.comma_separated')}})</label>
                                            <div class="tagInput_field">
                                                <input class="sr-only"  type="text" id="meta_tags" name="meta_tags" value="{{app('general_setting')->meta_tags}}" data-role="tagsinput" class="sr-only">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="meta_description">{{__('general_settings.meta_description')}}</label>
                                            <textarea class="primary_textarea" placeholder="{{__('general_settings.meta_description')}}" id="meta_description" cols="30" rows="10"
                                                name="meta_description">{{app('general_setting')->meta_description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @if (permissionCheck('company_information_update'))
                                <div class="col-12 mb-10 pt_15">
                                    <div class="submit_btn text-center">
                                        <button class="primary_btn_large company_info_form_submit" type="submit"> <i
                                                class="ti-check"></i>{{__('common.save')}} </button>
                                    </div>
                                </div>
                                @else
                                <div class="col-lg-12 text-center mt-2">
                                    <span class="alert alert-warning" role="alert">
                                        <strong>{{ __('common.you_don_t_have_this_permission') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </form>


                            <!--/ information_form  -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection
@push('scripts')
<script>

    (function($){
        "use strict";

        $(document).ready(function(){
            
        });

    })(jQuery);

</script>
@endpush

