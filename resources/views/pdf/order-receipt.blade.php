<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden #{{ $order->receipt_no }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
        }
        .order-info, .customer-info, .event-info, .tickets-info {
            margin-bottom: 17px;
        }
        h1 {
            color: #2c3e50;
            font-size: 20px;
        }
        h2 {
            color: #34495e;
            font-size: 16px;
            margin-top: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            margin-top: 18px;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            padding-top: 17px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Fan Tickets</h1>
            <p>Confirmación de Orden #{{ $order->receipt_no }}</p>
        </div>        <div class="order-info">
            <h2>Información de la Orden</h2>
            <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
            @if($order->receipt_no)
            <p><strong>Número de Recibo:</strong> {{ $order->receipt_no }}</p>
            @endif
        </div>

        <div class="payment-info">
            <h2>Información del Pago</h2>
            @if($order->brand)
            <p><strong>Tarjeta:</strong> {{ $order->brand }}</p>
            @endif
            @if($order->issuer)
            <p><strong>Banco Emisor:</strong> {{ $order->issuer }}</p>
            @endif
            @if($order->last4)
            <p><strong>Últimos 4 dígitos:</strong> **** **** **** {{ $order->last4 }}</p>
            @endif
        </div>

        <div class="customer-info">
            <h2>Información del Cliente</h2>
            <p><strong>Nombre:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <div class="event-info">
            <h2>Información del Evento</h2>
            <p><strong>Evento:</strong> {{ $event->event }}</p>
            <p><strong>Fecha:</strong> {{ $event->formatted_date }}</p>
            <p><strong>Recinto:</strong> {{ $event->venue }}</p>
        </div>

        <div class="tickets-info">
            <h2>Tickets</h2>
            <table>
                <thead>
                    <tr>
                        <th>Ticket digital</th>
                        <th>Información</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->info }}</td>
                        <td>${{ number_format($ticket->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total">
            <p>Cargo : ${{ number_format($order->total, 2) }}</p>
        </div>

        <div class="footer">
            <p>Gracias por tu compra en Fan Tickets.</p>
            <p>Este documento sirve como comprobante  de tu compra.</p>
            <p>Para cualquier consulta, por favor contáctanos a support@fantickets.com</p>
        </div>
    </div>
</body>
</html>
