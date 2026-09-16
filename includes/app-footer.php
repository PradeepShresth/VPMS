</div><!-- end content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// open and close the sidebar on phones
var menuBtn = document.getElementById('menuBtn');
var sidebar = document.getElementById('sidebar');
var backdrop = document.getElementById('backdrop');

menuBtn.onclick = function () {
  sidebar.classList.toggle('open');
  backdrop.classList.toggle('show');
};

backdrop.onclick = function () {
  sidebar.classList.remove('open');
  backdrop.classList.remove('show');
};
</script>
</body>
</html>
