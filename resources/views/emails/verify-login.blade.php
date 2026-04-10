<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre connexion</title>
    <style>
        body, table, td, p {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, sans-serif;
        }
        :root {
            --color-primary: hsl(0deg 66.67% 20%);
            --color-primary-light: hsl(0deg 66.67% 40%);
            --color-primary-dark: hsl(0deg 66.67% 10%);
            --color-secondary: hsl(0deg 66.67% 10%);
            --color-secondary-light: hsl(0deg 66.67% 30%);
            --color-main: hsl(0deg 66.67% 20%);
            --color-main-dark: hsl(0deg 66.67% 10%);
            --color-main-light: hsl(0deg 66.67% 30%);
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="min-width: 100%;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <!-- Email content container -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                    <!-- Logo section -->
                    <tr>
                        <td align="center" style="padding: 32px 0 16px;">
                            <img src="https://shawnagency.fr/wp-content/uploads/2025/01/Logo_Transparent_01.png" alt="Logo" style="width: 56px; height: 56px; object-fit: cover;">
                        </td>
                    </tr>

                    <!-- Content section -->
                    <tr>
                        <td style="padding: 0 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding-bottom: 16px;">
                                        <h1 style="color: var(--color-primary-dark); font-size: 24px; margin: 0;">Vérification de votre connexion</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 16px;">
                                        <p style="color: var(--color-primary); line-height: 1.5; margin: 0;">Bonjour,</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: var(--color-primary); line-height: 1.5; margin: 0;">Pour vérifier votre connexion, veuillez saisir le code suivant :</p>
                                        <h3 style="color: var(--color-secondary); font-size: 28px; margin: 16px 0; text-align: center;">{{ $verificationCode }}</h3>
                                        <p style="color: var(--color-primary); line-height: 1.5; margin: 0;">Si vous n'avez pas demandé cette connexion, veuillez ignorer cet e-mail.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; border-top: 1px solid var(--color-primary-light);">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <p style="color: var(--color-primary); font-size: 14px; margin: 0;">Ceci est un e-mail automatique, veuillez ne pas répondre.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>