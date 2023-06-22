<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FlexibleDrive-order-number</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border-spacing: 0; border-width: 0; padding: 0; border-width: 0;">
        <thead>
            <tr>
                <td colspan="2" style="border-bottom: 3px #ef5324 solid; padding-bottom: 10px;">
                    <table style="width: 100%; border-spacing: 0; border-width: 0; padding: 0; border-width: 0;">
                        <tr>
                            <td align="left">
                                <img src="{{asset('images/logo-colour.png')}}" alt="{{config("mail.mail_team_name")}}" style="max-width: 300px; width: 300px;">
                                <h2 style="color:#a1a1a1;    font-size: 22px;">
                                    Order received
                                </h2>
                            </td>
                            <td align="right" style="color: #818181; border-top: #ef5324;">
                                <div style="width: 150px; text-align: left; font-size: 22px; border-top: 3px #ef5324 solid; padding-top: 10px; line-height: 28px; color: #818181;">
                                    Transport Marine Industrial Defence
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <table style="width: 100%; border-spacing: 0; border-width: 0; padding: 0; border-width: 0; padding-top:20px;">
                        <tr>
                            <td>
                                @if(isset($is_for_admin) && $is_for_admin == 1)
                                <p>New Order received from {{$order->user->name}}</p>
                                @else

                                <p>Dear {{$order->user->name}}</p>
                                <p>Thank you for your order with {{config("mail.mail_team_name")}}.</p>
                                <p>Your order is currently being processed.</p>
                                <p>Should there be any issues with your order you will be notified directly. Otherwise
                                    your order will be supplied as per the order confirmation below and delivery options
                                    selected.</p>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style=" font-size: 18px; font-weight: bold; margin-top: 0;">
                                    <strong>{{(isset($is_for_admin) && $is_for_admin == 1) ? 'New Order' : 'Your order'}}</strong>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="width: 100%; border-spacing: 0; border-width: 0; padding: 0; border-width: 0;">
                                    <thead style="font-weight: bold;">
                                        <tr>
                                            <td align="center" style="border-bottom: 1px #000000 solid; padding: 8px 0;">Part No</td>
                                            <td align="center" style="border-bottom: 1px #000000 solid; padding: 8px 0;">Description
                                            </td>
                                            <td align="center" style="border-bottom: 1px #000000 solid; padding: 8px 0;">Qty</td>
                                            <td align="center" style="border-bottom: 1px #000000 solid; padding: 8px 0;">Unit price
                                            </td>
                                            <td align="center" style="border-bottom: 1px #000000 solid; padding: 8px 0;">Total</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td align="center" style="padding: 8px 0;">{{$item->product->product_nr}}</td>
                                            <td align="center" style="padding: 8px 0;">{{$item->product->name}}</td>
                                            <td align="center" style="padding: 8px 0;">{{$item->qty}}</td>
                                            <td align="center" style="padding: 8px 0;">${{ number_format((float)$item->price, 2, '.', '')}}</td>
                                            <td align="center" style="padding: 8px 0;">${{ number_format((float)$item->price * $item->qty, 2, '.', '')}}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="font-size: 18px; font-weight: bold; margin-bottom: 0px;">
                                    <strong>Payment total</strong>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">
                                <table style="width: 100%; border-spacing: 0; border-width: 0; padding: 0; border-width: 0;">
                                    <tr>
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            Order subtotal:
                                        </td>
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            ${{ number_format((float)$order->subtotal, 2, '.', '')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            Freight:
                                        </td>
                                        <!--<td align="left" style="width: 50%; padding: 8px 0;">
                                            ${{ number_format((float)config('constant.invoice.freight'), 2, '.', '') }}
                                        </td>-->
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            ${{ number_format((float)$order->delivery, 2, '.', '')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            GST (included):
                                            @php
                                            $gst = config('constant.invoice.gst');
                                            @endphp
                                        </td>
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            ${{ number_format((float)$order->gst, 2, '.', '') }}({{$gst}}%)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            Order total:
                                        </td>
                                        <td align="left" style="width: 50%; padding: 8px 0;">
                                            ${{ number_format((float)$order->total, 2, '.', '') }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @if(!isset($is_for_admin) || $is_for_admin == 0)
                        <tr>
                            <td>
                                <p style="font-size: 18px; font-weight: bold; margin-top: 0;">
                                    <strong>Payment information</strong>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="margin-top: 0;">
                                    Confirmation of your order will be sent with shipment and invoiced to your account
                                    as per your terms with {{config("mail.mail_team_name")}}.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: left; line-height: 22px; margin-top: 0;"></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="font-size: 18px; font-weight: bold;">
                                    <strong>Delivery & Collection times</strong>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 8px 0;">
                                <table style="width: 50%; border-spacing: 0; border-width: 0; padding: 0; border-width: 0;">
                                    <tr>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px;">
                                            Delivery 1</td>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px; border-right: 1px #000000 solid;">
                                            Today</td>
                                    </tr>
                                    <tr>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px;">
                                            Delivery 2</td>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px; border-right: 1px #000000 solid;">
                                            Tomorrow</td>
                                    </tr>
                                    <tr>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px;">
                                            Delivery 3</td>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px; border-right: 1px #000000 solid;">
                                            Approx two weeks</td>
                                    </tr>
                                    <tr>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px; border-bottom: 1px #000000 solid;">
                                            Delivery 4</td>
                                        <td style="border-left: 1px #000000 solid; border-top:1px #000000 solid; padding: 8px; border-bottom: 1px #000000 solid; border-right: 1px #000000 solid;">
                                            To be collected</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    For further information or to contact us on {{\Config::get('constant.HELP_LINE_NUMBER')}}, or visit our <a href="{{\Config::get('constant.front_url')}}">website</a>. Terms and conditions are also available on our website.
                                </p>
                            </td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                @if(!isset($is_for_admin) || $is_for_admin == 0)
                <td style="padding: 10px 10px; border-top: 3px #ef5324 solid;">
                    
                </td>
                @endif
                <td align=" right" style="padding: 10px 10px; border-top: 3px #ef5324 solid;">
                    <img src="{{asset('images/footer-logo.png')}}" alt="{{config('mail.mail_team_name')}}" style="width: 50px; max-width: 50px;">
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>