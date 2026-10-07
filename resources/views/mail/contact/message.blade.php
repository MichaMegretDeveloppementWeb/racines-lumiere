<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau message · Racines &amp; Lumière</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1eee6; color: #303425; font-family: Arial, Helvetica, sans-serif; -webkit-text-size-adjust: 100%;">
    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">{{ $contact->name }} vous a écrit depuis le formulaire de contact.</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1eee6;">
        <tr>
            <td align="center" style="padding: 24px 12px;">
                <!--[if mso]><table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"><tr><td><![endif]-->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; table-layout: fixed;">
                    <tr>
                        <td align="center" bgcolor="#303425" style="padding: 28px 24px; border-radius: 20px 20px 0 0;">
                            <img src="{{ $message->embed(public_path('images/brand/logo-email.png')) }}" alt="Racines &amp; Lumière · Rituels bien-être, beauté vivante" width="144" height="161" style="display: block; width: 144px; height: auto; border: 0; color: #dd9b28; font-size: 16px;">
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#fcf8ec" style="padding: 36px 7% 32px;">
                            <p style="margin: 0 0 14px; color: #733106; font-size: 11px; line-height: 18px; letter-spacing: 2px; text-transform: uppercase;">Le formulaire de contact</p>
                            <h1 style="margin: 0 0 12px; color: #303425; font-family: Georgia, 'Times New Roman', serif; font-size: 32px; line-height: 38px; font-weight: normal;">Un nouveau message</h1>
                            <p style="margin: 0; color: #59533d; font-size: 13px; line-height: 22px;">Reçu le {{ now()->timezone('Europe/Paris')->format('d/m/Y à H:i') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 30px 7% 34px;">
                            <p style="margin: 0 0 6px; color: #59533d; font-size: 11px; line-height: 18px; letter-spacing: 1.5px; text-transform: uppercase;">De la part de</p>
                            <p style="margin: 0 0 8px; font-family: Georgia, 'Times New Roman', serif; font-size: 24px; line-height: 32px; overflow-wrap: anywhere; word-break: break-word;">{{ $contact->name }}</p>
                            <p style="margin: 0; font-size: 15px; line-height: 26px; overflow-wrap: anywhere; word-break: break-word;">
                                <a href="mailto:{{ $contact->email }}" style="color: #733106; text-decoration: underline;">{{ $contact->email }}</a>
                            </p>
                            @if ($contact->phone)
                                <p style="margin: 2px 0 0; color: #59533d; font-size: 15px; line-height: 26px; overflow-wrap: anywhere; word-break: break-word;">{{ $contact->phone }}</p>
                            @endif
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr><td height="24" style="height: 24px; font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
                                <tr><td style="border-top: 1px solid #dac0a2; font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
                                <tr><td height="24" style="height: 24px; font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
                            </table>
                            <div style="color: #303425; font-size: 16px; line-height: 28px; overflow-wrap: anywhere; word-break: break-word;">{!! nl2br(e($contact->message)) !!}</div>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin-top: 30px;">
                                <tr>
                                    <td align="center" bgcolor="#733106" style="border-radius: 24px; mso-padding-alt: 14px 24px;">
                                        <a href="mailto:{{ $contact->email }}" style="display: inline-block; padding: 14px 24px; border: 1px solid #733106; border-radius: 24px; color: #fcf8ec; font-size: 14px; line-height: 20px; text-decoration: none; font-weight: bold;">Répondre au message</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 16px 0 0; color: #59533d; font-size: 12px; line-height: 20px;">Vous pouvez aussi utiliser la fonction « Répondre » de votre messagerie.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#fcf8ec" style="padding: 24px; border-radius: 0 0 20px 20px; border-top: 1px solid #dac0a2;">
                            <p style="margin: 0 0 5px; color: #733106; font-family: Georgia, 'Times New Roman', serif; font-size: 18px; line-height: 26px;">Racines &amp; Lumière</p>
                            <p style="margin: 0; color: #59533d; font-size: 12px; line-height: 20px;">Maison de beauté holistique à Sciez</p>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
