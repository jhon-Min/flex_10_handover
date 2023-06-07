<table style="width: 100%;font-family: sans-serif;border:1px solid #c0c0c0;font-size: 12px;" cellpadding="10" cellspacing="0" align="center">
    <tr>
        <td colspan="2">
            <table>
                <tr>
                    <th>Order No. : </th>
                    <td>{{$order->order_number}}</td>
                    <th>Order Date : </th>
                    <td>{{date('d/m/Y',strtotime($order->created_at))}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr bgcolor="#f8f8f8">
        <td colspan="2">
            <table border="1" bordercolor="#ccc" cellspacing="0" cellpadding="10" width="100%">
                <tr>
                    <th>Part No.</th>
                    <th>Description</th>
                    <th>qty</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
                @foreach($order->items as $item)
                <tr align="center">
                    <td>{{$item->product->product_nr}}</td>
                    <td>{{$item->product->name}} </td>
                    <td>{{$item->qty}}</td>
                    <td>${{number_format((float)$item->price, 2, '.', '')}}</td>
                    <td>${{number_format((float)$item->total, 2, '.', '')}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" align="right" style="border-top: 1px solid #000;border-bottom: 1px solid #000">
                        <table width="100%">
                            <tr>
                                <td width="50%">
                                    <b>Subtotal(<small>ex GST</small>)</b>
                                </td>
                                <td align="right">
                                    ${{ number_format((float)$order->subtotal, 2, '.', '')}}
                                </td>
                            </tr>
                            <tr>
                                @php
                                $gst = config('constant.invoice.gst');
                                @endphp
                                <td><b>GST({{$gst}}%)</b></td>
                                <td align="right">${{ number_format((float)$order->gst, 2, '.', '') }}</td>
                            </tr>
                            <tr>
                                <td><b>Delivery</b></td>
                                <td align="right">${{ number_format((float)$order->delivery, 2, '.', '')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" align="right" style="border-bottom: 1px solid #000">
                        <table width="100%">
                            <tr>
                                <td width="50%">
                                    <b><big>Total</big></b>
                                </td>
                                <td align="right" width="50%">
                                    <big><b>${{ number_format((float)$order->total, 2, '.', '') }}</b></big>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>