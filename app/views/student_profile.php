<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$student = $student ?? [];

function profile_value($student, $key)
{
    return htmlspecialchars($student[$key] ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Ken Jerry</title>
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
        .profile {
            display: grid; grid-template-columns: 320px 1fr; gap: 22px;
        }
        .panel {
            background: var(--card);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(2, 132, 199, 0.16);
        }
        .identity {
            padding: 28px;
            background:
                linear-gradient(135deg, rgba(255,255,255,0.94), rgba(224, 242, 254, 0.88)),
                var(--card);
        }
        .avatar {
            width: 96px; height: 96px; display: grid; place-items: center;
            color: #fff;
            background: linear-gradient(135deg, var(--sky), var(--sky-deep));
            border-radius: 8px;
            font-size: 34px;
            font-weight: 900;
            margin-bottom: 22px;
            box-shadow: 0 14px 26px rgba(2, 132, 199, 0.24);
        }
        h1 { color: #075985; font-size: 34px; line-height: 1.05; }
        .muted { margin-top: 10px; color: var(--muted); line-height: 1.6; }
        .details { padding: 30px; }
        .details h2 { font-size: 24px; margin-bottom: 18px; color: #075985; }
        .rows { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .row {
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: rgba(255,255,255,0.82);
        }
        .row small { color: var(--sky-deep); font-weight: 900; text-transform: uppercase; font-size: 11px; letter-spacing: 0.08em; }
        .row p { margin-top: 7px; font-weight: 900; color: #0f3b57; overflow-wrap: anywhere; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .chip {
            padding: 8px 10px;
            border-radius: 8px;
            color: #075985;
            background: #e0f2fe;
            border: 1px solid #7dd3fc;
            font-weight: 900;
            font-size: 13px;
        }
        @media (max-width: 820px) {
            .profile, .rows { grid-template-columns: 1fr; }
            .nav { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <nav class="nav">
            <div class="brand">Ken Jerry Student Portal</div>
            <div class="links">
                <a href="<?= site_url('student'); ?>">Home</a>
                <a class="active" href="<?= site_url('student/profile'); ?>">Student Profile</a>
                <a href="<?= site_url('student?permission=yes'); ?>">Allow Access</a>
                <a class="danger" href="<?= site_url('student?permission=no'); ?>">Hide Info</a>
            </div>
        </nav>

        <section class="profile">
            <aside class="panel identity">
                <div class="avatar">KJ</div>
                <h1><?= profile_value($student, 'name'); ?></h1>
                <p class="muted">
                    A <?= profile_value($student, 'year'); ?> <?= profile_value($student, 'course'); ?>
                    student from section <?= profile_value($student, 'section'); ?>.
                </p>
            </aside>

            <section class="panel details">
                <h2>Student Profile</h2>
                <div class="rows">
                    <div class="row">
                        <small>Student ID</small>
                        <p><?= profile_value($student, 'student_id'); ?></p>
                    </div>
                    <div class="row">
                        <small>Email</small>
                        <p><?= profile_value($student, 'email'); ?></p>
                    </div>
                    <div class="row">
                        <small>Address</small>
                        <p><?= profile_value($student, 'address'); ?></p>
                    </div>
                    <div class="row">
                        <small>Contact Number</small>
                        <p><?= profile_value($student, 'contact'); ?></p>
                    </div>
                    <div class="row">
                        <small>Course</small>
                        <p><?= profile_value($student, 'course'); ?></p>
                    </div>
                    <div class="row">
                        <small>Profile Description</small>
                        <p><?= profile_value($student, 'description'); ?></p>
                    </div>
                    <div class="row">
                        <small>Skills</small>
                        <div class="chips">
                            <?php foreach (($student['skills'] ?? []) as $skill): ?>
                                <span class="chip"><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="row">
                        <small>Hobbies</small>
                        <div class="chips">
                            <?php foreach (($student['hobbies'] ?? []) as $hobby): ?>
                                <span class="chip"><?= htmlspecialchars($hobby, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>
</body>
</html>
