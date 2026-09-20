@php
    $statusContent = $changesSubmitted ? [
        'eyebrow' => 'Cambios recibidos',
        'title' => 'Tu tienda está en revisión',
        'message' => 'Guardamos los cambios enviados. Para proteger la información del catálogo, la tienda no se mostrará públicamente hasta que un administrador los revise y apruebe.',
        'label' => 'En revisión',
        'color' => '#005fad',
        'softColor' => '#d4e3ff',
        'action' => 'Te notificaremos por este medio cuando la revisión haya finalizado.',
    ] : match ($status) {
        'approved' => [
            'eyebrow' => 'Tu tienda ya está visible',
            'title' => 'Tienda aprobada',
            'message' => 'La tienda ya fue aprobada y puede ser consultada públicamente en CB Tiendas.',
            'label' => 'Aprobada',
            'color' => '#0f5238',
            'softColor' => '#dff7ee',
            'action' => 'Gracias por formar parte de nuestra comunidad local.',
        ],
        'rejected' => [
            'eyebrow' => 'Actualización de revisión',
            'title' => 'Tienda no aprobada',
            'message' => 'Luego de revisar la información enviada, por el momento no podemos aprobar la tienda.',
            'label' => 'No aprobada',
            'color' => '#93000a',
            'softColor' => '#ffdad6',
            'action' => 'Puedes revisar la información y volver a contactarnos para realizar una nueva solicitud.',
        ],
        'inactive' => [
            'eyebrow' => 'Actualización de la tienda',
            'title' => 'Tienda inactivada',
            'message' => 'La tienda fue marcada como inactiva y ya no se muestra temporalmente en el catálogo público.',
            'label' => 'Inactiva',
            'color' => '#646985',
            'softColor' => '#eef0f7',
            'action' => 'Si necesitas más información, puedes comunicarte con el equipo administrador.',
        ],
        default => [
            'eyebrow' => 'Coronel Bogado Tiendas',
            'title' => 'Tienda registrada',
            'message' => 'La tienda fue registrada correctamente y quedó pendiente de revisión por parte de nuestros administradores.',
            'label' => 'En revisión',
            'color' => '#0f5238',
            'softColor' => '#dff7ee',
            'action' => 'Te notificaremos por este medio cuando el proceso haya finalizado.',
        ],
    };
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $statusContent['title'] }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0efff; font-family: Arial, Helvetica, sans-serif; color: #161a32;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0efff; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 18px; overflow: hidden;">
                    <tr>
                        <td style="background-color: #0f5238; padding: 32px; text-align: left;">
                            <p style="margin: 0 0 8px; color: #a8f0c6; font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;">{{ $statusContent['eyebrow'] }}</p>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; line-height: 1.2; font-weight: 700;">{{ $statusContent['title'] }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 36px 32px;">
                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6;">Hola,</p>
                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6;">Te informamos que el estado de <strong style="color: #0f5238;">{{ $store->name }}</strong> fue actualizado.</p>
                            <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6;">{{ $statusContent['message'] }}</p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 24px; background-color: {{ $statusContent['softColor'] }}; border-left: 4px solid {{ $statusContent['color'] }};">
                                <tr>
                                    <td style="padding: 16px; color: {{ $statusContent['color'] }}; font-size: 15px; line-height: 1.5;">Estado: <strong>{{ $statusContent['label'] }}</strong></td>
                                </tr>
                            </table>
                            <p style="margin: 0; font-size: 16px; line-height: 1.6;">{{ $statusContent['action'] }}</p>
                            @if ($publicUrl)
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin-top: 28px;">
                                    <tr>
                                        <td style="border-radius: 10px; background-color: #0f5238;">
                                            <a href="{{ $publicUrl }}" target="_blank" rel="noreferrer" style="display: inline-block; padding: 14px 22px; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none;">Ver mi tienda publicada</a>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 32px; background-color: #f8f8fc; text-align: center; color: #646985; font-size: 12px;">
                            Este es un mensaje automático. Por favor, no respondas a este correo.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
