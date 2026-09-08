<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            width: 7.5cm;
            margin: 0 auto;
            padding: 10px;
        }

        .receipt {
            width: 7.5cm;
            border: 1px lightgray solid;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
            font-size: 14px;
            margin: 5px 0;
        }

        table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 3px 0;
        }

        .total {
            margin-top: 10px;
            font-weight: bold;
            text-align: right;
            font-size: 13px;
        }

        .btn-print {
            margin-top: 10px;
            width: 100%;
            padding: 8px;
            border: none;
            background: black;
            color: white;
            font-size: 12px;
            cursor: pointer;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="receipt">

    <h2>SALES RECEIPT</h2>

    <table>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
        </tr>

        @foreach($cart as $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>{{ $item['quantity'] ?? 1 }}</td>
            <td>{{ number_format($item['price'], 2) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="total">
        TOTAL: ₱{{ number_format($total, 2) }}
    </div>

    <button class="btn-print" onclick="window.print()">
        PRINT RECEIPT
    </button>

</div>

</body>
</html>