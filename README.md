<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## How to test

1. In command line in the project folder run the laravel local server: php artisan serve.
2. In web browser on localhost generate QR by entering this route /generate/{some app name}/{username} (e.g. http://127.0.0.1:8000/generate/QRapp/user5555@rado.com).
3. Scan the QR with Authenticator.
4. Verify TOTP code by entering this route in the web browser /verify/{username}/{totp code from app} (e.g. http://127.0.0.1:8000/verify/user5555@rado.com/356762)
5. It will write in the web browser if this is valid code or if there is any problem.
