<table style="width: 100%;font-family: sans-serif;border:1px solid #000;font-size: 12px;" cellpadding="10" cellspacing="0" align="center">
    <tr>
        <td align="left" style="border-bottom: 2px solid #000">
            <img src="https://www.flexibledrive.com.au/static/themes/theme-1/images/logos/flexible-drive-logo.svg">
            <br />
            <b>QUOTE/ PROFORMA INVOICE</b>
        </td>
        <td align="right" style="border-bottom: 2px solid #000">
            
        </td>
    </tr>
    <tr bgcolor="#fff">
        <td align="left">
            <b>Charge to:</b> <Br />
              {!!$order->user->company_name!!}<br />
              {!!$order->user->address_line1!!}<br />
              {!!$order->user->address_line2!!}<br />
              {!!$order->user->state!!} {!!$order->user->zip!!}<br />
            <br />
            @if ( $order->delivery_info['delivery'] != null ) 
            <p>
              <b>Deliver to:</b> <br />
              {!!$order->user->company_name!!}<br />
              {!! $order->delivery_info['delivery']['address_line1'] !!}<br />
              {!! $order->delivery_info['delivery']['address_line2'] !!}<br />
              {!! $order->delivery_info['delivery']['state'] !!} {!! $order->delivery_info['delivery']['zip'] !!}<br />
              <br />
            </p>
            @endif
            @if ( $order->delivery_info['pickup'] != null ) 
            <p>
              <b>Pickup From:</b> <br />
              {!! $order->delivery_info['pickup']['name'] !!}<br />
              {!! ($order->delivery_info['pickup']['address']) ? $order->delivery_info['pickup']['address'] : '' !!}<br />
              {!! $order->delivery_info['pickup']['city'] !!}<br />
              {!! $order->delivery_info['pickup']['state'] !!}<br />
              <br />
            </p>
            @endif
            @if ( $order->delivery_info['pickup'] == null && $order->delivery_info['delivery'] == null) 
            <p>
              <b>Pickup From:</b> <br />
                Interstate Pickup
              <br />
            </p>
            @endif
        </td>
        <td align="right">
            <table width="100%">
                <tr>
                    <td> <b>Quote No.</b> </td>
                    <td> : {{$order->order_number}} </td>
                </tr>
                <tr>
                    <td> <b>Quote Date.</b> </td>
                    <td> : {{date('d/m/Y',strtotime($order->created_at))}} </td>
                </tr>
                <tr>
                    <td> <b>Account No.</b> </td>
                    <td> : {{$order->user->account_code}}</td>
                </tr>
                <tr>
                    <td> <b>Customer Ref</b> </td>
                    <td> : {{ ($order->reference_number) ? $order->reference_number : '--' }} </td>
                </tr>
                <tr>
                    <td> <b>Sales Rep</b> </td>
                    <td> : -- </td>
                </tr>
                <tr>
                    <td> <b>Freight</b> </td>
                    <td> : {{ config('constant.invoice.freight') }} </td>
                </tr>
                <tr>
                    <td> <b>Currency</b> </td>
                    <td> : {{ config('constant.invoice.currency') }} </td>
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
                    <th>Len.</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Ext. Price</th>
                </tr>

                @foreach($order->items as $item)

                <tr align="center">
                    <td>{{$item->product->product_nr}}</td>
                    <td>{{$item->product->name}}</td>
                    <td> </td>
                    <td>{{$item->qty}}</td>
                    <td>${{ number_format((float)$item->price, 2, '.', '')}}</td>
                    <td>${{ number_format((float)$item->total, 2, '.', '')}}</td>
                </tr>

                @endforeach
                <tr align="center">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr align="center">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr bgcolor="#fff">
        <td width="70%"></td>
        <td align="right" style="border-top: 2px solid #000;border-bottom: 2px solid #000">
            <table width="100%">
                <tr>
                    <td>
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
                    <td><b>Freight</b></td>
                    <td align="right">{{ number_format((float)config('constant.invoice.freight'), 2, '.', '') }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr bgcolor="#fff">
        <td width="70%"></td>
        <td width="30%" align="right" style="border-bottom: 2px solid #000">
            <table width="100%">
                <tr>
                    <td>
                        <b><big>Total</big></b>
                    </td>
                    <td align="right">
                        <big><b>${{ number_format((float)$order->total, 2, '.', '') }}</b></big>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr bgcolor="#fff">
        <td colspan="2">
            <Br />
        </td>
    </tr>
    <tr bgcolor="#fff">
        <td colspan="2" style="border-top: 2px solid #000"></td>
    </tr>
    <tr bgcolor="#f9f9f9">
        <td colspan="2">
            <table width="100%">
                <tr>
                    <td>
                        <b>Fiexible Drive Pty Ltd</b> <Br />
                        ABN 15 004 922 570 <br />
                        3 CONCORDE DRIVE <Br />
                        KEILOR PARK VIC 3042 <Br />
                        AUSTRALIA
                    </td>
                    <td>
                        <b>NEW Bank Details</b> <Br />
                        <b>EFT </b> : NAB <Br />
                        <b>BSB </b> : 083 419 <Br />
                        <b>ACCT </b> : 43 697 6047 <Br />
                        SWIFT code NATAAU3303M
                    </td>
                    <td>
                        <b>National Sales</b> <br />
                        <b>T </b> : {{\Config::get('constant.HELP_LINE_NUMBER')}} <br />
                        <b>E </b> : vicsales@flexibledrive.com.au<br />
                        <b>W </b> : flexibledrive.com.au<br />
                    </td>
                </tr>

            </table>
        </td>
    </tr>
    <tr bgcolor="#f9f9f9">
        <td colspan="2">
            <b>Please use account number reference on payment</b> <Br />
            <b>Terms : </b> This quotation is a whole quote and holds firm until {{date('d/m/Y')}} <Br />
            Any alterations to items or quantities may incur a price change in accepting this quotation the customer accepts
            Flexible Drive standard terms and conditions of sale.
        </td>
    </tr>
</table>
