@php
    $shipping= false;

    if(request()->is('shipping/*') || request()->is('shipping-rate/*')  || request()->is('shipping-rate') )
    {
        $shipping = true;
    }
@endphp
@if(permissionCheck('shipping_methods'))
<li class="{{ $shipping ?'mm-active' : '' }} sortable_li" data-position="{{ menuManagerCheck(1,41)->position }}" data-status="{{ menuManagerCheck(1,41)->status }}">
    <a href="javascript:;" class="has-arrow" aria-expanded="{{ $shipping ? 'true' : 'false' }}">
        <div class="nav_icon_small">
            <span class="fas fa-money-bill"></span>
        </div>
        <div class="nav_title">
            <span>{{__('shipping.shipping')}}</span>
        </div>
    </a>
    <ul>
       @if (permissionCheck('shipping.carriers.index') && menuManagerCheck(2,41,'shipping.carriers.index')->status == 1)
           <li data-position="{{ menuManagerCheck(2,41,'shipping.carriers.index')->position }}">
               <a href="{{route('shipping.carriers.index')}}" @if (request()->routeIs('shipping.carriers.*')) class="active" @endif>{{ __('shipping.carriers') }}</a>
           </li>
       @endif
        @if (permissionCheck('shipping_methods.index') && menuManagerCheck(2,41,'shipping_methods.index')->status == 1)
            <li data-position="{{ menuManagerCheck(2,41,'shipping_methods.index')->position }}">
                <a href="{{route('shipping_methods.index')}}" @if (request()->routeIs('shipping_methods.*')) class="active" @endif>{{ __('shipping.shipping_rates') }}</a>
            </li>
        @endif

        @if (permissionCheck('shipping.pickup_locations.index') && menuManagerCheck(2,41,'shipping.pickup_locations.index')->status == 1)
            <li data-position="{{ menuManagerCheck(2,41,'shipping.pickup_locations.index')->position }}">
                <a href="{{route('shipping.pickup_locations.index')}}" @if (request()->routeIs('shipping.pickup_locations.*')) class="active" @endif>{{ __('shipping.pickup_locations') }}</a>
            </li>
        @endif
        @if (permissionCheck('shipping.pending_orders.index') && menuManagerCheck(2,41,'shipping.pending_orders.index')->status == 1)
            <li data-position="{{ menuManagerCheck(2,41,'shipping.pending_orders.index')->position }}">
                <a href="{{route('shipping.pending_orders.index')}}" @if (request()->routeIs('shipping.pending_orders.index')) class="active" @endif>{{ __('shipping.shipping_orders') }}</a>
            </li>
        @endif
        @if (permissionCheck('shipping.configuration.index') && menuManagerCheck(2,41,'shipping.configuration.index')->status == 1)
            <li data-position="{{ menuManagerCheck(2,41,'shipping.configuration.index')->position }}">
                <a href="{{route('shipping.configuration.index')}}" @if (request()->routeIs('shipping.configuration.index')) class="active" @endif>{{ __('shipping.configuration') }}</a>
            </li>
        @endif
    </ul>
</li>
@endif
