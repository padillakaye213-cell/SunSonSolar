# SunSonSolar Registration System

## Team Name
**JustCommit**

## How the System Works

### Registration
- The user fills out the registration form and chooses an account type: Customer or Employee.
- If Employee is chosen, a department dropdown appears and must be selected.
- On submit, CodeIgniter validates all inputs server-side:
  - Email must be valid and unique
  - Username must be at least 4 characters and unique
  - Password must be at least 8 characters and match the confirmation
- If valid, the account is created with a hashed password using `password_hash()`.
- If invalid, error messages appear at the top of the form.

### Sign-In
- The user enters their username and password.
- The system looks up the username and verifies the password using `password_verify()`.
- If correct, a session is created and the user is redirected by role:
  - Admin → `/admin/dashboard`
  - Employee → `/employee/dashboard`
  - Customer → `/customer/dashboard`
- If incorrect, an error message appears.

### Role-Based Access
- Each dashboard is protected. A user can only access the dashboard matching their role.

### Logout
- Visiting `/logout` destroys the session and returns to the sign-in page.

---

## How to Install

### Requirements
- XAMPP (Apache + MySQL + PHP 8.1 or higher)
- The `intl` extension enabled in PHP

### Steps

1. **Place the project in XAMPP**


2. **Import the database**
- Open `http://localhost/phpmyadmin`
- Create a database named `sunsonsolar`
- Import `sunsonsolar.sql`

3. **Create the `.env` file**
- Copy the `env` file in the project root and rename the copy to `.env`
- Set these values:


4. **Enable the `intl` extension**
- Open `C:\xampp\php\php.ini`
- Remove the `;` before `extension=intl`
- Restart Apache

5. **Run the app**
- Open your browser to:
