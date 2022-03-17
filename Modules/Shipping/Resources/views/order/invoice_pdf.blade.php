<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$order->package_code}} Invoice</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
        }
        h1,h2,h3,h4,h5,h6{
            margin: 0;
            color: #101010;
        }
        .invoice_wrapper{
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 20px;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        .border_none{
            border: 0px solid transparent;
            border-top: 0px solid transparent !important;
        }
        .invoice_part_iner{
            background-color: #fff;
        }

        .table_border thead{
            background-color: #F6F8FA;
        }
        .table td, .table th {
            padding: 5px 0;
            vertical-align: top;
            border-top: 0 solid transparent;
            color: #101010;
        }
        .table td , .table th {
            padding: 5px 0;
            vertical-align: top;
            border-top: 0 solid transparent;
            color: #101010;
        }
        .table_border tr{
            border-bottom: 1px solid #101010 !important;
        }
        th p span, td p span{
            color: #212E40;
        }
        .table th {
            color: #101010;
            border: 1px solid #101010 !important;
        }
        p{
            font-size: 14px;
            color: #101010;
        }
        h5{
            font-size: 12px;
            font-weight: 500;
        }
        h6{
            font-size: 10px;
            font-weight: 300;
        }
        .mt_40{
            margin-top: 40px;
        }
        .table_style th, .table_style td{
            padding: 20px;
        }
        .invoice_info_table td{
            font-size: 10px;
            padding: 0px;
        }

        .text_right{
            text-align: right;
        }
        .text_left{
            text-align: left!important;
        }
        .virtical_middle{
            vertical-align: middle !important;
        }
        .logo_img {
            max-width: 120px;
        }
        .logo_img img{
            width: 100%;
        }
        .border_bottom{
            border-bottom: 1px solid #000;
        }
        .line_grid{
            display: grid;
            grid-template-columns: 110px auto;
            grid-gap: 10px;
        }
        .line_grid span{
            display: flex;
            justify-content: space-between;
        }

        .line_grid2{
            display: grid;
            grid-template-columns:  auto 110px;
            grid-gap: 10px;
        }
        .line_grid2 span{
            display: flex;
            justify-content: space-between;
        }
        p{
            margin: 0;
        }
        .font_18 {
            font-size: 18px;
        }
        .mb-0{
            margin-bottom: 0;
        }
        .mb_30{
            margin-bottom: 30px !important;
        }
        .border_table{}
        .border_table thead tr th {
            padding: 5px;
        }
        .border_table tbody tr td {
            border: 1px solid #101010 !important;
            text-align: center;
            padding: 5px;
        }
        td, th{
            color: #101010;
            font-weight: 500;
            padding: 5px;

        }
        table{
            width: 100%;
        }
        @page {
            footer: page-footer;
        }
    </style>
</head>
<body>
<div class="invoice_wrapper">
    <!-- invoice print part here -->
    <div class="invoice_print mb_30">
        <div class="container">
            <div class="invoice_part_iner">
                <table class="table border_bottom mb_30">
                    <thead>
                    <tr>
                        <td>
                            <div class="logo_div">
                                <img src="{{showImage(app('general_setting')->logo)}}" alt="">
                            </div>
                        </td>
                        <td class="virtical_middle text_right invoice_info">
                            <h4 class="text_uppercase">{{app('general_setting')->company_name}}</h4>
                            <h4>{{app('general_setting')->phone}}</h4>
                            <h4>{{app('general_setting')->email}}</h4>
                            <h4>{{$order->order->order_number}}</h4>
                        </td>
                    </tr>
                    </thead>
                </table>
                <!-- middle content  -->
                <table class="table">
                    <tbody>
                    <tr>
                        <td style="width: 50%">
                            <!-- single table  -->
                            <table class="mb_30">
                                <tbody>
                                <tr>
                                    <td>
                                        <h5 class="font_18 mb-0" >{{ __('shipping.billing_info') }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.name') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->shipping_address->name : $order->order->guest_info->billing_name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.email') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->customer_email : $order->order->guest_info->billing_email}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.phone') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->customer_phone : $order->order->guest_info->billing_phone}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.address') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->billing_address->address : $order->order->guest_info->billing_address}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.city') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? @$order->order->billing_address->getCity->name : @$order->order->guest_info->getBillingCity->name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.state') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? @$order->order->billing_address->getState->name : @$order->order->guest_info->getBillingState->name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.country') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? @$order->order->billing_address->getCountry->name : @$order->order->guest_info->getBillingCountry->name}}
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!--/ single table  -->
                        </td>
                        <td style="width: 50%">
                            <!-- single table  -->
                            <table class="mb_30">
                                <tbody>
                                <tr>
                                    <td>
                                        <h5 class="font_18 mb-0" >{{ __('shipping.company_info') }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid" >
                                                         <span>
                                                             <span>{{ __('common.name') }}</span>
                                                             <span>:</span>
                                                         </span>
                                            {{app('general_setting')->company_name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid" >
                                                         <span>
                                                             <span>{{ __('common.email') }}</span>
                                                             <span>:</span>
                                                         </span>
                                            {{app('general_setting')->email}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid" >
                                                         <span>
                                                             <span>{{ __('common.phone') }}</span>
                                                             <span>:</span>
                                                         </span>
                                            {{app('general_setting')->phone}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid" >
                                                         <span>
                                                             <span>{{ __('common.website') }}</span>
                                                             <span>:</span>
                                                         </span>
                                            {{ app('general_setting')->website_url }}
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!--/ single table  -->
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%">
                            <!-- single table  -->
                            <table>
                                <tbody>
                                <tr>
                                    <td>
                                        <h5 class="font_18 mb-0" >{{ __('shipping.shipping_info') }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.name') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->shipping_address->name : $order->order->guest_info->shipping_name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.email') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->shipping_address->email : $order->order->guest_info->shipping_email}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.phone') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->shipping_address->phone : $order->order->guest_info->shipping_phone}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.address') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->shipping_address->address : $order->order->guest_info->shipping_address}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.city') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? @$order->order->shipping_address->getCity->name : $order->order->guest_info->getShippingCity->name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.state') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? @$order->order->shipping_address->getState->name : $order->order->guest_info->getShippingState->name}}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="line_grid_2" >
                                                        <span>
                                                            <span>{{ __('common.country') }}</span>
                                                            <span>:</span>
                                                        </span>
                                            {{($order->order->customer_id) ? $order->order->shipping_address->getCountry->name : $order->order->guest_info->getShippingCountry->name}}
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!--/ single table  -->
                        </td>
                        <td style="width: 50%">
                            <table>
                                <tbody>
                                <tr>
                                    <td>
                                        <!-- single table  -->
                                        <table class="mb_30">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <h5 class="font_18 mb-0" >{{ __('shipping.order_info') }}</h5>
                                                </td>
                                            </tr>
                                            @if($order->order->customer_id == null)
                                                <tr>
                                                    <td>
                                                        <p class="line_grid" >
                                                                        <span>
                                                                            <span>{{ __('common.secret_id') }}</span>
                                                                            <span>:</span>
                                                                        </span>
                                                            {{$order->order->guest_info->guest_id}}
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>
                                                    <p class="line_grid" >
                                                                        <span>
                                                                            <span>{{ __('order.is_paid') }}</span>
                                                                            <span>:</span>
                                                                        </span>
                                                        {{$order->order->is_paid == 1 ? 'Yes' : 'No'}}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="line_grid" >
                                                                        <span>
                                                                            <span>{{ __('common.subtotal') }}</span>
                                                                            <span>:</span>
                                                                        </span>
                                                        {{single_price($order->subTotal())}}
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <p class="line_grid" >
                                                                        <span>
                                                                            <span>{{ __('common.shipping_charge') }}</span>
                                                                            <span>:</span>
                                                                        </span>
                                                        {{single_price($order->shipping_cost)}}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="line_grid" >
                                                                        <span>
                                                                            <span>{{ __('common.tax') }}</span>
                                                                            <span>:</span>
                                                                        </span>
                                                        {{single_price($order->tax_amount)}}
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <p class="line_grid" >
                                                                        <span>
                                                                            <span>{{ __('common.grand_total') }}</span>
                                                                            <span>:</span>
                                                                        </span>
                                                        {{single_price($order->subTotal() + $order->tax_amount + $order->shipping_cost)}}
                                                    </p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!--/ single table  -->
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <table class="table">
        <tbody>
        <tr>
            <td style="width: 50%">
                <p class="line_grid_2">
                                <span>
                                    <span>{{ __('common.package') }}</span>
                                    <span>:</span>
                                </span>
                    {{ $order->package_code }}
                </p>
            </td>
            @if(isModuleActive('MultiVendor'))
                <td style="width: 50%">
                    <p class="line_grid_auto grid_end">
                                <span>
                                    <span>{{ __('shipping.shop_name') }}</span>
                                    <span>:</span>
                                </span>
                        @if(@$order->seller->role->type == 'seller')
                            {{ (@$order->seller->SellerAccount->seller_shop_display_name) ? @$order->seller->SellerAccount->seller_shop_display_name : @$order->seller->first_name }}
                        @else
                            {{ app('general_setting')->company_name }}
                        @endif
                    </p>
                </td>
            @endif
        </tr>
        <tr>
            <td style="width: 50%">
                @if (file_exists(base_path().'/Modules/GST/') && (app('gst_config')['enable_gst'] == "gst" || app('gst_config')['enable_gst'] == "flat_tax"))
                    @foreach ($order->gst_taxes as $key => $gst_tax)
                        <p class="line_grid_2 ">
                                        <span>
                                            <span>{{ $gst_tax->gst->name }}</span>
                                            <span>:</span>
                                        </span>
                            {{ single_price($gst_tax->amount) }}
                        </p>
                    @endforeach
                @endif
            </td>
            <td style="width: 50%">
                <p class="line_grid_auto grid_end">
                                <span>
                                    <span>{{ __('shipping.shipping_method') }}</span>
                                    <span>:</span>
                                </span>
                    {{ $order->shipping->method_name }}
                </p>
            </td>
        </tr>
        </tbody>
    </table>
    <h3 class="center title_text">Ordered Products</h3>

    <table class="table border_table mb_30">
        <thead>
            <tr>
                <th>{{ __('common.name') }}</th>
                <th>{{ __('common.details') }}</th>
                <th>{{ __('common.price') }}</th>
                <th>{{ __('common.total') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($order->products as $key => $package_product)
                    <tr>
                        <td class="text_left">
                            @if ($package_product->type == "gift_card")
                                {{ @$package_product->giftCard->name }}
                            @else
                                {{ @$package_product->seller_product_sku->sku->product->product_name }}
                            @endif
                        </td>
                        @if ($package_product->type == "gift_card")
                            <td>Qty: {{ $package_product->qty }}</td>
                        @else
                            @if (@$package_product->seller_product_sku->sku->product->product_type == 2)
                                <td>
                                    Qty: {{ $package_product->qty }}
                                    <br>
                                    @php
                                        $countCombinatiion = count(@$package_product->seller_product_sku->product_variations);
                                    @endphp
                                    @foreach (@$package_product->seller_product_sku->product_variations as $key => $combination)
                                        @if ($combination->attribute->name == 'Color')
                                            <div class="box_grid ">
                                                <span>{{ $combination->attribute->name }}:</span><span class='box' style="background-color:{{ $combination->attribute_value->value }}"></span>
                                            </div>
                                        @else
                                            {{ $combination->attribute->name }}:
                                            {{ $combination->attribute_value->value }}
                                        @endif
                                        @if ($countCombinatiion > $key + 1)
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                            @else
                                <td>Qty: {{ $package_product->qty }}</td>
                            @endif
                        @endif

                        <td>{{ single_price($package_product->price) }}</td>
                        <td>{{ single_price($package_product->price * $package_product->qty) }}</td>
                    </tr>
                @endforeach
        </tbody>



    </table>
</div>
</body>
</html>
