<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sun Son Solar — Create your account</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--blue-dark:#0B4C8C;--blue:#1668B8;--blue-light:#EAF2FB;--white:#FFF;--ink:#132433;--ink-soft:#5C7185;--line:#D7E3EF;--err:#C13A2E;--radius:8px;--font:'Inter',sans-serif;}
        *{box-sizing:border-box;}
        body{font-family:var(--font); background:var(--blue-light); color:var(--ink); margin:0; min-height:100vh;}
        .page{max-width:600px; margin:0 auto; padding:40px 20px 80px;}
        .brand{display:flex; align-items:center; gap:10px; margin-bottom:24px;}
        .brand-mark{width:32px; height:32px; border-radius:50%; background:var(--blue);}
        .brand-name{font-weight:700; font-size:18px; color:var(--blue-dark);}
        .card{background:var(--white); border:1px solid var(--line); border-radius:12px; padding:32px 28px;}
        .card h1{font-size:22px; margin:0 0 6px;}
        .card p{font-size:14px; color:var(--ink-soft); margin:0 0 24px;}
        .card a{color:var(--blue); text-decoration:none; font-weight:600;}
        fieldset{border:0; margin:0 0 24px; padding:0;}
        legend{font-size:13px; font-weight:600; color:var(--blue-dark); margin-bottom:12px; padding:0;}
        .segmented{display:grid; grid-template-columns:1fr 1fr; gap:8px;}
        .seg-option{position:relative;}
        .seg-option input{position:absolute; opacity:0; inset:0; cursor:pointer;}
        .seg-card{border:1.5px solid var(--line); border-radius:var(--radius); padding:12px 14px; text-align:center; font-size:14px; font-weight:600; color:var(--ink-soft);}
        .seg-option input:checked + .seg-card{border-color:var(--blue); background:var(--blue-light); color:var(--blue-dark);}
        .grid-2{display:grid; grid-template-columns:1fr 1fr; gap:14px;}
        .grid-3{display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px;}
        .field{margin-bottom:14px; display:flex; flex-direction:column; gap:5px;}
        .field label{font-size:13px; font-weight:500;}
        .field input, .field select{font-family:var(--font); font-size:14px; padding:10px 12px; border:1.5px solid var(--line); border-radius:var(--radius); outline:none; width:100%;}
        .field input:focus, .field select:focus{border-color:var(--blue); box-shadow:0 0 0 3px rgba(22,104,184,0.15);}
        .btn-primary{width:100%; padding:12px 16px; border:0; border-radius:var(--radius); background:var(--blue); color:#FFF; font-family:var(--font); font-size:15px; font-weight:600; cursor:pointer;}
        .btn-primary:hover{background:var(--blue-dark);}
        .flash-error{background:#FDECEA; border:1px solid var(--err); color:var(--err); padding:12px 14px; border-radius:8px; margin-bottom:16px; font-size:13px;}
        .optional{color:var(--ink-soft); font-weight:400;}
        .radio-row{display:flex; gap:16px; flex-wrap:wrap; padding-top:2px;}
        .radio-opt{display:flex; align-items:center; gap:6px; font-size:14px;}
    </style>
</head>
<body>
<div class="page">
    <div class="brand">
        <span class="brand-mark"></span>
        <span class="brand-name">Sun Son Solar</span>
    </div>

    <div class="card">
        <h1>Create your account</h1>
        <p>Already registered? <a href="<?= site_url('signin') ?>">Sign in instead</a></p>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="flash-error">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('auth/attemptRegister') ?>">
            <?= csrf_field() ?>

            <fieldset>
                <legend>Account type</legend>
                <div class="segmented">
                    <label class="seg-option">
                        <input type="radio" name="accountType" value="customer" checked>
                        <span class="seg-card">Customer</span>
                    </label>
                    <label class="seg-option">
                        <input type="radio" name="accountType" value="employee">
                        <span class="seg-card">Employee</span>
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <legend>Personal details</legend>
                <div class="grid-3">
                    <div class="field">
                        <label for="firstName">First name</label>
                        <input type="text" id="firstName" name="firstname" required>
                    </div>
                    <div class="field">
                        <label for="middleName">Middle name <span class="optional">(optional)</span></label>
                        <input type="text" id="middleName" name="middle_name">
                    </div>
                    <div class="field">
                        <label for="lastName">Last name</label>
                        <input type="text" id="lastName" name="lastname" required>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <label for="birthdate">Date of birth</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                    <div class="field">
                        <label>Gender</label>
                        <div class="radio-row">
                            <label class="radio-opt"><input type="radio" name="gender" value="female" required> Female</label>
                            <label class="radio-opt"><input type="radio" name="gender" value="male"> Male</label>
                            <label class="radio-opt"><input type="radio" name="gender" value="unspecified"> Prefer not to say</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Contact information</legend>
                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <label for="phone">Phone number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="field">
                        <label for="address">Home address</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Employee details</legend>
                <div class="field">
                    <label for="department">Department</label>
                    <select id="department" name="department">
                        <option value="" disabled selected>Select your department</option>
                        <option value="Administration">Administration</option>
                        <option value="IT">IT</option>
                        <option value="Dispatch">Dispatch</option>
                        <option value="Accounting">Accounting</option>
                        <option value="HR">HR</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales</option>
                        <option value="Customer Service">Customer Service</option>
                    </select>
                </div>
            </fieldset>

            <fieldset>
                <legend>Login details</legend>
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required minlength="4">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="field">
                    <label for="confirmPassword">Confirm password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
            </fieldset>

            <button type="submit" class="btn-primary">Create account</button>
        </form>
    </div>
</div>
</body>
</html>