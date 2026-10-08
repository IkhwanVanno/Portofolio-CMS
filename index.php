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
  <title><?= htmlspecialchars($site['title'] ?? 'Portfolio') ?></title>
  <link rel="icon" href="./favicon.ico" type="image/x-icon" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="style.css">
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
    <div class="footer-container">
      <section class="footer-information">
        <div class="footer-col">
          <h3><?= htmlspecialchars($site['title'] ?? '') ?></h3>
          <p><?= htmlspecialchars($site['footer'] ?? '') ?></p>
        </div>

        <div class="footer-col">
          <h4>Navigasi</h4>
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

        <div class="footer-col">
          <h4>Kontak</h4>
          <ul class="social-links">
            <?php if (!empty($about['whatsapp_link'])): ?>
              <li>
                <a href="<?= htmlspecialchars($about['whatsapp_link']) ?>" target="_blank" rel="noopener noreferrer">
                  WhatsApp
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($about['instagram_link'])): ?>
              <li>
                <a href="<?= htmlspecialchars($about['instagram_link']) ?>" target="_blank" rel="noopener noreferrer">
                  Instagram
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($about['github'])): ?>
              <li>
                <a href="<?= htmlspecialchars($about['github']) ?>" target="_blank" rel="noopener noreferrer">
                  GitHub
                </a>
              </li>
            <?php endif; ?>

            <?php if (!empty($about['linkedin'])): ?>
              <li>
                <a href="<?= htmlspecialchars($about['linkedin']) ?>" target="_blank" rel="noopener noreferrer">
                  LinkedIn
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </section>

      <section class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($site['title'] ?? '') ?>. All rights reserved.</p>
        <p>Dibuat dengan PHP Native</p>
      </section>
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
        if (e.isIntersecting) {
          e.target.style.opacity = '1';
          e.target.style.transform = 'translateY(0)';
        }
      });
    }, {
      threshold: 0.1
    });

    document.querySelectorAll('.slide-item, .about-sub, .contact-list li').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(24px)';
      el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(el);
    });
  </script>
</body>

</html>