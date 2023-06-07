<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        div {
            padding: 10px;
        }

        th,
        td {
            text-align: left;
        }
    </style>
</head>

<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box; background-color: #fff; color: #74787e; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
    <div>
        <p>Hello, Administrator</p>

        @if(isset($order) && !empty($order))
        <p>{{$order->user->name}} has {{$action}} below order.</p>
        @include('contents.product_list_mail')
        @endif
        @if(isset($orders) && $orders->count() > 0)
        <p>{{$orders->first()->user->name}} has {{$action}} below orders.</p>
        @foreach($orders as $order)
        @include('contents.product_list_mail')
        @if(!$loop->last)
        <br />
        @endif
        @endforeach
        @endif
    </div>
</body>

</html>