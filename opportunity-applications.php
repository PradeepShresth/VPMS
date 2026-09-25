<?php
$page_title = 'Applications | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT * FROM opportunity WHERE opportunity_id = ?');
$find->execute(array($id));
$opportunity = $find->fetch();

if ($opportunity == false) {
    header('Location: opportunities.php');
    exit;
}

$mine = ($opportunity['organization_id'] != ''
      && $opportunity['organization_id'] == $_SESSION['organization_id']);

// a coordinator of an organization partnered with the one behind this work
// manages it too, because a partnership is joint work
if (!$mine && $_SESSION['role_id'] == 2 && $opportunity['organization_id'] != ''
 && $_SESSION['organization_id'] != '') {
    $together = $pdo->prepare(
        'SELECT partnership_id FROM partnership
          WHERE status = ?
            AND ((organization_id = ? AND partner_id = ?)
              OR (organization_id = ? AND partner_id = ?))'
    );
    $together->execute(array('active',
        $_SESSION['organization_id'], $opportunity['organization_id'],
        $opportunity['organization_id'], $_SESSION['organization_id']));

    if ($together->fetch() != false) {
        $mine = true;
    }
}

if ($opportunity['created_by'] != $_SESSION['user_id'] && !$mine && $_SESSION['role_id'] != 6) {
    header('Location: opportunity-details.php?id=' . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $decision = $_POST['decision'];

    $who = $pdo->prepare('SELECT user_id FROM application WHERE application_id = ? AND opportunity_id = ?');
    $who->execute(array($_POST['application_id'], $id));
    $volunteer = $who->fetchColumn();

    // somebody who already turned up has hours against their name, and those
    // hours feed the partnership and SDG totals, so the decision stands
    $count = $pdo->prepare(
        'SELECT COUNT(*) FROM event_volunteer ev
           JOIN event e ON e.event_id = ev.event_id
          WHERE e.opportunity_id = ? AND ev.user_id = ? AND ev.attended = 1'
    );
    $count->execute(array($id, $volunteer));
    $turned_up = $count->fetchColumn();

    $allowed = ($decision == 'accepted' || $decision == 'rejected' || $decision == 'pending');

    if ($volunteer != false && $turned_up == 0 && $allowed) {

        $update = $pdo->prepare(
            'UPDATE application SET status = ? WHERE application_id = ? AND opportunity_id = ?'
        );
        $update->execute(array($decision, $_POST['application_id'], $id));

        // the roster has to follow the decision
        $find = $pdo->prepare('SELECT event_id FROM event WHERE opportunity_id = ? ORDER BY event_id');
        $find->execute(array($id));
        $event = $find->fetch();

        if ($event != false) {
            $check = $pdo->prepare(
                'SELECT event_volunteer_id FROM event_volunteer WHERE event_id = ? AND user_id = ?'
            );
            $check->execute(array($event['event_id'], $volunteer));
            $already = $check->fetch();

            if ($decision == 'accepted' && $already == false) {
                $add = $pdo->prepare(
                    'INSERT INTO event_volunteer (event_id, user_id, status) VALUES (?, ?, ?)'
                );
                $add->execute(array($event['event_id'], $volunteer, 'confirmed'));

            } elseif ($decision == 'accepted') {
                // they were carried over on the waitlist, so confirm them instead
                $confirm = $pdo->prepare(
                    'UPDATE event_volunteer SET status = ? WHERE event_volunteer_id = ?'
                );
                $confirm->execute(array('confirmed', $already['event_volunteer_id']));

            } elseif ($already != false) {
                // no longer accepted, so no longer on the team
                $remove = $pdo->prepare('DELETE FROM event_volunteer WHERE event_volunteer_id = ?');
                $remove->execute(array($already['event_volunteer_id']));
            }
        }
    }

    header('Location: opportunity-applications.php?id=' . $id . '&done=1');
    exit;
}

// all, pending, accepted or rejected
$show = isset($_GET['show']) ? $_GET['show'] : 'all';

$sql = 'SELECT a.*, u.full_name, u.email, u.phone,
               (SELECT COALESCE(SUM(ev.hours_logged), 0) FROM event_volunteer ev
                 WHERE ev.user_id = a.user_id AND ev.attended = 1) AS hours,
               (SELECT COALESCE(SUM(ev.hours_logged), 0) FROM event_volunteer ev
                  JOIN event e ON e.event_id = ev.event_id
                 WHERE e.opportunity_id = a.opportunity_id
                   AND ev.user_id = a.user_id AND ev.attended = 1) AS hours_here
        FROM application a
        JOIN `user` u ON u.user_id = a.user_id
        WHERE a.opportunity_id = ?';
$values = array($id);

if ($show != 'all') {
    $sql = $sql . ' AND a.status = ?';
    $values[] = $show;
}

$sql = $sql . ' ORDER BY a.created_at DESC';

$list = $pdo->prepare($sql);
$list->execute($values);
$applications = $list->fetchAll();

$count = $pdo->prepare('SELECT COUNT(*) FROM application WHERE opportunity_id = ?');
$count->execute(array($id));
$total = $count->fetchColumn();

include 'includes/app-header.php';
?>

<a class="back-link" href="opportunity-details.php?id=<?php echo $id; ?>">
  <i class="bi bi-arrow-left"></i> Back to Opportunity
</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Application updated. The volunteer has been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Applications</h1>
    <p class="page-sub">
      <?php echo htmlspecialchars($opportunity['title']); ?> · <?php echo $total; ?> applications
    </p>
  </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="pill <?php if ($show == 'all') echo 'active'; ?>"
     href="opportunity-applications.php?id=<?php echo $id; ?>&show=all">All</a>
  <a class="pill <?php if ($show == 'pending') echo 'active'; ?>"
     href="opportunity-applications.php?id=<?php echo $id; ?>&show=pending">Pending</a>
  <a class="pill <?php if ($show == 'accepted') echo 'active'; ?>"
     href="opportunity-applications.php?id=<?php echo $id; ?>&show=accepted">Accepted</a>
  <a class="pill <?php if ($show == 'rejected') echo 'active'; ?>"
     href="opportunity-applications.php?id=<?php echo $id; ?>&show=rejected">Rejected</a>
</div>

<?php if (count($applications) == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">Nothing to show</p>
    <p style="font-size:14px;color:#6d7880">No applications match this filter yet.</p>
  </div>

<?php } else { ?>

<div class="list-card">
  <div class="table-responsive">
    <table class="table-v">
      <tr>
        <th>Volunteer</th>
        <th>Applied</th>
        <th>Hours logged</th>
        <th>Skills</th>
        <th>Status</th>
        <th></th>
      </tr>

      <?php foreach ($applications as $row) { ?>
        <tr>
          <td>
            <span class="d-flex align-items-center gap-3">
              <span class="avatar-circle"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
              <span>
                <span class="d-block fw-bold"><?php echo htmlspecialchars($row['full_name']); ?></span>
                <span class="d-block" style="font-size:12.5px;color:#6d7880"><?php echo htmlspecialchars($row['email']); ?></span>

                <details style="margin-top:6px">
                  <summary class="link-green" style="font-size:12.5px;cursor:pointer">View application</summary>

                  <span class="d-block mt-2" style="font-size:13px;color:#48545e;line-height:1.6;max-width:420px">
                    <strong>Why they want to join</strong><br>
                    <?php
                    if ($row['why'] != '') {
                        echo nl2br(htmlspecialchars($row['why']));
                    } else {
                        echo 'Nothing written.';
                    }
                    ?>
                  </span>

                  <span class="d-block mt-2" style="font-size:13px;color:#48545e">
                    <strong>Availability</strong><br>
                    <?php echo htmlspecialchars($row['availability']); ?>
                  </span>

                  <span class="d-block mt-2" style="font-size:13px;color:#48545e">
                    <strong>Emergency contact</strong><br>
                    <?php echo htmlspecialchars($row['emergency_contact']); ?>
                  </span>

                  <?php if ($row['phone'] != '') { ?>
                    <span class="d-block mt-2" style="font-size:13px;color:#48545e">
                      <strong>Phone</strong><br>
                      <?php echo htmlspecialchars($row['phone']); ?>
                    </span>
                  <?php } ?>
                </details>
              </span>
            </span>
          </td>
          <td class="mono td-muted" style="font-size:13px"><?php echo date('d M', strtotime($row['created_at'])); ?></td>
          <td class="mono" style="font-size:13px"><?php echo $row['hours']; ?> hrs</td>
          <td>
            <?php
            $skills = explode(',', $row['skills']);
            foreach ($skills as $skill) {
                $skill = trim($skill);
                if ($skill != '') { ?>
                  <span class="chip" style="font-size:12px"><?php echo htmlspecialchars($skill); ?></span>
            <?php }
            } ?>
          </td>
          <td>
            <?php if ($row['status'] == 'pending') { ?>
              <span class="badge-v badge-pending">Pending</span>
            <?php } elseif ($row['status'] == 'accepted') { ?>
              <span class="badge-v badge-navy">Accepted</span>
            <?php } else { ?>
              <span class="badge-v badge-grey">Rejected</span>
            <?php } ?>
          </td>
          <td class="text-end">
            <?php if ($row['hours_here'] > 0) { ?>

              <!-- they turned up, so the hours are on the books and the decision stays -->
              <span class="td-muted" style="font-size:13px">
                Attended · <?php echo $row['hours_here']; ?> hrs
              </span>

            <?php } elseif ($row['status'] == 'pending') { ?>
              <span class="d-flex gap-2 justify-content-end">
                <form action="opportunity-applications.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                  <input type="hidden" name="decision" value="accepted">
                  <button class="btn-v btn-green btn-sm-v" type="submit">Accept</button>
                </form>
                <form action="opportunity-applications.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                  <input type="hidden" name="decision" value="rejected">
                  <button class="btn-v btn-outline btn-sm-v" type="submit">Reject</button>
                </form>
              </span>

            <?php } elseif ($row['status'] == 'accepted') { ?>
              <span class="d-flex gap-2 justify-content-end">
                <form action="opportunity-applications.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                  <input type="hidden" name="decision" value="pending">
                  <button class="btn-v btn-soft btn-sm-v" type="submit">Back to Review</button>
                </form>
                <form action="opportunity-applications.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                  <input type="hidden" name="decision" value="rejected">
                  <button class="btn-v btn-outline btn-sm-v" type="submit">Reject</button>
                </form>
              </span>

            <?php } else { ?>
              <span class="d-flex gap-2 justify-content-end">
                <form action="opportunity-applications.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                  <input type="hidden" name="decision" value="pending">
                  <button class="btn-v btn-soft btn-sm-v" type="submit">Back to Review</button>
                </form>
                <form action="opportunity-applications.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="application_id" value="<?php echo $row['application_id']; ?>">
                  <input type="hidden" name="decision" value="accepted">
                  <button class="btn-v btn-green btn-sm-v" type="submit">Accept</button>
                </form>
              </span>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>

    </table>
  </div>
</div>

<?php } ?>

<?php include 'includes/app-footer.php'; ?>
