<?php
/**
 * One-time setup: loads demo content and creates starter accounts.
 * 1) Import schema.sql  2) Edit config.php  3) Open /setup.php once  4) DELETE this file.
 */
require_once __DIR__ . '/includes/helpers.php';

header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><meta charset="utf-8"><title>Sun Son Solar setup</title><body style="font-family:system-ui;max-width:720px;margin:3rem auto;padding:0 1rem;color:#2f4156">';

try {
    if ((int)q('SELECT COUNT(*) FROM users')->fetchColumn() > 0) {
        echo '<h1>Already set up</h1><p>Users already exist. For security, delete <code>setup.php</code> from the server.</p>';
        exit;
    }
} catch (Throwable $e) {
    echo '<h1>Database not ready</h1><p>Import <code>schema.sql</code> and check <code>config.php</code>.</p><pre>' . e($e->getMessage()) . '</pre>';
    exit;
}

$pdo = db();
$mk = function (array $u) {
    q('INSERT INTO users (first_name,middle_name,last_name,birthdate,gender,email,phone,address,username,password_hash,role,department,privacy_consent,consent_at)
       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,1,NOW())',
      [$u[0], $u[1], $u[2], $u[3], $u[4], $u[5], $u[6], $u[7], $u[8], password_hash($u[9], PASSWORD_DEFAULT), $u[10], $u[11]]);
    return (int)db()->lastInsertId();
};

$accounts = [
  ['Kat', null, 'Admin', '1988-03-14', 'Female', 'admin@sunsonsolar.example', '09170000001', 'Pasig City', 'admin', 'Admin#2026', 'admin', 'Administration'],
  ['Sonny', null, 'Dispatcher', '1986-07-02', 'Male', 'dispatch@sunsonsolar.example', '09170000002', 'Pasig City', 'dispatcher', 'Dispatch#2026', 'dispatcher', 'Dispatch'],
  ['Hana', 'R', 'Reyes', '1990-11-21', 'Female', 'hr@sunsonsolar.example', '09170000003', 'Pasig City', 'hr', 'HumanR#2026', 'hr', 'HR'],
  ['Tomas', 'L', 'Bautista', '1993-05-09', 'Male', 'tech@sunsonsolar.example', '09170000004', 'Pasig City', 'technician', 'Tech#2026', 'technician', 'Dispatch'],
  ['Maria', 'S', 'Dela Cruz', '1991-09-30', 'Female', 'customer@example.com', '09171234567', '12 Kapitolyo St., Pasig City', 'customer', 'Customer#2026', 'customer', null],
];
$ids = [];
foreach ($accounts as $a) $ids[$a[8]] = $mk($a);

// Products
$cat = fn($slug) => (int)q('SELECT id FROM categories WHERE slug=?', [$slug])->fetchColumn();
$products = [
 ['panels','SunPeak 550W Mono PERC','SunPeak','High-efficiency monocrystalline panel with 25-year performance warranty.','Half-cut cell design reduces shading losses. 22.1% efficiency, IP68 junction box, anodized aluminium frame.',"Power: 550 W\nEfficiency: 22.1%\nCells: 144 half-cut mono\nWarranty: 25 yr performance / 12 yr product",9800,40],
 ['panels','SunPeak 450W Residential','SunPeak','Compact panel sized for tight Metro Manila rooftops.','Lightweight 450 W panel ideal for residential roofs. Strong wind and typhoon load rating.',"Power: 450 W\nEfficiency: 21.0%\nWind load: 2400 Pa\nWarranty: 25 yr performance",8200,28],
 ['panels','Bifacial 600W Glass-Glass','Helio','Dual-sided generation for commercial rooftops and carports.','Captures reflected light from the rear for up to 20% extra yield on light-coloured roofs.',"Power: 600 W\nBifaciality: 70%\nGlass: 2.0 mm dual\nWarranty: 30 yr",null,12],
 ['inverters','HybridMax 5kW Inverter','VoltGrid','Hybrid inverter with battery-ready architecture and Wi-Fi monitoring.','Switch between grid, solar and battery automatically. Includes app monitoring.',"Rated output: 5 kW\nMPPT inputs: 2\nBattery: 48 V ready\nWarranty: 10 yr",64500,10],
 ['inverters','StringPro 3kW Grid-Tie','VoltGrid','Reliable grid-tied inverter for small homes.','Quiet, fanless design with 97.6% peak efficiency.',"Rated output: 3 kW\nPeak efficiency: 97.6%\nProtection: IP65\nWarranty: 5 yr",32900,14],
 ['inverters','Commercial 30kW Three-Phase','VoltGrid','Three-phase inverter for shops, factories and farms.','Multiple MPPT trackers, remote firmware updates and utility-grade protection.',"Rated output: 30 kW\nMPPT inputs: 4\nPhase: 3\nWarranty: 10 yr",null,3],
 ['batteries','PowerCell 5.12kWh LiFePO4','Ampere','Wall-mounted lithium iron phosphate battery for backup and self-consumption.','6,000+ cycles, built-in BMS and stackable up to 8 units.',"Capacity: 5.12 kWh\nChemistry: LiFePO4\nCycle life: 6000+\nWarranty: 10 yr",78000,8],
 ['batteries','PowerCell 10kWh Rack','Ampere','Rack battery module for larger homes and small businesses.','High-capacity storage with parallel expansion and CAN/RS485 communication.',"Capacity: 10 kWh\nChemistry: LiFePO4\nDepth of discharge: 90%\nWarranty: 10 yr",null,4],
 ['racking','Roof Rail Kit (Metal Sheet)','MountEx','Aluminium rails and clamps for metal-sheet roofs.','Corrosion-resistant anodized aluminium with stainless hardware.',"Material: 6005-T5 aluminium\nSupports: 6 panels\nWind rating: 60 m/s\nWarranty: 10 yr",5400,35],
 ['racking','Ground Mount Frame (6-panel)','MountEx','Galvanized steel ground-mount frame with adjustable tilt.','Adjustable 10–35° tilt for open lots and farms.',"Material: hot-dip galvanized steel\nTilt: 10–35°\nSupports: 6 panels",null,6],
 ['wires','PV Cable 6mm² (per meter, red/black)','CableOne','UV-resistant, double-insulated solar DC cable.','TÜV-rated cross-linked polyolefin insulation for outdoor use.',"Cross-section: 6 mm²\nRating: 1.8 kV DC\nTemp: -40 to 120°C",95,900],
 ['wires','MC4 Connector Pair','CableOne','Weatherproof connector pair for PV strings.','IP67 rated and tool-lockable for safe, reliable connections.',"Current: 30 A\nVoltage: 1500 V\nRating: IP67",180,150],
];
foreach ($products as $p) {
    q('INSERT INTO products (category_id,name,brand,short_desc,long_desc,specs,price,stock) VALUES (?,?,?,?,?,?,?,?)',
      [$cat($p[0]), $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]]);
}

// FAQs
$faqs = [
 ['How much does a home solar system cost?','Most Metro Manila homes spend between ₱150,000 and ₱450,000 depending on roof size, monthly bill and whether a battery is included. A free consultation gives you an exact quote.'],
 ['How long does installation take?','A typical residential installation takes 1–3 days on site. Permitting and net-metering approval may add a few weeks, which we handle for you.'],
 ['Will solar work during a brownout?','Grid-tied systems shut off during outages for safety. A hybrid inverter with a battery keeps your essential appliances running.'],
 ['Do I need to clean the panels?','Light rain keeps panels fairly clean, but dust and bird droppings reduce output. We recommend a maintenance visit every 6 months.'],
 ['Can I sell extra power back to the grid?','Yes. Through net metering you can export surplus energy to your distribution utility and earn bill credits. We help you file the application.'],
 ['What warranties do you offer?','Panels carry up to 25 years of performance warranty, inverters 5–10 years, and our workmanship is covered for 5 years.'],
 ['How do I check my system’s performance?','Log in to your Sun Son Solar account and open the Monitoring page to see daily generation, consumption and battery level.'],
 ['How do I reschedule a service?','Open your customer dashboard, go to Schedule, and choose Reschedule on the booking. Changes are confirmed by our dispatch team.'],
];
foreach ($faqs as $i => $f) q('INSERT INTO faqs (question,answer,sort_order) VALUES (?,?,?)', [$f[0], $f[1], $i]);

// Posts
$posts = [
 ['How to size a solar system for your home','how-to-size-a-solar-system','blog','Your monthly kWh, roof space and goals decide the right system. Here is a quick method we use on every consultation.',"Start with your last six electricity bills and find your average monthly kWh. Divide by 30 to get daily use, then divide by 4 (average peak sun hours in Luzon) to estimate the kW of panels you need.\n\nNext, check your usable roof area. A 550 W panel takes roughly 2.6 m². Finally, decide whether you want battery backup, because that changes the inverter you need.\n\nOur team can do all of this for free during your consultation."],
 ['Net metering in the Philippines: what homeowners should know','net-metering-philippines','news','Net metering lets you export surplus solar energy and earn bill credits. We explain the steps and paperwork.',"Net metering is available to qualified customers of distribution utilities. You install a bi-directional meter, your utility credits exported energy, and your bill is reduced accordingly.\n\nSun Son Solar prepares the application, coordinates inspection and follows up with the utility so you do not have to."],
 ['5 maintenance habits that keep panels producing','panel-maintenance-habits','blog','Small habits add up. Keep your array clean, shade-free and monitored for years of steady output.',"1. Clean twice a year or after heavy dust.\n2. Trim nearby trees before they shade the array.\n3. Check your monitoring page weekly.\n4. Inspect cables and connectors annually.\n5. Book a professional check-up every 12 months."],
 ['Sun Son Solar opens its Pasig City office','pasig-office-opening','news','Our new Pasig City office brings design, dispatch and support under one roof.',"We are excited to open our Pasig City office, home to our design, dispatch and customer service teams. Drop by for a free consultation or schedule a site visit."],
];
foreach ($posts as $i => $p) q('INSERT INTO posts (title,slug,category,excerpt,body,published_at) VALUES (?,?,?,?,?,?)', [$p[0], $p[1], $p[2], $p[3], $p[4], date('Y-m-d H:i:s', strtotime('-' . ($i * 9 + 2) . ' days'))]);

// Testimonials
$tm = [
 ['Maria Dela Cruz','Kapitolyo, Pasig','Our monthly bill dropped from ₱9,800 to under ₱2,000. The team explained everything and finished in two days.',5],
 ['Engr. Jun Santos','Mandaluyong','Their design was spot on and the permitting was handled for us. We now run our small factory mostly on solar.',5],
 ['Aileen Ramos','Cainta, Rizal','The battery kept our fridge and fans running during the last brownout. Worth every peso.',5],
 ['Dr. Paolo Lim','Quezon City','Responsive support and clear monitoring. I can see my savings every day.',4],
];
foreach ($tm as $t) q('INSERT INTO testimonials (customer_name,location,quote,rating) VALUES (?,?,?,?)', $t);

// Demo operational data
$cust = $ids['customer']; $tech = $ids['technician'];
$svc = fn($slug) => (int)q('SELECT id FROM services WHERE slug=?', [$slug])->fetchColumn();
$b = function ($sid, $when, $status, $techId) use ($cust) {
    q('INSERT INTO bookings (customer_id,service_id,scheduled_at,address,status,technician_id) VALUES (?,?,?,?,?,?)',
      [$cust, $sid, $when, '12 Kapitolyo St., Pasig City', $status, $techId]);
    return (int)db()->lastInsertId();
};
$b1 = $b($svc('consultation'), date('Y-m-d 09:00:00', strtotime('-40 days')), 'completed', $tech);
$b2 = $b($svc('installation'), date('Y-m-d 08:00:00', strtotime('-20 days')), 'completed', $tech);
$b($svc('maintenance'), date('Y-m-d 10:00:00', strtotime('+6 days')), 'approved', $tech);
$b($svc('maintenance'), date('Y-m-d 13:00:00', strtotime('+14 days')), 'pending', null);

q("INSERT INTO invoices (invoice_no,customer_id,booking_id,description,amount,status,method,due_date,paid_at) VALUES
  ('INV-0001',?,?,'Installation – 5.5 kW hybrid system',285000,'paid','Bank transfer',?,?),
  ('INV-0002',?,NULL,'Maintenance visit – semi-annual',2500,'unpaid',NULL,?,NULL)",
  [$cust, $b2, date('Y-m-d', strtotime('-15 days')), date('Y-m-d H:i:s', strtotime('-18 days')), $cust, date('Y-m-d', strtotime('+10 days'))]);

for ($i = 29; $i >= 0; $i--) {
    q('INSERT INTO system_readings (customer_id,reading_date,kwh_generated,kwh_consumed,battery_pct) VALUES (?,?,?,?,?)',
      [$cust, date('Y-m-d', strtotime("-$i days")), round(18 + mt_rand(-40, 40) / 10, 2), round(14 + mt_rand(-30, 30) / 10, 2), mt_rand(55, 98)]);
}

q("INSERT INTO notifications (user_id,audience,type,title,body) VALUES
  (?,'user','reminder','Maintenance visit coming up','Your maintenance visit is approved. Our technician will arrive at the scheduled time.'),
  (NULL,'customers','promotion','Free panel cleaning','Book any maintenance visit this month and get a free panel cleaning.'),
  (NULL,'all','system','Welcome to the new Sun Son Solar portal','You can now schedule services, track payments and monitor your system in one place.')", [$cust]);

q("INSERT INTO announcements (author_id,title,body,is_urgent) VALUES
  (?,'Safety briefing every Monday','All field staff: 7:45 AM toolbox talk at the office before dispatch.',0),
  (?,'URGENT: Inverter recall check','Verify serial numbers of StringPro 3kW units before installing. Report mismatches immediately.',1)",
  [$ids['admin'], $ids['hr']]);

q("INSERT INTO tickets (customer_id,subject,status) VALUES (?,?,?)", [$cust, 'Question about my net-metering application', 'answered']);
$tid = (int)db()->lastInsertId();
q("INSERT INTO ticket_messages (ticket_id,sender_id,message) VALUES (?,?,?),(?,?,?)",
  [$tid, $cust, 'Hi, when will my net-metering application be approved?', $tid, $ids['admin'], 'Hello Maria! Your application is with the utility. We expect a response within 2–3 weeks and will notify you.']);

q('INSERT INTO feedback (booking_id,customer_id,rating,comment) VALUES (?,?,?,?)', [$b1, $cust, 5, 'Very thorough and friendly.']);

audit('setup', 'Initial demo data and accounts created');
?>
<h1>Setup complete ✔</h1>
<p>Starter accounts (change these passwords right away):</p>
<table border="1" cellpadding="8" style="border-collapse:collapse">
<tr><th>Role</th><th>Username</th><th>Password</th></tr>
<?php foreach ($accounts as $a): ?>
<tr><td><?= e($a[10]) ?></td><td><?= e($a[8]) ?></td><td><?= e($a[9]) ?></td></tr>
<?php endforeach; ?>
</table>
<p><strong>Now delete <code>setup.php</code> from the server.</strong> Then visit <a href="index.php">the home page</a>.</p>
</body>
