# Pasted
A simple and secure Pastebin alternative that encrypts content client-side using AES.

### Features
- Create client-side AES-encrypted pastes (anonymous or linked to your account)
- Build-in short or custom URLs to the paste
- View and delete all your pastes via your account
- Login, registration, and password change functionality
- Admin panel to manage pastes (unable to view encrypted content without the correct password)
- Admin panel to manage users (view/delete users)
- Admin control to enable/disable; user registration, short/custom URLs
- Built with security in mind, addressing basic vulnerabilities (does not include rate limiting)

### Demo / Screenshots
- Live Demo: [https://pasted.za.ax](https://pasted.za.ax)
- Screenshots: [https://imgur.com/a/624zWpZ](https://imgur.com/a/624zWpZ)

### Installation Guide
Setting up this application is simple. Follow these steps:

1. Upload the files to a web host (ensure SSL is enabled) and route traffic through the `/public_html/` folder.
2. Configure your database settings in `app/config/database.php`.
3. Import the SQL file (`pasted.sql`) into your database.
4. You're all set! Log in to the admin account with the default credentials (`admin:admin`), and be sure to change the password.
