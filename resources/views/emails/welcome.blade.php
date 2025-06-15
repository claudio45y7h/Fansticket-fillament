<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido a Fan Tickets</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #8A2BE2;
        }
		.exol{
		color: #ffff;
	 font-weight: bold;
     font-size: 14px;
			
		}
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
        }
        .button {
            display: inline-block;
            background-color: #8A2BE2;
            color: #ffff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            margin-top: 20px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Fan Tickets</div>
        </div>
        
        <div class="content">
            <h2>¡Hola {{ $user->name }}!</h2>
            
            <p>¡Bienvenido a Fan Tickets! Estamos emocionados de tenerte como parte de nuestra comunidad.</p>
            
            <p>Ahora podrás:</p>
            <ul>
                <li>Comprar entradas para tus eventos favoritos</li>
                <li>Recibir notificaciones sobre nuevos eventos</li>
                <li>Guardar tus boletos digitales en un solo lugar</li>
                <li>Compartir experiencias con otros fans</li>
                <li>Acceder a ofertas exclusivas y descuentos</li>
            </ul>
            
            <p>Para comenzar a explorar los eventos disponibles, haz clic en el botón a continuación:</p>
            
            <a href="{{ url('https://fantickets.lat/') }}" class="button"><p class="exol">Explorar Eventos</p></a>
            
            <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos enviando un correo a soporte@fantickets.lat o a través de nuestro centro de soporte.</p>
            
            <p>¡Gracias por unirte a nosotros!</p>
            
            <p>Atentamente,<br>El equipo de Fan Tickets</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Fan Tickets. Todos los derechos reservados.</p>
            <p>
                Si no deseas recibir más correos, puedes <a href="{{ url('/unsubscribe') }}">darte de baja aquí</a>.
            </p>
        </div>
    </div>
</body>
</html>
