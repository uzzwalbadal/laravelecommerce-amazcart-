<table class="table Crm_table_active3">
    <thead>
    <tr>
        <th scope="col">{{__('common.sl')}}</th>
        <th scope="col">{{__('shipping.pickup_location')}}</th>
{{--        <th scope="col">{{__('common.email')}}</th>--}}
        <th scope="col">{{__('common.phone')}}</th>
        <th scope="col">{{__('common.address')}}</th>
        <th scope="col">{{__('shipping.pin_code')}}</th>
        <th scope="col">{{__('shipping.is_active')}}</th>
        <th scope="col">{{__('shipping.is_default')}}</th>
        <th scope="col">{{__('common.action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($pickup_locations as $key => $row)
        <tr>
            <th>{{ $key+1 }}</th>
            <td>{{ $row->pickup_location }}</td>
{{--            <td>{{ $row->email }}</td>--}}
            <td>{{ $row->phone }}</td>
            <td>{{ $row->address }}</td>
            <td>{{ $row->pin_code }}</td>
            <td>
                <label class="switch_toggle" for="active_checkbox{{ $row->id }}">
                    <input type="checkbox" id="active_checkbox{{ $row->id }}"
                           @if ($row->status == 1) checked @endif @if(permissionCheck('shipping.pickup_locations.status')) class="status_change" value="{{ $row->id }}" data-id="{{ $row->id }}" @else disabled @endif>
                    <div class="slider round"></div>
                </label>
            </td>
            <td>
                <label class="switch_toggle" for="default_checkbox{{ $row->id }}">
                    <input type="checkbox" id="default_checkbox{{ $row->id }}"
                           @if ($row->is_default == 1) checked @endif @if(permissionCheck('shipping.pickup_locations.set_default')) class="set_default" value="{{ $row->id }}" data-id="{{ $row->id }}" @else disabled @endif>
                    <div class="slider round"></div>
                </label>
            </td>
            <td>
                <div class="dropdown CRM_dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{__('common.select')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                        @if (permissionCheck('shipping.pickup_locations.show'))
                            <a class="dropdown-item view_row" data-id="{{$row->id}}" type="button">{{__('common.view')}}</a>
                        @endif
                        @if (permissionCheck('shipping.pickup_locations.update'))
                            <a class="dropdown-item edit_row" data-id="{{$row->id}}" type="button">{{__('common.edit')}}</a>
                        @endif
                        @if ( permissionCheck('shipping.pickup_locations.destroy'))
                            <a class="dropdown-item delete_row" data-id="{{$row->id}}">{{__('common.delete')}}</a>
                        @endif
                    </div>
                </div>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
