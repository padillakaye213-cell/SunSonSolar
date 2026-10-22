<?php
require_once __DIR__ . '/includes/layout.php';
page_head('Privacy Policy');
?>
<div class="page-title"><div class="container"><h1>Privacy Policy</h1><p>Last updated: <?= date('F j, Y') ?></p></div></div>
<section class="section"><div class="container prose">
<p>Sun Son Solar (“we”) respects your privacy and processes personal data in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173).</p>
<h2>What we collect</h2>
<p>Identity and contact details (name, birthdate, gender, email, phone, address), account credentials (passwords are stored only as secure hashes), service and payment records, system performance readings, and — for employees — attendance check-ins with time and GPS location.</p>
<h2>Why we collect it</h2>
<p>To create and manage your account, schedule and deliver services, issue invoices and receipts, send reminders and service updates, provide support, and meet legal obligations. Promotions are sent only according to your preferences.</p>
<h2>Sharing</h2>
<p>We share data only with service providers and utilities needed to complete your service (for example, permit and net-metering applications) and when required by law. We do not sell personal data.</p>
<h2>Retention and security</h2>
<p>We keep records only as long as necessary for the purposes above. Access is role-based, sessions time out automatically, and important actions are written to an audit log.</p>
<h2>Your rights</h2>
<p>You may access, correct, or request deletion of your data, withdraw consent, and file a complaint with the National Privacy Commission. Contact us at <a href="mailto:<?= e(setting('company_email')) ?>"><?= e(setting('company_email')) ?></a>.</p>
<p class="small muted">This is a template policy. Please have it reviewed by legal counsel before launch.</p>
</div></section>
<?php page_foot(); ?>
