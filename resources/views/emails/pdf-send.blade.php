<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0a0a0a;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0a0a0a;
            padding-bottom: 40px;
        }

        .main {
            background-color: #171717;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #ffffff;
            border-top: 4px solid #fbbf24;
        }

        .header {
            background-color: #000000;
            text-align: center;
            padding: 40px 20px;
        }

        .brand-title {
            font-family: 'Permanent Marker', cursive;
            font-size: 38px;
            color: #ffffff;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .brand-accent {
            color: #fbbf24;
        }

        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }

        .document-title {
            color: #fbbf24;
            font-family: 'Permanent Marker', cursive;
            font-size: 24px;
            margin-bottom: 10px;
            border-left: 3px solid #fbbf24;
            padding-left: 15px;
        }

        .kicker {
            color: #fbbf24;
            font-family: monospace;
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background-color: #fbbf24;
            color: #000000 !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 25px;
            text-transform: uppercase;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            padding: 30px;
            font-size: 12px;
            color: #737373;
            background-color: #000000;
        }

        .logo-img {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <tr>
                <td class="header">
                    <img src="{{ asset('img/logo.webp') }}" alt="Two Brothers Logo" class="logo-img">
                    <h1 class="brand-title">TWO <span class="brand-accent">BROTHERS</span></h1>
                </td>
            </tr>

            <tr>
                <td class="content">
                    <p class="kicker">// Envío de Documento</p>
                    <h2 class="document-title">{{ $form['document'] }}</h2>

                    <p style="color: #d4d4d4;">
                        ¡Hola! Se ha generado un nuevo documento para ti. Adjunto a este correo encontrarás el archivo
                        PDF con todos los detalles.
                    </p>

                    <p style="color: #d4d4d4; margin-top: 20px;">
                        Gracias por confiar en <strong>Two Brothers Stickers & Design</strong> para darle ese toque
                        único a tu máquina.
                    </p>

                    <center>
                        <a href="{{ url('/') }}" class="button">Visitar nuestra tienda</a>
                    </center>
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <p style="margin-bottom: 5px;">&copy; {{ date('Y') }} Two Brothers Stickers & Design</p>
                    <p>Ubicación | Contacto | México</p>
                    <div style="margin-top: 15px; border-top: 1px solid #262626; padding-top: 15px;">
                        <small>// Dale un toque único a tu máquina //</small>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
