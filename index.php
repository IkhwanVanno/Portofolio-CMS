<?php
require_once 'includes/config.php';

$db = getDB();

// Fetch all data
$site = $db->query("SELECT * FROM site_setup WHERE id = 1")->fetch();
$about = $db->query("SELECT * FROM about_me WHERE id = 1")->fetch();
$galeri = $db->query("SELECT * FROM galery ORDER BY id DESC")->fetchAll();
$pengalaman = $db->query("SELECT * FROM pengalaman ORDER BY created_at DESC")->fetchAll();
$perkerjaan = $db->query("SELECT * FROM perkerjaan ORDER BY created_at DESC")->fetchAll();
$sertifikat = $db->query("SELECT * FROM sertifikat ORDER BY created_at DESC")->fetchAll();

// Group into pairs for slider
function groupItems($items, $perSlide = 2)
{
  return array_chunk($items, $perSlide);
}
$pengalamanGrouped = groupItems($pengalaman);
$perkerjaanGrouped = groupItems($perkerjaan);
$sertifikatGrouped = groupItems($sertifikat);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($site['title'] ?? 'Portfolio') ?></title>\
  <link rel="icon" href="./favicon.ico" type="image/x-icon" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap"
    rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --cream: #F5F0E8;
      --cream-dark: #EDE5D0;
      --blue: #1B2B4B;
      --blue-mid: #2A3F6B;
      --blue-light: #3D5A8A;
      --gold: #C9A96E;
      --gold-light: #E8C99A;
      --text-dark: #1a1a2e;
      --text-light: #f8f5f0;
      --font-display: 'Cormorant Garamond', Georgia, serif;
      --font-body: 'DM Sans', sans-serif;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: var(--font-body);
      background: var(--cream);
      color: var(--text-dark);
      overflow-x: hidden;
    }
    img {
    max-width: 100%;
    display: block;
    }

    section,
    div,
    main,
    header,
    footer {
      max-width: 100%;
    }

    /* ── HEADER ── */
    .site-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: rgba(27, 43, 75, 0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(201, 169, 110, 0.2);
    }

    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .site-title {
      font-family: var(--font-display);
      font-size: 1.5rem;
      font-weight: 300;
      color: var(--gold-light);
      letter-spacing: 0.05em;
    }

    .site-nav ul {
      list-style: none;
      display: flex;
      gap: 2rem;
    }

    .site-nav a {
      color: rgba(248, 245, 240, 0.8);
      text-decoration: none;
      font-size: 0.85rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      font-weight: 400;
      transition: color 0.3s;
    }

    .site-nav a:hover {
      color: var(--gold-light);
    }

    .btn-menu-toggle {
      display: none;
      background: none;
      border: none;
      color: var(--gold-light);
      font-size: 1.5rem;
      cursor: pointer;
    }

    .mobile-menu {
      background: var(--blue);
      padding: 1rem 2rem;
      border-top: 1px solid rgba(201, 169, 110, 0.2);
    }

    .mobile-menu ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .mobile-menu a {
      color: var(--text-light);
      text-decoration: none;
      font-size: 0.9rem;
    }

    .hidden {
      display: none;
    }

    /* ── SECTIONS ── */
    .section {
      min-height: 100vh;
      padding: 100px 2rem 80px;
    }

    .section--biru {
      background: var(--blue);
      color: var(--text-light);
    }

    .section--creamee {
      background: var(--cream);
      color: var(--text-dark);
    }

    /* ── INTRO ── */
    #intro {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    #intro::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(27, 43, 75, 0.7) 0%, rgba(27, 43, 75, 0.3) 100%);
    }

    #intro > div {
      position: relative;
      z-index: 1;
      text-align: center;
      width: 100%;
      max-width: 900px;
      padding: 0 1rem;
    }

    #intro h1 {
      font-family: var(--font-display);
      font-size: clamp(3rem, 8vw, 7rem);
      font-weight: 300;
      color: var(--text-light);
      line-height: 1.1;
      letter-spacing: -0.02em;
      text-shadow: 0 4px 40px rgba(0, 0, 0, 0.3);
      word-break: break-word;
      overflow-wrap: break-word;
    }

    #intro h1 em {
      color: var(--gold-light);
      font-style: italic;
    }

    /* ── ABOUT ── */
    .about-inner {
      max-width: 1000px;
      margin: 0 auto 4rem;
      display: grid;
      grid-template-columns: 280px 1fr;
      gap: 4rem;
      align-items: center;
    }

    .about-inner img {
      width: 100%;
      aspect-ratio: 3/4;
      object-fit: cover;
      border-radius: 2px;
      box-shadow: 12px 12px 0 var(--blue-mid);
      border: 1px solid rgba(201, 169, 110, 0.3);
    }

    .about-inner h2 {
      font-family: var(--font-display);
      font-size: 3rem;
      font-weight: 300;
      color: var(--gold-light);
      margin-bottom: 1.5rem;
      line-height: 1;
    }

    .about-inner p {
      font-size: 1rem;
      line-height: 1.8;
      opacity: 0.85;
    }

    .about-subs {
      max-width: 1000px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
    }

    .about-sub {
      padding: 2rem;
      border: 1px solid rgba(201, 169, 110, 0.25);
      border-radius: 2px;
      background: rgba(255, 255, 255, 0.03);
    }

    .about-sub h2 {
      font-family: var(--font-display);
      font-size: 2rem;
      color: var(--gold-light);
      margin-bottom: 0.75rem;
      font-weight: 400;
    }

    .about-sub p {
      font-size: 0.9rem;
      line-height: 1.7;
      opacity: 0.75;
    }

    /* ── SECTION HEADER ── */
    .section-header {
      max-width: 1000px;
      margin: 0 auto 4rem;
    }

    .text-section-label {
      font-size: 0.75rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 0.75rem;
      font-weight: 500;
    }

    .section--biru .text-section-label {
      color: var(--gold-light);
    }

    .section-header h1 {
      font-family: var(--font-display);
      font-size: clamp(2rem, 4vw, 3.5rem);
      font-weight: 300;
      line-height: 1.2;
    }

    /* ── SLIDER ── */
    .slider-wrapper {
      max-width: 1000px;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
    }

    .slider-track {
      display: flex;
      transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .slider-slide {
      min-width: 100%;
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .slide-item {
      display: grid;
      grid-template-columns: 220px 1fr;
      gap: 2.5rem;
      align-items: start;
      padding: 2rem;
      border: 1px solid rgba(201, 169, 110, 0.15);
      border-radius: 2px;
      background: rgba(255, 255, 255, 0.03);
    }

    .section--creamee .slide-item {
      background: rgba(27, 43, 75, 0.04);
      border-color: rgba(27, 43, 75, 0.1);
    }

    .slide-item img {
      width: 100%;
      aspect-ratio: 4/3;
      object-fit: cover;
      border-radius: 1px;
    }

    .slide-item h2 {
      font-family: var(--font-display);
      font-size: 1.8rem;
      font-weight: 400;
      color: var(--gold-light);
      margin-bottom: 0.75rem;
    }

    .section--creamee .slide-item h2 {
      color: var(--blue);
    }

    .slide-item p {
      font-size: 0.9rem;
      line-height: 1.75;
      opacity: 0.8;
    }

    .slider-controls {
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
      margin-top: 2rem;
    }

    .btn-slider {
      background: none;
      border: 1px solid rgba(201, 169, 110, 0.4);
      padding: 0.6rem 1rem;
      cursor: pointer;
      border-radius: 1px;
      transition: all 0.3s;
    }

    .btn-slider:hover {
      background: rgba(201, 169, 110, 0.15);
      border-color: var(--gold);
    }

    .btn-slider img {
      width: 24px;
      height: 24px;
      display: block;
      filter: invert(1);
    }

    .section--creamee .btn-slider img {
      filter: none;
    }

    /* ── GALLERY ── */
    .gallery-overflow {
      overflow: hidden;
      max-width: 1200px;
      margin: 0 auto;
    }

    .gallery-track {
      display: flex;
      gap: 1.5rem;
      width: max-content;
      animation: galleryScroll 30s linear infinite;
    }

    .gallery-track:hover {
      animation-play-state: paused;
    }

    .gallery-image {
      width: 280px;
      height: 200px;
      object-fit: cover;
      border-radius: 2px;
      flex-shrink: 0;
      border: 1px solid rgba(201, 169, 110, 0.2);
    }

    @keyframes galleryScroll {
      from {
        transform: translateX(0);
      }

      to {
        transform: translateX(-50%);
      }
    }

    /* ── CONTACT ── */
    .contact-list {
      max-width: 700px;
      margin: 0 auto;
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .contact-list li {
      display: flex;
      align-items: center;
      gap: 1.25rem;
      padding: 1.25rem 1.5rem;
      border: 1px solid rgba(27, 43, 75, 0.12);
      border-radius: 2px;
      background: rgba(27, 43, 75, 0.03);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .contact-list li:hover {
      transform: translateX(6px);
      box-shadow: -3px 0 0 var(--blue);
    }

    .contact-icon img {
      width: 36px;
      height: 36px;
      object-fit: contain;
    }

    .contact-label {
      font-weight: 500;
      font-size: 0.85rem;
      letter-spacing: 0.05em;
      color: var(--blue);
      min-width: 80px;
    }

    .contact-list a {
      color: var(--blue);
      text-decoration: none;
      font-size: 0.95rem;
    }

    /* ── FOOTER ── */
    .site-footer {
      background: var(--blue);
      color: rgba(248, 245, 240, 0.6);
      padding: 3rem 2rem;
      text-align: center;
      border-top: 1px solid rgba(201, 169, 110, 0.2);
    }

    .footer-tagline {
      font-family: var(--font-display);
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
      font-style: italic;
    }

    .footer-socials {
      display: flex;
      justify-content: center;
      gap: 1rem;
    }

    .btn-social {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 44px;
      height: 44px;
      border: 1px solid rgba(201, 169, 110, 0.3);
      border-radius: 50%;
      transition: all 0.3s;
    }

    .btn-social:hover {
      background: rgba(201, 169, 110, 0.15);
      border-color: var(--gold);
    }

    .btn-social img {
      width: 20px;
      height: 20px;
      object-fit: contain;
      filter: invert(1) brightness(0.85);
    }

    .text-justify {
      text-align: justify;
    }

/* ── RESPONSIVE TABLET & MOBILE ── */
@media (max-width: 768px) {

  html,
  body {
    overflow-x: hidden;
    width: 100%;
  }

  .page-wrapper {
    overflow-x: hidden;
  }

  .site-nav {
    display: none;
  }

  .btn-menu-toggle {
    display: block;
  }

  .header-inner {
    padding: 0 1rem;
    height: 64px;
  }

  .site-title {
    font-size: 1.1rem;
    max-width: 80%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .mobile-menu {
    padding: 1rem;
  }

  .section {
    min-height: auto;
    padding: 90px 1rem 60px;
  }

  /* INTRO */
  #intro {
    min-height: 100vh;
    text-align: center;
    padding: 120px 1rem 80px;
  }

  #intro > div {
    width: 100%;
  }

  #intro h1 {
    font-size: clamp(2rem, 9vw, 3.5rem);
    line-height: 1.2;
    word-break: break-word;
  }

  /* ABOUT */
  .about-inner {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .about-inner img {
    max-width: 260px;
    margin: 0 auto;
    display: block;
  }

  .about-inner h2 {
    font-size: 2.2rem;
    text-align: center;
  }

  .about-inner p {
    font-size: 0.95rem;
  }

  .about-subs {
    grid-template-columns: 1fr;
  }

  .about-sub {
    padding: 1.5rem;
  }

  /* SECTION TITLE */
  .section-header {
    margin-bottom: 2rem;
  }

  .section-header h1 {
    font-size: 2rem;
    line-height: 1.3;
  }

  /* SLIDER */
  .slider-wrapper {
    overflow: hidden;
  }

  .slider-slide {
    gap: 1.5rem;
  }

  .slide-item {
    grid-template-columns: 1fr;
    gap: 1.25rem;
    padding: 1.25rem;
  }

  .slide-item img {
    width: 100%;
    height: auto;
  }

  .slide-item h2 {
    font-size: 1.5rem;
  }

  .slide-item p {
    font-size: 0.9rem;
  }

  .slider-controls {
    justify-content: center;
  }

  /* GALLERY */
  .gallery-image {
    width: 220px;
    height: 160px;
  }

  /* CONTACT */
  .contact-list li {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.8rem;
  }

  .contact-label {
    min-width: auto;
  }

  .contact-list a {
    word-break: break-word;
  }

  /* FOOTER */
  .footer-socials {
    flex-wrap: wrap;
  }
}
  </style>
</head>

<body>
  <header class="site-header">
    <div class="header-inner">
      <h3 class="site-title"><?= htmlspecialchars($site['title'] ?? '') ?></h3>
      <button id="menu-toggle" class="btn-menu-toggle">&#9776;</button>
      <nav class="site-nav">
        <ul>
          <li><a href="#intro">Intro</a></li>
          <li><a href="#about-me">About Me</a></li>
          <li><a href="#experience">Experience</a></li>
          <li><a href="#work">Work</a></li>
          <li><a href="#certificate">Certificate</a></li>
          <li><a href="#gallery">Gallery</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
    </div>
    <div id="mobile-menu" class="mobile-menu hidden">
      <ul>
        <li><a href="#intro">Intro</a></li>
        <li><a href="#about-me">About Me</a></li>
        <li><a href="#experience">Experience</a></li>
        <li><a href="#work">Work</a></li>
        <li><a href="#certificate">Certificate</a></li>
        <li><a href="#gallery">Gallery</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </div>
  </header>

  <main class="page-wrapper">
    <!-- INTRO -->
    <section id="intro" class="section"
      style="<?= $about['bg_intro'] ? 'background-image:url(' . htmlspecialchars($about['bg_intro']) . ');background-size:cover;background-position:center;' : 'background:linear-gradient(135deg,#1B2B4B 0%,#2A3F6B 100%);' ?>">
      <div>
        <h1><?= nl2br(htmlspecialchars($about['intro'] ?? 'Hello, I\'m a Developer')) ?></h1>
      </div>
    </section>

    <!-- ABOUT ME -->
    <section id="about-me" class="section section--biru">
      <div class="about-inner">
        <?php if (!empty($about['profile'])): ?>
          <img src="<?= htmlspecialchars($about['profile']) ?>" alt="Profile" />
        <?php else: ?>
          <div
            style="background:rgba(201,169,110,0.1);aspect-ratio:3/4;border-radius:2px;border:1px dashed rgba(201,169,110,0.3);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);font-size:0.8rem;">
            No Photo</div>
        <?php endif; ?>
        <div>
          <h2><?= htmlspecialchars($about['title'] ?? 'About Me') ?></h2>
          <p class="text-justify"><?= nl2br(htmlspecialchars($about['description'] ?? '')) ?></p>
        </div>
      </div>
      <div class="about-subs">
        <div class="about-sub">
          <h2><?= htmlspecialchars($site['sub_one'] ?? '') ?></h2>
          <p><?= nl2br(htmlspecialchars($site['sub_one_desc'] ?? '')) ?></p>
        </div>
        <div class="about-sub">
          <h2><?= htmlspecialchars($site['sub_two'] ?? '') ?></h2>
          <p><?= nl2br(htmlspecialchars($site['sub_two_desc'] ?? '')) ?></p>
        </div>
        <div class="about-sub">
          <h2><?= htmlspecialchars($site['sub_three'] ?? '') ?></h2>
          <p><?= nl2br(htmlspecialchars($site['sub_three_desc'] ?? '')) ?></p>
        </div>
      </div>
    </section>

    <!-- EXPERIENCE -->
    <section id="experience" class="section section--creamee">
      <div class="section-header">
        <p class="text-section-label">My Experience</p>
        <h1><?= htmlspecialchars($site['desc_pengalaman'] ?? 'Things I\'ve Done') ?></h1>
      </div>
      <?php if (!empty($pengalaman)): ?>
        <div class="slider-wrapper">
          <div id="experienceSlider" class="slider-track">
            <?php foreach ($pengalamanGrouped as $group): ?>
              <div class="slider-slide">
                <?php foreach ($group as $item): ?>
                  <div class="slide-item">
                    <?php if ($item['image']): ?>
                      <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" />
                    <?php else: ?>
                      <div
                        style="background:#e8e0d0;aspect-ratio:4/3;border-radius:1px;display:flex;align-items:center;justify-content:center;color:#999;font-size:0.75rem;">
                        No Image</div>
                    <?php endif; ?>
                    <div>
                      <h2><?= htmlspecialchars($item['title']) ?></h2>
                      <p class="text-justify"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="slider-controls">
            <button id="experiencePrev" class="btn-slider"><span
                style="font-size:1.2rem;color:#1B2B4B;">&#8592;</span></button>
            <button id="experienceNext" class="btn-slider"><span
                style="font-size:1.2rem;color:#1B2B4B;">&#8594;</span></button>
          </div>
        </div>
      <?php else: ?>
        <p style="text-align:center;opacity:0.5;max-width:1000px;margin:0 auto;">Belum ada data pengalaman.</p>
      <?php endif; ?>
    </section>

    <!-- WORK -->
    <section id="work" class="section section--biru">
      <div class="section-header">
        <p class="text-section-label">Recent Work</p>
        <h1><?= htmlspecialchars($site['desc_perkerjaan'] ?? 'Projects I\'ve Built') ?></h1>
      </div>
      <?php if (!empty($perkerjaan)): ?>
        <div class="slider-wrapper">
          <div id="workSlider" class="slider-track">
            <?php foreach ($perkerjaanGrouped as $group): ?>
              <div class="slider-slide">
                <?php foreach ($group as $item): ?>
                  <div class="slide-item">
                    <?php if ($item['image']): ?>
                      <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" />
                    <?php else: ?>
                      <div
                        style="background:rgba(255,255,255,0.05);aspect-ratio:4/3;border-radius:1px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.2);font-size:0.75rem;">
                        No Image</div>
                    <?php endif; ?>
                    <div>
                      <h2><?= htmlspecialchars($item['title']) ?></h2>
                      <p class="text-justify"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                      <?php if ($item['link']): ?>
                        <a href="<?= htmlspecialchars($item['link']) ?>" target="_blank"
                          style="display:inline-block;margin-top:1rem;color:var(--gold-light);font-size:0.85rem;text-decoration:underline;">View
                          Project →</a>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="slider-controls">
            <button id="workPrev" class="btn-slider"><span
                style="font-size:1.2rem;color:var(--gold-light);">&#8592;</span></button>
            <button id="workNext" class="btn-slider"><span
                style="font-size:1.2rem;color:var(--gold-light);">&#8594;</span></button>
          </div>
        </div>
      <?php else: ?>
        <p style="text-align:center;opacity:0.5;max-width:1000px;margin:0 auto;">Belum ada data perkerjaan.</p>
      <?php endif; ?>
    </section>

    <!-- CERTIFICATE -->
    <section id="certificate" class="section section--creamee">
      <div class="section-header">
        <p class="text-section-label">My Certificate</p>
        <h1><?= htmlspecialchars($site['desc_sertifikat'] ?? 'Credentials & Achievements') ?></h1>
      </div>
      <?php if (!empty($sertifikat)): ?>
        <div class="slider-wrapper">
          <div id="certificateSlider" class="slider-track">
            <?php foreach ($sertifikatGrouped as $group): ?>
              <div class="slider-slide">
                <?php foreach ($group as $item): ?>
                  <div class="slide-item">
                    <?php if ($item['image']): ?>
                      <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" />
                    <?php else: ?>
                      <div
                        style="background:#e8e0d0;aspect-ratio:4/3;border-radius:1px;display:flex;align-items:center;justify-content:center;color:#999;font-size:0.75rem;">
                        No Image</div>
                    <?php endif; ?>
                    <div>
                      <h2><?= htmlspecialchars($item['title']) ?></h2>
                      <p class="text-justify"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="slider-controls">
            <button id="certificatePrev" class="btn-slider"><span
                style="font-size:1.2rem;color:#1B2B4B;">&#8592;</span></button>
            <button id="certificateNext" class="btn-slider"><span
                style="font-size:1.2rem;color:#1B2B4B;">&#8594;</span></button>
          </div>
        </div>
      <?php else: ?>
        <p style="text-align:center;opacity:0.5;max-width:1000px;margin:0 auto;">Belum ada data sertifikat.</p>
      <?php endif; ?>
    </section>

    <!-- GALLERY -->
    <section id="gallery" class="section section--biru">
      <div class="section-header">
        <p class="text-section-label">My Gallery</p>
        <h1><?= htmlspecialchars($site['desc_galeri'] ?? 'Visual Stories') ?></h1>
      </div>
      <?php if (!empty($galeri)): ?>
        <div class="gallery-overflow">
          <div class="gallery-track">
            <?php foreach ($galeri as $g): ?>
              <img class="gallery-image" src="<?= htmlspecialchars($g['image']) ?>" alt="Gallery" />
            <?php endforeach; ?>
            <?php foreach ($galeri as $g): ?>
              <img class="gallery-image" src="<?= htmlspecialchars($g['image']) ?>" alt="Gallery" />
            <?php endforeach; ?>
          </div>
        </div>
      <?php else: ?>
        <p style="text-align:center;opacity:0.5;max-width:1000px;margin:0 auto;">Belum ada foto galeri.</p>
      <?php endif; ?>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="section section--creamee">
      <div class="section-header">
        <p class="text-section-label">My Contact</p>
        <h1><?= htmlspecialchars($site['desc_kontak'] ?? 'Let\'s Connect') ?></h1>
      </div>
      <ul class="contact-list">
        <?php if (!empty($about['whatsapp'])): ?>
          <li>
            <a class="contact-icon" href="<?= htmlspecialchars($about['whatsapp_link']) ?>" target="_blank">
              <img src="admin/assets/WaColorfull.png" alt="WhatsApp" onerror="this.style.display='none'" />
            </a>
            <span class="contact-label">WhatsApp:</span>
            <a href="<?= htmlspecialchars($about['whatsapp_link']) ?>" target="_blank">
              <?= htmlspecialchars($about['whatsapp']) ?>
            </a>
          </li>
        <?php endif; ?>
        <?php if (!empty($about['instagram'])): ?>
          <li>
            <a class="contact-icon" href="<?= htmlspecialchars($about['instagram_link']) ?>" target="_blank">
              <img src="admin/assets/InstaColorfull.png" alt="Instagram" onerror="this.style.display='none'" />
            </a>
            <span class="contact-label">Instagram:</span>
            <a href="<?= htmlspecialchars($about['instagram_link']) ?>" target="_blank">
              <?= htmlspecialchars($about['instagram']) ?>
            </a>
          </li>
        <?php endif; ?>
        <?php if (!empty($about['email'])): ?>
          <li>
            <a class="contact-icon" href="mailto:<?= htmlspecialchars($about['email']) ?>" target="_blank">
              <img src="admin/assets/GmailColorfull.png" alt="Email" onerror="this.style.display='none'" />
            </a>
            <span class="contact-label">Email:</span>
            <a href="mailto:<?= htmlspecialchars($about['email']) ?>" target="_blank">
              <?= htmlspecialchars($about['email']) ?>
            </a>
          </li>
        <?php endif; ?>
        <?php if (!empty($about['github'])): ?>
          <li>
            <a class="contact-icon" href="<?= htmlspecialchars($about['github']) ?>" target="_blank">
              <img src="admin/assets/Github.png" alt="GitHub" onerror="this.style.display='none'" />
            </a>
            <span class="contact-label">GitHub:</span>
            <a href="<?= htmlspecialchars($about['github']) ?>" target="_blank">
              <?= htmlspecialchars($about['github']) ?>
            </a>
          </li>
        <?php endif; ?>
        <?php if (!empty($about['linkedin'])): ?>
          <li>
            <a class="contact-icon" href="<?= htmlspecialchars($about['linkedin']) ?>" target="_blank">
              <img src="admin/assets/linkedinColorfull.png" alt="LinkedIn" onerror="this.style.display='none'" />
            </a>
            <span class="contact-label">LinkedIn:</span>
            <a href="<?= htmlspecialchars($about['linkedin']) ?>" target="_blank">
              <?= htmlspecialchars($about['linkedin']) ?>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </section>
  </main>

  <footer class="site-footer">
    <p class="footer-tagline"><?= htmlspecialchars($site['footer'] ?? '') ?></p>
    <div class="footer-socials">
      <?php if (!empty($about['whatsapp_link'])): ?>
        <a class="btn-social" href="<?= htmlspecialchars($about['whatsapp_link']) ?>" target="_blank">
          <img src="admin/assets/wa.png" alt="WhatsApp" onerror="this.innerHTML='W'" />
        </a>
      <?php endif; ?>
      <?php if (!empty($about['instagram_link'])): ?>
        <a class="btn-social" href="<?= htmlspecialchars($about['instagram_link']) ?>" target="_blank">
          <img src="admin/assets/ig.png" alt="Instagram" onerror="this.innerHTML='I'" />
        </a>
      <?php endif; ?>
      <?php if (!empty($about['email'])): ?>
        <a class="btn-social" href="mailto:<?= htmlspecialchars($about['email']) ?>" target="_blank">
          <img src="admin/assets/mail.png" alt="Email" onerror="this.innerHTML='E'" />
        </a>
      <?php endif; ?>
    </div>
  </footer>

  <script>
    // Mobile menu
    document.getElementById('menu-toggle').addEventListener('click', () => {
      document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Close mobile menu on link click
    document.querySelectorAll('#mobile-menu a').forEach(a => {
      a.addEventListener('click', () => document.getElementById('mobile-menu').classList.add('hidden'));
    });

    // Slider factory
    function initSlider(trackId, prevId, nextId) {
      const track = document.getElementById(trackId);
      if (!track) return;
      const slides = track.querySelectorAll('.slider-slide');
      if (slides.length === 0) return;
      let current = 0;

      function goTo(n) {
        current = (n + slides.length) % slides.length;
        track.style.transform = `translateX(-${current * 100}%)`;
      }

      document.getElementById(prevId)?.addEventListener('click', () => goTo(current - 1));
      document.getElementById(nextId)?.addEventListener('click', () => goTo(current + 1));
    }

    initSlider('experienceSlider', 'experiencePrev', 'experienceNext');
    initSlider('workSlider', 'workPrev', 'workNext');
    initSlider('certificateSlider', 'certificatePrev', 'certificateNext');

    // Smooth reveal on scroll
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.style.opacity = '1'; e.target.style.transform = 'translateY(0)'; }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.slide-item, .about-sub, .contact-list li').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(24px)';
      el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(el);
    });
  </script>
</body>

</html>