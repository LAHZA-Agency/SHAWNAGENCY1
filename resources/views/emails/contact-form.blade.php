<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        body,
        table,
        td,
        p {
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
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="min-width: 100%;">
    <tr>
        <td align="center" style="padding: 40px 20px;">
            <!-- Email content container -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                <!-- Logo section -->
                <tr>
                    <td align="center" style="padding: 32px 0 16px;">
                        <img loading="lazy" src="https://shawnagency.fr/wp-content/uploads/2025/01/Logo_Transparent_01.png" alt="Logo" style="width: 56px; height: 56px;object-fit: cover;" />
                    </td>
                </tr>

                <!-- Content section -->
                <tr>
                    <td style="padding: 0 32px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="padding-bottom: 16px;">
                                    <h1 style="color: var(--color-secondary); font-size: 24px; margin: 0;">Nouveau message de contact</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 16px;">
                                    <p style="color: var(--color-primary); line-height: 1.5; margin: 0;">Vous avez reçu un nouveau message via le formulaire de contact de votre site.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 8px;">
                                    <strong style="color: var(--color-secondary);">Nom:</strong> <span style="color: var(--color-primary);">{{ $name }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 8px;">
                                    <strong style="color: var(--color-secondary);">Email:</strong> <span style="color: var(--color-primary);">{{ $email }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 8px;">
                                    <strong style="color: var(--color-secondary);">Téléphone:</strong> <span style="color: var(--color-primary);">{{ $tel }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 24px;">
                                    <strong style="color: var(--color-secondary);">Message:</strong>
                                    <p style="color: var(--color-primary); line-height: 1.5; margin-top: 4px;">{{ $contactMessage }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding: 24px 32px; border-top: 1px solid var(--color-primary);">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center">
                                    <p style="color: var(--color-primary); font-size: 14px; margin: 0;">Ceci est un email automatique, veuillez ne pas répondre.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</html>