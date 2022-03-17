<table class="table Crm_table_active">
    <thead>
    <tr>
        <th scope="col">{{__('common.sl')}}</th>
        <th scope="col">{{ __('common.name') }}</th>
        <th scope="col">{{ __('general_settings.activate') }}</th>
        <th scope="col">{{ __('common.action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($carriers as $key => $row)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                {{ $row->name }}
                @if(config('app.sync'))
                    @if($row->slug == 'Shiprocket')
                        <span class="demo_addons">Addon</span>
                    @endif
                @endif
            </td>
            @if($row->slug == 'Shiprocket')
                @php
                    $carrierConfig = $row->carrierConfig;
                @endphp
                 @if($carrierConfig)
                    <td class="text-left">
                        <label class="switch_toggle" for="checkbox{{ $row->id }}">
                            <input data-carrier="{{$row->id}}" type="checkbox" id="checkbox{{ $row->id }}" @if ($row->carrierConfig->carrier_status == 1) checked @endif @if (permissionCheck('shipping.carriers.status')) value="{{ $row->carrierConfig->id }}" class="carrier_activate" @else disabled @endif>
                            <div class="slider round"></div>
                        </label>
                    </td>
                @else
                    <td class="text-left">
                        <label class="switch_toggle disable_shiprocket" for="checkbox{{ $row->id }}">
                            <input title="Carrier Config Done First" type="checkbox" id="checkbox{{ $row->id }}"  class="carrier_dissable" disabled >
                            <div class="slider round"></div>
                        </label>
                    </td>
                @endif
            @else
                <td class="text-left">
                    <label class="switch_toggle" for="checkbox{{ $row->id }}">
                        <input data-carrier="{{$row->id}}" type="checkbox" id="checkbox{{ $row->id }}" @if ($row->status == 1) checked @endif @if (permissionCheck('shipping.carriers.status')) value="{{ $row->id }}" class="carrier_activate" @else disabled @endif>
                        <div class="slider round"></div>
                    </label>
                </td>
            @endif
            <td>
                @if($row->type != 'Automatic')
                <div class="dropdown CRM_dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{__('common.select')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">

                        <a href="#" data-id="{{$row->id}}" class="edit_carrier dropdown-item">{{__('common.edit')}}</a>
                        @if (permissionCheck('shipping.carrier.update'))
                        @endif
                        @if (permissionCheck('shipping.carrier.destroy'))
                        @endif
                        <a href="#" data-id="{{$row->id}}" class="delete_carrier dropdown-item">{{__('common.delete')}}</a>

                    </div>
                </div>
                @else
                {{__('common.not_editable')}}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
