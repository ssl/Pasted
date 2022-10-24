# Pasted
Simple and secure pastebin alternative. Encrypts your content client-side with AES.

### Features
- Post (client-side AES encrypted) pastes (Anonymous or with your account)
- View/delete all your pastes via an account
- Login, register and change password functionallity
- Admin panel which can view/delete all pastes (ofcourse not able to view encrypted pastes content without the password)
- Admin panel which can view/delete users
- Admin panel which can enable/disable registration
- Should be pretty secure against most basic things (does not prevent things like rate limiting)

### Demo / Screenshots
- https://pasted.pw
- https://imgur.com/a/624zWpZ

### Objective
I got challenged to make a secure PHP MVC app in a few days without using any frameworks. The requirements;
- application should not use a framework
- application should implement MVC in object oriented PHP
- source code is well formatted, clear and readable
- no other files should be in the webroot besides the index.php and optional static resources
- application should use PHP session functionality
- application should be secure (think of protection against SQL injection, XSS, CSRF, missing authorization)

### Install and use on your own
Using this application should be very simple following these steps;
- Upload the files on a host (with SSL enabled) and route traffic through /public/ folder
- Fill in your database info in app/config/database.php and generate a random salt for app/config/app.php
- Upload the SQL (pasted.sql) in your database
- Ready to use! Login to the admin account with password Test123! and change the password.
