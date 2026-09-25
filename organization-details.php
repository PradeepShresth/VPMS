<?php
$active = 'organizations';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['decision']) && $_SESSION['role_id'] == 6) {
    if ($_POST['decision'] == 'approve') {
        $status = 'verified';
    } else {
        $status = 'rejected';
    }

    $update = $pdo->prepare('UPDATE organization SET status = ? WHERE organization_id = ?');
    $update->execute(array($status, $id));

    // the people who registered it were approved along with it, so their
    // accounts do not sit in the queue a second time
    if ($status == 'verified') {
        $people = $pdo->prepare('UPDATE `user` SET status = ? WHERE organization_id = ? AND status = ?');
        $people->execute(array('active', $id, 'pending'));
    } else {
        $people = $pdo->prepare('UPDATE `user` SET status = ? WHERE organization_id = ? AND status = ?');
        $people->execute(array('suspended', $id, 'pending'));
    }

    header('Location: organizations.php?done=1');
    exit;
}

$find = $pdo->prepare('SELECT * FROM organization WHERE organization_id = ?');
$find->execute(array($id));
$organization = $find->fetch();

if ($organization == false) {
    header('Location: organizations.php');
    exit;
}

$page_title = $organization['name'] . ' | VPMS';

$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE organization_id = ?');
$count->execute(array($id));
$members = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM opportunity WHERE organization_id = ?');
$count->execute(array($id));
$projects = $count->fetchColumn();

$where = $organization['city'];

if ($organization['state'] != '') {
    if ($where != '') {
        $where = $where . ', ' . $organization['state'];
    } else {
        $where = $organization['state'];
    }
}

$can_edit = ($_SESSION['role_id'] == 6
          || ($_SESSION['role_id'] == 2 && $_SESSION['organization_id'] == $id));

// the organization runs its own membership; the administrator can step in
$runs_membership = ($_SESSION['role_id'] == 6
                 || ($_SESSION['role_id'] == 2 && $_SESSION['organization_id'] == $id));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $runs_membership) {

    if (isset($_POST['approve_request'])) {
        $find = $pdo->prepare('SELECT * FROM membership_request WHERE request_id = ? AND organization_id = ?');
        $find->execute(array($_POST['approve_request'], $id));
        $request = $find->fetch();

        if ($request != false) {
            $join = $pdo->prepare('UPDATE `user` SET organization_id = ?, designation = ? WHERE user_id = ?');
            $join->execute(array($id, $request['designation'], $request['user_id']));

            $done = $pdo->prepare('UPDATE membership_request SET status = ? WHERE request_id = ?');
            $done->execute(array('approved', $request['request_id']));
        }
    }

    if (isset($_POST['decline_request'])) {
        $done = $pdo->prepare(
            'UPDATE membership_request SET status = ? WHERE request_id = ? AND organization_id = ?'
        );
        $done->execute(array('declined', $_POST['decline_request'], $id));
    }

    if (isset($_POST['set_designation'])) {
        $save = $pdo->prepare('UPDATE `user` SET designation = ? WHERE user_id = ? AND organization_id = ?');
        $save->execute(array(trim($_POST['designation']), $_POST['set_designation'], $id));
    }

    // a layoff: they keep their account, they just are not one of yours any more
    if (isset($_POST['remove_member']) && $_POST['remove_member'] != $_SESSION['user_id']) {
        $drop = $pdo->prepare(
            'UPDATE `user` SET organization_id = NULL, designation = NULL WHERE user_id = ? AND organization_id = ?'
        );
        $drop->execute(array($_POST['remove_member'], $id));
    }

    // so an organization is never left with nobody who can run it
    if (isset($_POST['promote_member'])) {
        $up = $pdo->prepare('UPDATE `user` SET role_id = 2 WHERE user_id = ? AND organization_id = ?');
        $up->execute(array($_POST['promote_member'], $id));
    }

    header('Location: organization-details.php?id=' . $id . '&members=1');
    exit;
}

$find = $pdo->prepare(
    'SELECT m.request_id, m.designation, m.created_at, u.user_id, u.full_name, u.email, r.name AS role_name
     FROM membership_request m
     JOIN `user` u ON u.user_id = m.user_id
     JOIN role r ON r.role_id = u.role_id
     WHERE m.organization_id = ? AND m.status = ?
     ORDER BY m.created_at'
);
$find->execute(array($id, 'pending'));
$requests = $find->fetchAll();

$find = $pdo->prepare(
    'SELECT u.user_id, u.full_name, u.email, u.designation, u.role_id, r.name AS role_name,
            (SELECT COALESCE(SUM(ev.hours_logged), 0) FROM event_volunteer ev
              WHERE ev.user_id = u.user_id AND ev.attended = 1) AS hours
     FROM `user` u
     JOIN role r ON r.role_id = u.role_id
     WHERE u.organization_id = ?
     ORDER BY u.role_id, u.full_name'
);
$find->execute(array($id));
$member_list = $find->fetchAll();

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="organizations.php"><i class="bi bi-arrow-left"></i> Back</a>

  <?php if (isset($_GET['saved'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Organization details saved.</div>
  <?php } ?>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1"><?php echo htmlspecialchars($organization['name']); ?></h1>
    <?php if ($organization['status'] == 'verified') { ?>
      <span class="badge-v badge-navy mt-2">Verified</span>
    <?php } elseif ($organization['status'] == 'pending') { ?>
      <span class="badge-v badge-pending mt-2">Pending</span>
    <?php } else { ?>
      <span class="badge-v badge-grey mt-2">Rejected</span>
    <?php } ?>
  </div>

  <p class="mb-4" style="color:#16663e;font-size:14px">
    <?php echo htmlspecialchars($organization['type']); ?>
    <?php if ($where != '') { ?> · <?php echo htmlspecialchars($where); ?><?php } ?>
  </p>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Members</span>
        <span class="t-value serif"><?php echo $members; ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Opportunities Posted</span>
        <span class="t-value serif"><?php echo $projects; ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Member Since</span>
        <span class="t-value serif"><?php echo date('F Y', strtotime($organization['created_at'])); ?></span>
      </div>
    </div>
  </div>

  <?php if ($runs_membership && count($requests) > 0) { ?>
    <p class="section-label">Asking to join</p>

    <div class="card-v card-v-pad mb-4">
      <?php foreach ($requests as $row) { ?>
        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
          <span class="avatar-circle"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
          <span class="flex-grow-1">
            <span class="d-block fw-bold"><?php echo htmlspecialchars($row['full_name']); ?></span>
            <span class="d-block" style="font-size:12.5px;color:#6d7880">
              <?php echo htmlspecialchars($row['role_name']); ?> · <?php echo htmlspecialchars($row['email']); ?>
              <?php if ($row['designation'] != '') { ?> · says they are <?php echo htmlspecialchars($row['designation']); ?><?php } ?>
            </span>
          </span>
          <span class="d-flex gap-2">
            <form action="organization-details.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="approve_request" value="<?php echo $row['request_id']; ?>">
              <button class="btn-v btn-green btn-sm-v" type="submit">Approve</button>
            </form>
            <form action="organization-details.php?id=<?php echo $id; ?>" method="post">
              <input type="hidden" name="decline_request" value="<?php echo $row['request_id']; ?>">
              <button class="btn-v btn-outline btn-sm-v" type="submit">Decline</button>
            </form>
          </span>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if (count($member_list) > 0) { ?>
    <p class="section-label">People</p>

    <div class="list-card mb-4">
      <div class="table-responsive">
        <table class="table-v">
          <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Hours</th>
            <?php if ($runs_membership) { ?><th></th><?php } ?>
          </tr>

          <?php foreach ($member_list as $row) { ?>
            <tr>
              <td>
                <span class="d-flex align-items-center gap-3">
                  <span class="avatar-circle grey"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
                  <span>
                    <span class="d-block fw-bold"><?php echo htmlspecialchars($row['full_name']); ?></span>
                    <span class="d-block" style="font-size:12.5px;color:#6d7880">
                      <?php echo htmlspecialchars($row['role_name']); ?>
                    </span>
                  </span>
                </span>
              </td>
              <td>
                <?php if ($runs_membership) { ?>
                  <form class="d-flex gap-2" action="organization-details.php?id=<?php echo $id; ?>" method="post">
                    <input type="hidden" name="set_designation" value="<?php echo $row['user_id']; ?>">
                    <input class="input-v" style="width:150px;padding:6px 10px;font-size:13px"
                           type="text" name="designation" placeholder="Position"
                           value="<?php echo htmlspecialchars($row['designation']); ?>">
                    <button class="btn-v btn-soft btn-sm-v" type="submit">Save</button>
                  </form>
                <?php } else { ?>
                  <span class="td-muted"><?php echo htmlspecialchars($row['designation']); ?></span>
                <?php } ?>
              </td>
              <td class="mono" style="font-size:13px"><?php echo $row['hours']; ?> hrs</td>
              <?php if ($runs_membership) { ?>
                <td class="text-end">
                  <span class="d-flex gap-2 justify-content-end">
                    <?php if ($row['role_id'] != 2 && $row['role_id'] != 6) { ?>
                      <form action="organization-details.php?id=<?php echo $id; ?>" method="post">
                        <input type="hidden" name="promote_member" value="<?php echo $row['user_id']; ?>">
                        <button class="btn-v btn-soft btn-sm-v" type="submit">Make coordinator</button>
                      </form>
                    <?php } ?>
                    <?php if ($row['user_id'] != $_SESSION['user_id']) { ?>
                      <form action="organization-details.php?id=<?php echo $id; ?>" method="post"
                            onsubmit="return confirm('Remove this person from the organization?')">
                        <input type="hidden" name="remove_member" value="<?php echo $row['user_id']; ?>">
                        <button class="btn-v btn-outline btn-sm-v" type="submit">Remove</button>
                      </form>
                    <?php } else { ?>
                      <span class="td-muted">That is you</span>
                    <?php } ?>
                  </span>
                </td>
              <?php } ?>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  <?php } ?>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Registration Details</p>

    <div class="doc-row">
      <i class="bi bi-hash" style="color:#6d7880"></i>
      Registration number
      <span class="ms-auto mono" style="font-size:13px;color:#6d7880">
        <?php if ($organization['registration_no'] != '') {
            echo htmlspecialchars($organization['registration_no']);
        } else {
            echo 'not provided';
        } ?>
      </span>
    </div>

    <div class="doc-row">
      <i class="bi bi-geo-alt" style="color:#6d7880"></i>
      Address
      <span class="ms-auto" style="font-size:13px;color:#6d7880">
        <?php if ($organization['address'] != '') {
            echo htmlspecialchars($organization['address']);
        } else {
            echo 'not provided';
        } ?>
      </span>
    </div>

    <div class="doc-row mb-0">
      <i class="bi bi-globe" style="color:#6d7880"></i>
      Website
      <span class="ms-auto" style="font-size:13px;color:#6d7880">
        <?php if ($organization['website'] != '') { ?>
          <a class="link-green" href="<?php echo htmlspecialchars($organization['website']); ?>">
            <?php echo htmlspecialchars($organization['website']); ?>
          </a>
        <?php } else { ?>
          not provided
        <?php } ?>
      </span>
    </div>
  </div>

  <?php if ($organization['description'] != '') { ?>
    <p class="section-label">About</p>
    <p class="mb-4" style="color:#48545e;font-size:14.5px;line-height:1.7">
      <?php echo nl2br(htmlspecialchars($organization['description'])); ?>
    </p>
  <?php } ?>

  <?php if ($organization['status'] == 'pending' && $_SESSION['role_id'] == 6) { ?>
    <div class="row g-3">
      <div class="col-sm-6">
        <form action="organization-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="decision" value="approve">
          <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Approve Organization</button>
        </form>
      </div>
      <div class="col-sm-6">
        <form action="organization-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="decision" value="reject">
          <button class="btn-v btn-red btn-block btn-lg-v" type="submit">Reject</button>
        </form>
      </div>
    </div>

    <a class="btn-v btn-outline btn-block btn-lg-v mt-3" href="organization-edit.php?id=<?php echo $id; ?>">Edit Details</a>
  <?php } elseif ($can_edit) { ?>
    <a class="btn-v btn-soft btn-block btn-lg-v" href="organization-edit.php?id=<?php echo $id; ?>">Edit Details</a>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>
