<?php

// this one has no html, it just hands the browser a csv file
require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT * FROM report WHERE report_id = ?');
$find->execute(array($id));
$report = $find->fetch();

if ($report == false) {
    header('Location: reports.php');
    exit;
}

$file_name = 'report-' . $report['report_id'] . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $file_name . '"');

$out = fopen('php://output', 'w');

fputcsv($out, array($report['title']));
fputcsv($out, array('Period', $report['period']));
fputcsv($out, array('Generated', date('j M Y H:i', strtotime($report['created_at']))));
fputcsv($out, array());

if ($report['report_type'] == 'Volunteer Activity Report') {
    fputcsv($out, array('Volunteer', 'Email', 'Event', 'Date', 'Attended', 'Hours'));

    $rows = $pdo->query(
        'SELECT u.full_name, u.email, e.title, e.event_date, ev.attended, ev.hours_logged
         FROM event_volunteer ev
         JOIN `user` u ON u.user_id = ev.user_id
         JOIN event e ON e.event_id = ev.event_id
         ORDER BY e.event_date DESC, u.full_name'
    );

    foreach ($rows->fetchAll() as $row) {
        if ($row['attended'] == 1) {
            $attended = 'yes';
        } else {
            $attended = 'no';
        }

        fputcsv($out, array($row['full_name'], $row['email'], $row['title'],
                            $row['event_date'], $attended, $row['hours_logged']));
    }

} elseif ($report['report_type'] == 'Partnership Performance Report'
       || $report['report_type'] == 'Sponsor Contribution Report') {
    fputcsv($out, array('Organisation', 'Partner', 'Type', 'Start', 'End', 'SDG goals', 'Status', 'Reported impact'));

    $rows = $pdo->query(
        'SELECT p.*, asked.name AS asked_by, partner.name AS partner_name
         FROM partnership p
         LEFT JOIN organisation asked ON asked.organisation_id = p.organisation_id
         LEFT JOIN organisation partner ON partner.organisation_id = p.partner_id
         ORDER BY p.created_at DESC'
    );

    foreach ($rows->fetchAll() as $row) {
        fputcsv($out, array($row['asked_by'], $row['partner_name'], $row['type'],
                            $row['start_date'], $row['end_date'], $row['sdg_goals'],
                            $row['status'], $row['reported_impact']));
    }

} elseif ($report['report_type'] == 'SDG Contribution Summary') {
    fputcsv($out, array('SDG goal', 'Opportunities', 'Partnerships'));

    $goals = array();

    $rows = $pdo->query('SELECT sdg_goals FROM opportunity');

    foreach ($rows->fetchAll() as $row) {
        foreach (explode(',', $row['sdg_goals']) as $goal) {
            $goal = trim($goal);
            if ($goal != '') {
                if (!isset($goals[$goal])) {
                    $goals[$goal] = array(0, 0);
                }
                $goals[$goal][0] = $goals[$goal][0] + 1;
            }
        }
    }

    $rows = $pdo->query('SELECT sdg_goals FROM partnership');

    foreach ($rows->fetchAll() as $row) {
        foreach (explode(',', $row['sdg_goals']) as $goal) {
            $goal = trim($goal);
            if ($goal != '') {
                if (!isset($goals[$goal])) {
                    $goals[$goal] = array(0, 0);
                }
                $goals[$goal][1] = $goals[$goal][1] + 1;
            }
        }
    }

    ksort($goals);

    foreach ($goals as $goal => $counts) {
        fputcsv($out, array('SDG ' . $goal, $counts[0], $counts[1]));
    }

} else {
    fputcsv($out, array('Opportunity', 'Organisation', 'Date', 'Category', 'Spots',
                        'Accepted', 'Applications', 'Status'));

    $rows = $pdo->query(
        'SELECT o.*, org.name AS organisation,
                (SELECT COUNT(*) FROM application a WHERE a.opportunity_id = o.opportunity_id) AS applications,
                (SELECT COUNT(*) FROM application a WHERE a.opportunity_id = o.opportunity_id
                  AND a.status = \'accepted\') AS accepted
         FROM opportunity o
         LEFT JOIN organisation org ON org.organisation_id = o.organisation_id
         ORDER BY o.opportunity_date DESC'
    );

    foreach ($rows->fetchAll() as $row) {
        fputcsv($out, array($row['title'], $row['organisation'], $row['opportunity_date'],
                            $row['category'], $row['spots'], $row['accepted'],
                            $row['applications'], $row['status']));
    }
}

fclose($out);
