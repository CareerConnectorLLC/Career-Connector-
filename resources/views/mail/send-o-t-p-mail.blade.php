<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        /* Basic Reset & Body Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif; /* Using Inter as per instructions */
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Container for the email content */
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px; /* Rounded corners for the main container */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Subtle shadow */
            overflow: hidden; /* Clear floats */
        }

        /* Header styles */
        h1, h2, h3, h4, h5, h6 {
            color: #222222;
            margin-top: 0;
            margin-bottom: 15px;
        }

        /* Paragraph styles */
        p {
            margin-bottom: 15px;
        }

        /* List styles */
        ul {
            margin-top: 0;
            margin-bottom: 15px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 8px;
        }

        /* Call to Action Button */
        .button {
            display: inline-block;
            padding: 12px 25px;
            margin: 20px 0;
            background-color: #007bff; /* Primary blue */
            color: #ffffff !important; /* Important to override default link color */
            text-decoration: none;
            border-radius: 6px; /* Rounded corners for buttons */
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Footer styles */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            font-size: 12px;
            color: #777777;
            text-align: center;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 20px;
                margin: 10px;
                border-radius: 0; /* No rounded corners on very small screens */
            }
            .button {
                display: block;
                width: 100%;
                box-sizing: border-box; /* Include padding in width */
            }
        }
    </style>
</head>
<body>
    <table role="presentation" class="email-container" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="padding: 20px 0;">
                <p style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ asset('frontend_assets/images/Logo.png') }}" alt="Company Logo" style="max-width: 150px; height: auto; border-radius: 4px;">
                </p>
                <h3 style="font-size: 24px; margin-bottom: 20px; text-align: center;">
                    <b>{{ config('app.name') }}</b>
                </h3>
                
                <p>Dear {{ $name }},</p>
                <p><i>Thank you for signing up.</i></p>
                <p>To complete your action and verify your email address, please use the following One-Time Password (OTP):</p>
                <p><b>{{ $otp }}</b></p>
                <p>Best regards,</p>
                <p>The {{ config('app.name') }} Team</p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p>{{ config('app.name') }}<br>
                <a href="{{ url('/') }}" style="color: #007bff; text-decoration: none;">{{ config('app.name') }}</a><br>
            </td>
        </tr>
    </table>
</body>
</html>