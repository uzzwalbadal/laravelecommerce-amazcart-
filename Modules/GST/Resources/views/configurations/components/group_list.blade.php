<div class="row">
    <div class="col-lg-12">
        <table class="table Crm_table_active3">
            <thead>
                <tr>
                    <th scope="col">{{ __('common.id') }}</th>
                    <th scope="col">{{ __('common.name') }}</th>
                    <th scope="col">{{ __('Same State GST') }}</th>
                    <th scope="col">{{ __('Outsite State GST') }}</th>
                    <th scope="col">{{ __('common.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gst_groups as $key => $group)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$group->name}}</td>
                        <td>
                            @php
                                $gsts = json_decode($group->same_state_gst);
                                $gsts = (array) $gsts;
                            @endphp
                            @foreach($gsts as $key => $percent)
                                @php
                                    $gst = \Modules\GST\Entities\GstTax::find($key);
                                @endphp
                                <p>{{$gst->name}}: {{$percent}} %</p>
                            @endforeach
                        </td>
                        <td>
                            @php
                                $gsts = json_decode($group->outsite_state_gst);
                                $gsts = (array) $gsts;
                            @endphp
                            @foreach($gsts as $key => $percent)
                                @php
                                    $gst = \Modules\GST\Entities\GstTax::find($key);
                                @endphp
                                <p>{{$gst->name}}: {{$percent}} %</p>
                            @endforeach
                        </td>
                        <td>
                            <div class="dropdown CRM_dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('common.select') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                                    <a class="dropdown-item edit_group" data-id="{{$group->id}}">{{__('common.edit')}}</a>
                                    <a class="dropdown-item delete_group" data-id="{{$group->id}}">{{__('common.delete')}}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>