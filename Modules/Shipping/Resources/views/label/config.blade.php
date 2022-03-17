@extends('backEnd.master')
@section('styles')
    <style>
        .dashed {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px dashed var(--gradient_1);
        }
    </style>
@endsection

@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="main-title">
                                        <h3 class="mb-15">
                                            {{__('shipping.label_terms_and_conditions')}}
                                        </h3>
                                        <hr class="dashed">
                                    </div>

                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'shipping.label.terms_condition.update',
                                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                    <div class="row">

                                        <div class="col-xl-12 mt-repeater no-extra-space">
                                            <strong class="text-center">{{__('shipping.terms_and_conditions')}}</strong>
                                            @if(count($conditions) > 0)
                                                @foreach($conditions as $key =>$condition)
                                                    <div class="row">
                                                        <input type="hidden" name="conditionIds[{{$condition->id}}]" value="{{$condition->id}}">
                                                        <div class="col">
                                                            <div class="primary_input mb-25 position-relative">
                                                                <input value="{{$condition->condition}}" name="eCondition[{{$condition->id}}]" id="condition"  class="primary_input_field condition" placeholder="{{__('shipping.terms_and_conditions') }}" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <div class="position-relative form-group">
                                                                <a data-condition="{{$condition->id}}"  href="javascript:;" data-repeater-delete class="primary-btn condition_delete small icon-only fix-gr-bg  mt-repeater-delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div data-repeater-list="conditions">
                                                <div data-repeater-item class="mt-repeater-item">
                                                    <div class="mt-repeater-row">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="primary_input mb-25 position-relative">
                                                                    <input  name="condition" class="primary_input_field condition" placeholder="{{__('shipping.terms_and_conditions') }}" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-1">
                                                                <div class="position-relative form-group">
                                                                    <a  href="javascript:;" data-repeater-delete class="primary-btn small icon-only fix-gr-bg  mt-repeater-delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-offset-1 col-md-9">
                                                <a href="javascript:;" data-repeater-create  class="primary-btn radius_30px condition_edit  fix-gr-bg mt-repeater-add"><i class="fa fa-plus"></i>{{__('shipping.add_more')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" >
                                                <span class="ti-check"></span>
                                                {{__('common.update')}}
                                            </button>
                                        </div>
                                    </div>
                                    {{ Form::close() }}

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('Modules/Shipping/Resources/assets/js/repeater/repeater.js')}}"></script>
    <script src="{{asset('Modules/Shipping/Resources/assets/js/repeater/indicator-repeater.js')}}"></script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $(document).on('click','.condition_delete',function (event){
                    event.preventDefault();
                    let id = $(this).data('condition');
                    let url =  "{{route('shipping.label.terms_condition.destroy',':id')}}";
                    url = url.replace(':id',id);
                    let selectRow = $(this).parent().parent().parent();
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function(response) {
                            if(response.status == 200){
                                selectRow.remove();
                                toastr.success("Condition Deleted Successfully");
                            }
                        },
                        error: function(response) {
                            toastr.error("Something went wrong");
                        }
                    });
                });

            });
        })(jQuery);
    </script>

@endpush

