<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta charset="UTF-8">
    <style media="all">
        @font-face {
            font-family: 'Roboto';
            src: url("{{ static_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-size: 0.75rem;
            font-family: 'Roboto';
            font-weight: normal;
            direction: ltr;
            text-align: left;
            padding: 0;
            margin: 0;
            color: #232323;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: 0 .8rem;
        }

        table.padding td {
            padding: .8rem;
        }

        table.sm-padding td {
            padding: .5rem .7rem;
        }

        table.lg-padding td {
            padding: 1rem 1.2rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold
        }

    </style>
</head>

<body>
    <div style="max-width: 750px;padding:20px;margin:0 auto">
        <div style="padding:0px 19px;">
            <table>
                <thead>
                    <tr>
                        <th width="50%"></th>
                        <th width="50%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if (get_setting('invoice_logo') != null)
                                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(uploaded_asset(get_setting('invoice_logo')))) }}"
                                                    height="30" style="display:inline-block;margin-bottom:10px">
                                            @else
                                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(static_asset('assets/img/logo.png'))) }}" height="30"
                                                style="display:inline-block;margin-bottom:10px">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="" class="bold">{{ get_setting('site_name') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ get_setting('invoice_address') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">Борлуулагч:
                                            {{ $combined_order->saler->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Email') }}:
                                            {{ $combined_order->saler->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Phone') }}:
                                            {{ $combined_order->saler->phone }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="text-right">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 2rem;" class="bold">ҮНИЙН САНАЛ
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ translate('Order Code') }}:</span>
                                            <span class="bold"
                                                style="color: #ED2939">{{ $combined_order->quote_no }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="">
                                            <span class=" ">{{ translate('Order Date') }}:</span>
                                            <span
                                                class="bold">{{ $combined_order->created_at->format('d.m.Y') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class=" ">{{ translate('Delivery type') }}:</span>
                                            <span class="bold"
                                                style="text-transform: capitalize">{{ translate($combined_order->delivery_type??"") }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin:8px 8px 15px 8px; clear:both">
            
            <div style="padding:10px 14px; border:1px solid #DEDEDE;border-radius:3px;width:45%;float:right">
                <table class="text-right">
                    <tbody>
                        @php
                            $shipping_address = json_decode($combined_order->shipping_address);
                        @endphp
                        <tr>
                            <td class="bold">{{ translate('Shipping address') }}:</td>
                        </tr>
                        <tr>
                            <td class="">{{ $shipping_address->address??'' }},
                                {{ $shipping_address->postal_code??'' }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ $shipping_address->city }},
                                {{ $shipping_address->state }}, {{ $shipping_address->country??'' }}</td>
                        </tr>
                        <tr>
                            <td class="">{{ translate('Phone') }}: {{ $shipping_address->phone??'' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table></table>

        <div style="margin:8px;border:1px solid #DEDEDE;border-radius:3px;padding:0 7px">
            <table class="padding">
                <thead>
                    <tr>
                        <td width="45%" class="text-left bold" style="border-bottom:1px solid #DEDEDE;">Барааны нэр</td>
                        <td width="13%" class="text-left bold" style="border-bottom:1px solid #DEDEDE;">{{ translate('Qty') }}</td>
                        <td width="15%" class="text-left bold" style="border-bottom:1px solid #DEDEDE;">{{ translate('Unit Price') }}</td>
                        <td width="10%" class="text-left bold" style="border-bottom:1px solid #DEDEDE;">НӨАТ</td>
                        <th class="text-center" data-breakpoints="lg" style="border-bottom:1px solid #DEDEDE;">Дэд Үнэ</th>
                        @if ($combined_order->orderDetails->sum('discount') > 0)
                            <th class="text-center" data-breakpoints="lg" style="border-bottom:1px solid #DEDEDE;">Хямдрал</th>
                        @endif
                        <td width="12%" class="text-right bold" style="border-bottom:1px solid #DEDEDE;">{{ translate('Total') }}</td>
                    </tr>
                </thead>
 
                <tbody class="strong">
                    
                    @php
                        $totalTax = 0;
                        $total = 0;
                    @endphp
                        @foreach ($combined_order->orderDetails as $key => $orderDetail)
                            @if ($orderDetail->product != null)
                                <tr>
                                    <td style="border-bottom:1px solid #DEDEDE;">
                                        <span style="display: block">{{ $orderDetail->product->name??'' }}</span>
                                        @if ($orderDetail->variation && $orderDetail->variation->combinations->count() > 0)
                                            @foreach ($orderDetail->variation->combinations as $combination)
                                                <span style="margin-right:10px">
                                                    <span
                                                        class="">{{ $combination->attribute->getTranslation('name') ??'' }}</span>:
                                                    <span>{{ $combination->attribute_value->getTranslation('name') ??''}}</span>
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="" style="border-bottom:1px solid #DEDEDE;">
                                        {{ $orderDetail->quantity }}</td>
                                    <td class="" style="border-bottom:1px solid #DEDEDE;">
                                        {{ format_price($orderDetail->price) }}</td>
                                    <td class="" style="border-bottom:1px solid #DEDEDE;">
                                        {{ format_price($orderDetail->tax) }}</td>
                                    <td class="text-center" style="border-bottom:1px solid #DEDEDE;">{{ format_price($orderDetail->price * $orderDetail->quantity) }}</td>
                                    @if ($combined_order->orderDetails->sum('discount') > 0)
                                        <td class="text-center" style="border-bottom:1px solid #DEDEDE;">{{ $orderDetail->discount_type == "₮" ? format_price($orderDetail->discount) : ($orderDetail->discount . '%') }}</td>
                                    @endif
                                    <td class="text-right bold" style="border-bottom:1px solid #DEDEDE;padding-right:20px;">
                                        {{ format_price($orderDetail->total) }}</td>
                                </tr>
                            @endif
                            @php
                                $totalTax = ($orderDetail->tax??0) * ($orderDetail->quantity??0);
                                $total += $orderDetail->total ?? 0;
                            @endphp
                        @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin:15px 8px;clear:both">
            <div style="float: left; width:43%;padding:14px 20px;">
            </div>
            <div style="float: right; width:43%;padding:14px 20px; border:1px solid #DEDEDE;border-radius:3px;">
                <table class="text-right sm-padding" style="border-collapse:collapse">
                    <tbody>
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                {{ translate('Sub Total') }}</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ format_price($total - $totalTax) }}</td>
                        </tr>
                        <tr class="">
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                               НӨАТ</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ format_price($totalTax) }}</td>
                        </tr>
                        <tr>
                            <td class="text-left" style="border-bottom:1px dotted #B8B8B8">
                                Хүргэлтийн төлбөр</td>
                            <td class="bold" style="border-bottom:1px dotted #B8B8B8">
                                {{ format_price($combined_order->shipping_cost??0) }}</td>
                        </tr>
                        <tr class="">
                            <td class="text-left" style="border-bottom:1px solid #DEDEDE">
                                Купоны хямдрал</td>
                            <td class="bold" style="border-bottom:1px solid #DEDEDE">
                                {{ format_price($combined_order->coupon_discount ?? 0) }}</td>
                        </tr>
                       
                        <tr class="">
                            <td class="text-left" style="border-bottom:1px solid #DEDEDE">
                                Гишүүнчлэл хямдрал</td>
                            <td class="bold" style="border-bottom:1px solid #DEDEDE">
                                {{ format_price($combined_order->membership ?? 0) }}</td>
                        </tr>
                        
                        @if ($combined_order->orderDetails->sum('discount') == 0)
                        <tr class="">
                            <td class="text-left" style="border-bottom:1px solid #DEDEDE">
                                Борлуулагч хямдрал</td>
                            <td class="bold" style="border-bottom:1px solid #DEDEDE">
                                {{ $combined_order->discount_type == "₮" ? format_price($combined_order->discount ?? 0) : ($combined_order->discount . '%') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-left bold">Нийт</td>
                            <td class="bold">{{ format_price($combined_order->grand_total ??0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>
