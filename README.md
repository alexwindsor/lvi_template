

## Laravel Vue3 InertiaJS Authenication Template

This is a template for a Laravel 10 / Vue 3 / InertiaJS 1.0 project that includes pages and routes for :

#### Pages: (get)
* Home
* Login
* Logout
* Register
* Edit Profile

#### Routes only:
* Login (post)
* Logout (post)
* Create Account (post)
* Update Profile (put)
* Delete Account (put - not delete because router.delete doesn't pass through data to the controller, unfortunately)

I make many projects and host them on my own server using simply my BT Broadband connection and it works well. I have a free domain http://alexphpdev.ddns.net. To deal with not having a static IP, there is a program running on the server called noip2 that regularly checks what my IP address is, and updates the DNS servers when it changes.

### Deploying on a subdirectory

A problem that I have is deploying my projects from (eg. `http://localhost:8000`) to a subfolder on the apache server (eg.`http://alexphpdev.ddns.net/my-project`) is an issue with routes (eg. `http://alexphpdev.ddns.net/my-project/login`) that would go to (`http://alexphpdev.ddns.net/login` instead of `http://alexphpdev.ddns.net/my-project/login`).

To solve this, in the .env file there is a value (in development):

`VITE_SERVER_SUBDIR="/"` ..that needs to be changed to:

`VITE_SERVER_SUBDIR="/my-project/"` ..when deployed

When linking to other routes in the project, there is a file called `baseurl.js` that needs to be imported into a .vue file, then links should be done like this:

`<Link :href="base_url + 'logout'">Logout</Link>`

..or

`router.post(base_url + 'login', form)`

If you use my script to deploy the project (see: `https://github.com/alexwindsor/laravel-deploy`) then the `VITE_SERVER_SUBDIR` environment variable is changed automatically and all you have to remember is to import and use `base_url`.

### To Do:

* Feature tests
* Email verification feature
* 'Remember me on this device' option