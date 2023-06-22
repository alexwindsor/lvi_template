

This is a template for a Laravel 10 / Vue 3 / InertiaJS 1.0 project that includes pages and routes for :

## Pages: (with routes)
* Home
* Login
* Logout
* Register
* Edit Profile

## Routes only:
* Login
* Logout
* Create Account
* Update Profile
* Delete Account

I make many projects and host them on my own server using simply my BT Broadband connection and it works well. I have a free domain http://alexphpdev.ddns.net. To deal with not having a static IP, there is a program running on the server called noip2 that regularly checks what my IP address is, and updates the DNS servers when it changes.

A problem that I have is deploying my projects from (eg. http://localhost:8000) to a subfolder on the apache server (eg. http://alexphpdev.ddns.net/my-project) is an issue with routes (eg. http://alexphpdev.ddns.net/my-project/login) that would go to (http://alexphpdev.ddns.net/login instead of http://alexphpdev.ddns.net/my-project/login.

To solve this, in the .env file there is a value (in development):

VITE_SERVER_SUBDIR="/"

..that needs to be changed to:

VITE_SERVER_SUBDIR="/my-project/"

..when deployed


