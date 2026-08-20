<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VPMS — Volunteer Partnership Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <meta name="theme-color" content="#0d6efd">
</head>
<body>
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="assets/img/logo.svg" alt="VPMS" height="36">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#how">How It Works</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
          <li class="nav-item"><a class="btn btn-primary ms-3" href="#">Get Started</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="modern-hero d-flex align-items-center">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-white">
          <h1 class="display-4 fw-bold reveal">Partner. Volunteer. Impact.</h1>
          <p class="lead text-white-75 reveal reveal-delay">A single platform to manage volunteer opportunities, partnerships and impact reporting — built for SDG 17.</p>
          <div class="d-flex gap-3 mt-4">
            <a class="btn btn-light btn-lg" href="#features">Explore VPMS</a>
            <a class="btn btn-outline-light btn-lg" href="#contact">Get In Touch</a>
          </div>
          
        </div>
        <div class="col-lg-6 d-none d-lg-block text-end">
          <div class="hero-art"></div>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section id="features" class="section-features">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-lg-8 text-center">
            <h2 class="fw-bold display-6 mb-3">What VPMS Does</h2>
            <p class="lead text-muted fs-5">Coordinate organisations, volunteers and sponsors with less manual effort.</p>
          </div>
        </div>
        <div class="row g-5 align-items-stretch">
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm p-4 reveal reveal-delay">
              <div class="mb-3 icon-wrap bg-primary text-white"><i class="fa-solid fa-users fa-lg"></i></div>
              <h5>User & Role Management</h5>
              <p class="mb-0 text-muted">Secure roles, onboarding and profile management for all stakeholders.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm p-4 reveal reveal-delay">
              <div class="mb-3 icon-wrap bg-success text-white"><i class="fa-solid fa-calendar-check fa-lg"></i></div>
              <h5>Opportunities & Events</h5>
              <p class="mb-0 text-muted">Post opportunities, manage applications and certify attendance.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm p-4 reveal reveal-delay reveal-slow">
              <div class="mb-3 icon-wrap bg-info text-white"><i class="fa-solid fa-handshake-angle fa-lg"></i></div>
              <h5>Partnerships & Reporting</h5>
              <p class="mb-0 text-muted">Track partnerships and deliver impact reports to sponsors and stakeholders.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="how" class="how-section bg-light py-6">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="fw-bold">How it works</h2>
          <p class="text-muted">Simple workflow to connect organisations, volunteers and sponsors.</p>
        </div>

        <div class="row g-4 align-items-stretch">
          <div class="col-md-6 col-lg-4">
            <div class="how-step card p-4 h-100 reveal">
              <div class="d-flex align-items-start gap-3">
                <div class="step-badge bg-primary text-white">1</div>
                <div>
                  <h5 class="mb-1">Create Organisations & Projects</h5>
                  <p class="text-muted mb-0">NGOs and companies register, verify organisations and post volunteer opportunities.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4">
            <div class="how-step card p-4 h-100 reveal reveal-delay">
              <div class="d-flex align-items-start gap-3">
                <div class="step-badge bg-success text-white">2</div>
                <div>
                  <h5 class="mb-1">Apply & Manage Attendance</h5>
                  <p class="text-muted mb-0">Volunteers apply, get approved and attendance is recorded for certification.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-4">
            <div class="how-step card p-4 h-100 reveal reveal-slow">
              <div class="d-flex align-items-start gap-3">
                <div class="step-badge bg-info text-white">3</div>
                <div>
                  <h5 class="mb-1">Report & Scale Impact</h5>
                  <p class="text-muted mb-0">Generate reports and dashboards for sponsors and partners to measure impact.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact" class="py-6 bg-white">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="card shadow-sm border-0 p-4 contact-card">
              <div class="row g-4 align-items-center">
                <div class="col-md-5">
                  <h4 class="fw-bold">Get in touch</h4>
                  <p class="text-muted">Questions, feedback or partnership enquiries? Send us a message and we'll respond within 2 business days.</p>

                  <ul class="list-unstyled mt-3 mb-0">
                    <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-muted"></i> contact@vpms.example</li>
                    <li class="mb-2"><i class="fa-solid fa-phone me-2 text-muted"></i> +1 (555) 123-4567</li>
                    <li><i class="fa-solid fa-location-dot me-2 text-muted"></i> 123 Community Ave, City</li>
                  </ul>
                </div>

                <div class="col-md-7">
                  <form class="contact-form" action="#" method="post">
                    <div class="row g-2">
                      <div class="col-md-6">
                        <input class="form-control" type="text" name="name" placeholder="Your name" required>
                      </div>
                      <div class="col-md-6">
                        <input class="form-control" type="email" name="email" placeholder="Email address" required>
                      </div>
                      <div class="col-12">
                        <textarea class="form-control" name="message" rows="5" placeholder="How can we help?" required></textarea>
                      </div>
                      <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Send message</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      const nav=document.querySelector('.navbar');
      function onScroll(){
        if(window.scrollY>30) nav.classList.add('navbar-solid'); else nav.classList.remove('navbar-solid');
      }
      document.addEventListener('scroll', onScroll, {passive:true});
      onScroll();
    })();
  </script>
  <script>
    // Simple reveal on scroll using IntersectionObserver
    (function(){
      const obs = new IntersectionObserver((entries)=>{
        entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('in'); });
      },{threshold:0.12});
      document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
    })();
  </script>
</body>
</html>
