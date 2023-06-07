<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flexible Invoice</title>
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @if(isset($order) && !empty($order))
        @include('contents.invoice_content')
    @endif
    @if(isset($orders) && $orders->count() > 0)
        @foreach($orders as $order)
            @include('contents.invoice_content')
            @if(!$loop->last)
            <div class="page-break"></div>
            @endif
        @endforeach
    @endif
</body>

</html>