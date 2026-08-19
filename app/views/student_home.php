<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$student = $student ?? [];
$notice = $notice ?? '';
$access_granted = $access_granted ?? false;

function student_value($student, $key)
{
    return htmlspecialchars($student[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

function protected_value($access_granted, $student, $key)
{
    return $access_granted ? student_value($student, $key) : 'Locked';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ken Jerry Student Portal</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sky: #38bdf8;
            --sky-deep: #0284c7;
            --sky-soft: #e0f2fe;
            --ink: #123047;
            --muted: #5b7b91;
            --card: rgba(255, 255, 255, 0.86);
            --line: rgba(56, 189, 248, 0.28);
            --pink: #fb7185;
        }
        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 10%, rgba(255, 255, 255, 0.95) 0 7%, transparent 8%),
                radial-gradient(circle at 82% 14%, rgba(255, 255, 255, 0.8) 0 5%, transparent 6%),
                linear-gradient(180deg, #bae6fd 0%, #e0f2fe 45%, #f8fbff 100%);
            overflow-x: hidden;
        }
        body::before, body::after {
            content: "";
            position: fixed;
            pointer-events: none;
            z-index: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            box-shadow:
                44px 12px 0 rgba(255, 255, 255, 0.64),
                88px 2px 0 rgba(255, 255, 255, 0.5);
        }
        body::before { width: 96px; height: 34px; top: 95px; left: 6%; }
        body::after { width: 118px; height: 38px; top: 210px; right: 9%; opacity: 0.75; }
        .shell {
            position: relative;
            z-index: 1;
            width: min(1040px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0 42px;
        }
        .nav {
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 14px 18px;
            margin-bottom: 28px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(12px);
            box-shadow: 0 12px 30px rgba(2, 132, 199, 0.12);
        }
        .brand { font-size: 18px; font-weight: 900; color: var(--sky-deep); }
        .links { display: flex; gap: 10px; flex-wrap: wrap; }
        .links a {
            color: var(--sky-deep);
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
            padding: 10px 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: rgba(255,255,255,0.76);
            box-shadow: 0 6px 14px rgba(14, 165, 233, 0.1);
        }
        .links a.active, .links a:hover { color: #fff; background: var(--sky); border-color: var(--sky); }
        .links a.danger { color: #be123c; }
        .links a.danger:hover { color: #fff; background: var(--pink); border-color: var(--pink); }
        .hero {
            display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 24px; align-items: stretch;
        }
        .panel {
            background: var(--card);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(2, 132, 199, 0.16);
        }
        .intro {
            padding: 42px;
            background:
                linear-gradient(135deg, rgba(255,255,255,0.92), rgba(224, 242, 254, 0.88)),
                var(--card);
        }
        .eyebrow { color: var(--pink); font-weight: 900; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; }
        h1 { margin-top: 12px; font-size: clamp(34px, 6vw, 58px); line-height: 1; color: #075985; }
        .summary { margin-top: 18px; max-width: 610px; color: var(--muted); font-size: 17px; line-height: 1.7; }
        .notice {
            margin-top: 22px;
            padding: 14px 16px;
            border-radius: 8px;
            color: #0c4a6e;
            background: #dff6ff;
            border: 1px solid #7dd3fc;
            font-weight: 800;
        }
        .status { padding: 30px; display: grid; align-content: center; gap: 18px; }
        .status strong { display: block; font-size: 34px; color: var(--sky-deep); }
        .status span { color: var(--muted); font-weight: 800; }
        .status p { color: #45677d; line-height: 1.6; }
        .info-grid { margin-top: 24px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
        .info {
            padding: 18px;
            background: rgba(255,255,255,0.82);
        }
        .info small { color: var(--sky-deep); font-weight: 900; text-transform: uppercase; font-size: 11px; letter-spacing: 0.08em; }
        .info p { margin-top: 8px; font-weight: 900; color: #0f3b57; overflow-wrap: anywhere; }
        .info.wide { grid-column: span 3; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .chip {
            padding: 8px 10px;
            border-radius: 8px;
            color: #075985;
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
            font-weight: 900;
            font-size: 13px;
        }
        .hidden-info {
            margin-top: 24px;
            padding: 30px;
            text-align: center;
            background: rgba(255, 255, 255, 0.78);
        }
        .hidden-info h2 { color: var(--pink); font-size: 32px; }
        .flow { margin-top: 24px; padding: 28px; }
        .flow h2 { color: #075985; font-size: 24px; margin-bottom: 8px; }
        .flow-intro { color: var(--muted); line-height: 1.7; margin-bottom: 18px; }
        .flow-chart {
            display: inline-block;
            min-width: min(100%, 560px);
            padding: 22px;
            border: 1px dashed #7dd3fc;
            border-radius: 8px;
            background: #f0f9ff;
            color: #0c4a6e;
            font-family: "Courier New", monospace;
            font-size: 17px;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        @media (max-width: 800px) {
            .hero, .info-grid { grid-template-columns: 1fr; }
            .info.wide { grid-column: span 1; }
            .intro { padding: 28px; }
            .nav { align-items: flex-start; flex-direction: column; }
            .flow { padding: 22px; }
            .flow-chart { font-size: 14px; padding: 16px; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <nav class="nav">
            <div class="brand">Ken Jerry Student Portal</div>
            <div class="links">
                <a class="active" href="<?= site_url('student'); ?>">Home</a>
                <a href="<?= site_url('student/profile'); ?>">Student Profile</a>
                <a href="<?= site_url('student?permission=yes'); ?>">Allow Access</a>
                <a class="danger" href="<?= site_url('student?permission=no'); ?>">Hide Info</a>
            </div>
        </nav>

        <section class="hero">
            <div class="panel intro">
                <div class="eyebrow">Student Information</div>
                <h1><?= $access_granted ? 'Hello, ' . student_value($student, 'name') : 'Locked'; ?></h1>
                <p class="summary">
                    <?= $access_granted ? 'Permission is allowed. Student information is visible.' : 'Locked'; ?>
                </p>
            </div>
            <aside class="panel status">
                <div>
                    <strong><?= $access_granted ? 'Allowed' : 'Locked'; ?></strong>
                    <span>Student information status</span>
                </div>
                <p><?= $access_granted ? 'Permission is YES, so the details are visible.' : 'Locked'; ?></p>
            </aside>
        </section>

        <section class="info-grid">
            <article class="panel info">
                <small>Student ID</small>
                <p><?= protected_value($access_granted, $student, 'student_id'); ?></p>
            </article>
            <article class="panel info">
                <small>Course</small>
                <p><?= protected_value($access_granted, $student, 'course'); ?></p>
            </article>
            <article class="panel info">
                <small>Email</small>
                <p><?= protected_value($access_granted, $student, 'email'); ?></p>
            </article>
            <article class="panel info">
                <small>Year and Section</small>
                <p><?= $access_granted ? student_value($student, 'year') . ' / ' . student_value($student, 'section') : 'Locked'; ?></p>
            </article>
            <article class="panel info">
                <small>Address</small>
                <p><?= protected_value($access_granted, $student, 'address'); ?></p>
            </article>
            <article class="panel info">
                <small>Contact Number</small>
                <p><?= protected_value($access_granted, $student, 'contact'); ?></p>
            </article>
            <article class="panel info wide">
                <small>Profile Description</small>
                <p><?= protected_value($access_granted, $student, 'description'); ?></p>
            </article>
            <article class="panel info">
                <small>Skills</small>
                <div class="chips">
                    <?php if ($access_granted): ?>
                        <?php foreach (($student['skills'] ?? []) as $skill): ?>
                            <span class="chip"><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="chip">Locked</span>
                    <?php endif; ?>
                </div>
            </article>
            <article class="panel info">
                <small>Hobbies</small>
                <div class="chips">
                    <?php if ($access_granted): ?>
                        <?php foreach (($student['hobbies'] ?? []) as $hobby): ?>
                            <span class="chip"><?= htmlspecialchars($hobby, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="chip">Locked</span>
                    <?php endif; ?>
                </div>
            </article>
        </section>
    </main>
</body>
</html>
