<?php

function generate_template_mail($args)
{
    $home_url = $args["home_url"];
    $name = $args["name"];
    $mail = $args["mail"];
    $cedula_de_ciudadania = $args["cedula_de_ciudadania"];
    $event_name = $args["event_name"];
    $description = $args["description"];
    $telefono = $args["telefono"];
    $team = $args["team"];

    $template = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="font-family:arial, helvetica neue, helvetica, sans-serif">

                <head>
                    <meta charset="UTF-8">
                    <meta content="width=device-width, initial-scale=1" name="viewport">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta content="telephone=no" name="format-detection">
                    <title>Nuevo Evento</title><!--[if (mso 16)]>
                    <style type="text/css">
                    a {text-decoration: none;}
                    </style>
                    <![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]>
                <xml>
                    <o:OfficeDocumentSettings>
                    <o:AllowPNG></o:AllowPNG>
                    <o:PixelsPerInch>96</o:PixelsPerInch>
                    </o:OfficeDocumentSettings>
                </xml>
                <![endif]--><!--[if !mso]><!-- -->
                    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet"><!--<![endif]-->
                    <style type="text/css">
                        #outlook a {
                            padding: 0;
                        }

                        .es-button {
                            mso-style-priority: 100 !important;
                            text-decoration: none !important;
                        }

                        a[x-apple-data-detectors] {
                            color: inherit !important;
                            text-decoration: none !important;
                            font-size: inherit !important;
                            font-family: inherit !important;
                            font-weight: inherit !important;
                            line-height: inherit !important;
                        }

                        .es-desk-hidden {
                            display: none;
                            float: left;
                            overflow: hidden;
                            width: 0;
                            max-height: 0;
                            line-height: 0;
                            mso-hide: all;
                        }

                        .es-button-border:hover a.es-button,
                        .es-button-border:hover button.es-button {
                            background: #58dfec !important;
                        }

                        .es-button-border:hover {
                            border-color: #26C6DA #26C6DA #26C6DA #26C6DA !important;
                            background: #58dfec !important;
                            border-style: solid solid solid solid !important;
                        }

                        @media only screen and (max-width:600px) {

                            p,
                            ul li,
                            ol li,
                            a {
                                line-height: 150% !important
                            }

                            h1,
                            h2,
                            h3,
                            h1 a,
                            h2 a,
                            h3 a {
                                line-height: 120%
                            }

                            h1 {
                                font-size: 30px !important;
                                text-align: center
                            }

                            h2 {
                                font-size: 24px !important;
                                text-align: left
                            }

                            h3 {
                                font-size: 20px !important;
                                text-align: left
                            }

                            .es-header-body h1 a,
                            .es-content-body h1 a,
                            .es-footer-body h1 a {
                                font-size: 30px !important;
                                text-align: center
                            }

                            .es-header-body h2 a,
                            .es-content-body h2 a,
                            .es-footer-body h2 a {
                                font-size: 24px !important;
                                text-align: left
                            }

                            .es-header-body h3 a,
                            .es-content-body h3 a,
                            .es-footer-body h3 a {
                                font-size: 20px !important;
                                text-align: left
                            }

                            .es-menu td a {
                                font-size: 14px !important
                            }

                            .es-header-body p,
                            .es-header-body ul li,
                            .es-header-body ol li,
                            .es-header-body a {
                                font-size: 14px !important
                            }

                            .es-content-body p,
                            .es-content-body ul li,
                            .es-content-body ol li,
                            .es-content-body a {
                                font-size: 14px !important
                            }

                            .es-footer-body p,
                            .es-footer-body ul li,
                            .es-footer-body ol li,
                            .es-footer-body a {
                                font-size: 14px !important
                            }

                            .es-infoblock p,
                            .es-infoblock ul li,
                            .es-infoblock ol li,
                            .es-infoblock a {
                                font-size: 12px !important
                            }

                            *[class="gmail-fix"] {
                                display: none !important
                            }

                            .es-m-txt-c,
                            .es-m-txt-c h1,
                            .es-m-txt-c h2,
                            .es-m-txt-c h3 {
                                text-align: center !important
                            }

                            .es-m-txt-r,
                            .es-m-txt-r h1,
                            .es-m-txt-r h2,
                            .es-m-txt-r h3 {
                                text-align: right !important
                            }

                            .es-m-txt-l,
                            .es-m-txt-l h1,
                            .es-m-txt-l h2,
                            .es-m-txt-l h3 {
                                text-align: left !important
                            }

                            .es-m-txt-r img,
                            .es-m-txt-c img,
                            .es-m-txt-l img {
                                display: inline !important
                            }

                            .es-button-border {
                                display: inline-block !important
                            }

                            a.es-button,
                            button.es-button {
                                font-size: 18px !important;
                                display: inline-block !important
                            }

                            .es-adaptive table,
                            .es-left,
                            .es-right {
                                width: 100% !important
                            }

                            .es-content table,
                            .es-header table,
                            .es-footer table,
                            .es-content,
                            .es-footer,
                            .es-header {
                                width: 100% !important;
                                max-width: 600px !important
                            }

                            .es-adapt-td {
                                display: block !important;
                                width: 100% !important
                            }

                            .adapt-img {
                                width: 100% !important;
                                height: auto !important
                            }

                            .es-m-p0 {
                                padding: 0px !important
                            }

                            .es-m-p0r {
                                padding-right: 0px !important
                            }

                            .es-m-p0l {
                                padding-left: 0px !important
                            }

                            .es-m-p0t {
                                padding-top: 0px !important
                            }

                            .es-m-p0b {
                                padding-bottom: 0 !important
                            }

                            .es-m-p20b {
                                padding-bottom: 20px !important
                            }

                            .es-mobile-hidden,
                            .es-hidden {
                                display: none !important
                            }

                            tr.es-desk-hidden,
                            td.es-desk-hidden,
                            table.es-desk-hidden {
                                width: auto !important;
                                overflow: visible !important;
                                float: none !important;
                                max-height: inherit !important;
                                line-height: inherit !important
                            }

                            tr.es-desk-hidden {
                                display: table-row !important
                            }

                            table.es-desk-hidden {
                                display: table !important
                            }

                            td.es-desk-menu-hidden {
                                display: table-cell !important
                            }

                            .es-menu td {
                                width: 1% !important
                            }

                            table.es-table-not-adapt,
                            .esd-block-html table {
                                width: auto !important
                            }

                            table.es-social {
                                display: inline-block !important
                            }

                            table.es-social td {
                                display: inline-block !important
                            }

                            .es-desk-hidden {
                                display: table-row !important;
                                width: auto !important;
                                overflow: visible !important;
                                max-height: inherit !important
                            }
                        }
                    </style>
                </head>

                <body style="width:100%;font-family:arial, helvetica neue, helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
                    <div class="es-wrapper-color" style="background-color:#07023C"><!--[if gte mso 9]>
                            <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                                <v:fill type="tile" color="#07023c"></v:fill>
                            </v:background>
                        <![endif]-->
                        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#07023C">
                            <tr>
                                <td valign="top" style="padding:0;Margin:0">
                                    <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                                        <tr>
                                            <td align="center" style="padding:0;Margin:0">
                                                <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;background-repeat:no-repeat;width:600px;background-image:url(' . $home_url . '/wp-content/uploads/2023/03/rectangle_26.png);background-position:center top" cellspacing="0" cellpadding="0" bgcolor="#ffffff" background="' . $home_url . '/wp-content/uploads/2023/03/rectangle_26.png" align="center">
                                                    <tr>
                                                        <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
                                                            <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                <tr>
                                                                    <td class="es-m-p0r" valign="top" align="center" style="padding:0;Margin:0;width:560px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tr>
                                                                                <td style="padding:0;Margin:0;font-size:0px" align="center"><a target="_blank" href="' . $home_url . '" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#0A8F93;font-size:14px"><img src="' . $home_url . '/wp-content/uploads/2023/03/bosque.png" alt="Logo" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic" title="Logo" height="55"></a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" style="padding:20px;Margin:0">
                                                            <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                <tr>
                                                                    <td align="left" style="padding:0;Margin:0;width:560px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tr>
                                                                                <td style="padding:0;Margin:0;padding-top:20px;padding-bottom:20px;font-size:0px" align="center"><a target="_blank" href="' . $home_url . '" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#0A8F93;font-size:14px"><img src="' . $home_url . '/wp-content/uploads/2023/03/fondo_mobile.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic" height="230"></a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="es-m-p0r" valign="top" align="center" style="padding:0;Margin:0;width:560px">
                                                                        <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                                            <tr>
                                                                                <td align="center" style="padding:0;Margin:0;padding-bottom:20px">
                                                                                    <h2 style="Margin:0;line-height:43px;mso-line-height-rule:exactly;font-family:Orbitron, sans-serif;font-size:36px;font-style:normal;font-weight:bold;color:#10054D">Solicitud Nuevo Evento<br></h2>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" style="padding:0;Margin:0;padding-bottom:15px;padding-top:20px">
                                                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica neue, helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                                                                        <strong>Usuario</strong>:&nbsp; ' . $name . ' <br>
                                                                                        <strong>Email:</strong>&nbsp; ' . $mail . ' <br>
                                                                                        <strong>Cedula de ciudadania:</strong>&nbsp;' . $cedula_de_ciudadania . '<br>
                                                                                        <strong>Teléfono:</strong>&nbsp;' . $telefono . '<br>
                                                                                        <strong>Grupo / Equipo</strong>:&nbsp;' . $team . '<br>
                                                                                        <strong>Nombre del Evento:</strong>&nbsp;&nbsp;' . $event_name . '<br><br>
                                                                                        <strong>Descripción:</strong><br>
                                                                                    </p>
                                                                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, helvetica neue, helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                                                                        &nbsp;' . $description . '
                                                                                        <br>
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </body>

                </html>';
    return $template;
}
