<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Laravel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <style>
            .verify_button {
                background-color: rgb(96 165 250); /* Tailwind's blue-400 */
                color: #ffffff; /* text-white */
                padding: 0.25rem; /* p-1 → 4px */
                margin: 0.25rem;  /* m-1 → 4px */
                border-radius: 0.25rem; /* rounded → 4px */
                text-decoration: none;
            }
        </style>
    </head>

    <body style="font-family: 'Inter', sans-serif;">
        <h1>
            Thank you for registering with our website.
        </h1>

        <p>
            Please click on the button below in order to verify your email address, in order to be able to make full use of the website.
        </p>

        <a href="{{ $link }}" target="_blank" class="verify_button"> Verify your Email</a>

        <p>
            This link expires 24 hours after it was sent. To have a new link, click <a href="http://localhost:8000/verify_email" target="_blank">here</a>.
        </p>



    </body>
</html>

