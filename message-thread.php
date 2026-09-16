<?php
$page_title = 'Message | VPMS';
$active = 'messages';
include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <?php if (isset($_GET['sent'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Reply sent.</div>
  <?php } ?>

  <div class="card-v">

    <div class="card-head">
      <span class="avatar-circle">R</span>
      <span>
        <span class="d-block" style="font-size:14.5px;font-weight:600">Ram Dhakal</span>
        <span class="d-block" style="font-size:12.5px;color:#6d7880">Corporate CSR Manager · TechCorp China</span>
      </span>
    </div>

    <div style="padding:16px 22px;border-bottom:1px solid #f0ede6">
      <p class="row-title">Re: Volunteer application for Beach Clean-Up — approved!</p>
    </div>

    <div class="card-v-pad">

      <div class="bubble-row">
        <div>
          <div class="bubble">
            Hi Pradeep — we reviewed the roster for the Bondi clean-up and approved all six of our
            employee volunteers. Could you confirm the reporting time?
          </div>
          <div class="bubble-time">Yesterday 16:40</div>
        </div>
      </div>

      <div class="bubble-row mine">
        <div>
          <div class="bubble">
            Great news. Team leads report at 06:45 for the safety walkthrough, everyone else at 07:00
            at the north gate.
          </div>
          <div class="bubble-time">Yesterday 17:02</div>
        </div>
      </div>

      <div class="bubble-row">
        <div>
          <div class="bubble">
            Noted. We will also bring 20 extra pairs of gloves and a first-aid kit from our CSR stock.
          </div>
          <div class="bubble-time">Yesterday 17:20</div>
        </div>
      </div>

      <div class="bubble-row mine">
        <div>
          <div class="bubble">
            Perfect — I have logged those against the partnership record so they count toward the
            in-kind contribution total.
          </div>
          <div class="bubble-time">Today 09:58</div>
        </div>
      </div>

      <div class="bubble-row">
        <div>
          <div class="bubble">Excellent. Application approved on our side too. See you Saturday!</div>
          <div class="bubble-time">Today 10:24</div>
        </div>
      </div>

    </div>

    <form class="composer" action="message-thread.php" method="get">
      <input type="hidden" name="sent" value="1">
      <input class="input-v input-soft" type="text" name="reply" placeholder="Write a reply...">
      <button class="btn-v btn-green" type="submit"><i class="bi bi-send"></i></button>
    </form>

  </div>

</div>

<?php include 'includes/app-footer.php'; ?>
