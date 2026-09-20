<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activá tu cuenta de CB Tiendas</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0efff; font-family: Arial, Helvetica, sans-serif; color: #161a32;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0efff; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; overflow: hidden; border: 1px solid #dee0ff; border-radius: 18px; background-color: #ffffff;">
                    <tr>
                        <td style="padding: 30px 32px; background-color: #0f5238;">
                            <p style="margin: 0 0 8px; color: #b1f0ce; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Panel de emprendedores</p>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; line-height: 1.2;">CB Tiendas</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h2 style="margin: 0 0 18px; color: #161a32; font-size: 24px; line-height: 1.25;">Activá tu cuenta</h2>
                            <p style="margin: 0 0 18px; font-size: 16px; line-height: 1.6;">Hola, <strong>{{ $user->name }}</strong>.</p>
                            <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6;">Confirmá tu correo electrónico para ingresar al panel y administrar tus emprendimientos.</p>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 26px;">
                                <tr>
                                    <td style="border-radius: 10px; background-color: #0f5238;">
                                        <a href="{{ $verificationUrl }}" target="_blank" rel="noreferrer" style="display: inline-block; padding: 14px 22px; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none;">Verificar mi cuenta</a>
                                    </td>
                                </tr>
                            </table>
                            <div style="padding: 16px; border-left: 4px solid #005fad; background-color: #d4e3ff; color: #003869; font-size: 14px; line-height: 1.6;">
                                Por seguridad, este enlace vence en {{ $expirationMinutes }} minutos. Si no creaste esta cuenta, podés ignorar el mensaje.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 32px; background-color: #f8f8fc; text-align: center; color: #646985; font-size: 12px; line-height: 1.5;">
                            CB Tiendas · Coronel Bogado, Itapúa, Paraguay
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
