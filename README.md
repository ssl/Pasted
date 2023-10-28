# Pasted
Simple and secure pastebin alternative. Encrypts your content client-side with AES. 

Source code of pasted.pw.

### Features
- Post (client-side AES encrypted) pastes (Anonymous or with your account)
- View/delete all your pastes via an account
- Login, register and change password functionallity
- Admin panel which can view/delete all pastes (ofcourse not able to view encrypted pastes content without the password)
- Admin panel which can view/delete users
- Admin panel which can enable/disable registration
- Should be pretty secure against most basic things (does not prevent things like rate limiting)

### Demo / Screenshots
- https://pasted.za.ax
- https://imgur.com/a/624zWpZ

### Install and use on your own
Using this application should be very simple following these steps;
- Upload the files on a host (with SSL enabled) and route traffic through /public/ folder
- Fill in your database info in app/config/database.php and generate a random salt for app/config/app.php
- Upload the SQL (pasted.sql) in your database
- Ready to use! Create your (admin) account or change the password of the 'admin' user.
