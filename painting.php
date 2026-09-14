<?php

// ======================================================
// BHEEMA ART GALLERY
// ARTWORK DETAIL PAGE — view a single painting, with a
// direct WhatsApp "Order Now" link.
//
// Expects: painting.php?id=123
// ======================================================

session_start();

require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/image.php";

// ---- WhatsApp number used for direct orders ----
// (Bheema Art Gallery contact number; update here if it changes.)
define('GALLERY_WHATSAPP_NUMBER', '919448312348');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$painting = null;

if ($conn && $id > 0) {
    $stmt = $conn->prepare("SELECT * FROM paintings WHERE id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            $painting = $res->fetch_assoc();
        }
        $stmt->close();
    }
}

$found = !empty($painting);

if ($found) {
    $title       = $painting['title']       ?? 'Untitled';
    $category    = $painting['category']    ?? '';
    $price       = $painting['price']       ?? 0;
    $image       = $painting['image']       ?? '';
    $description = $painting['description'] ?? '';
    $medium      = $painting['medium']      ?? '';
    $dimensions  = $painting['dimensions']  ?? '';

    $imagePath = getPaintingImage($image);

    $hasPrice = ((float) $price) > 0;
    $priceLabel = $hasPrice
        ? '₹' . number_format((float) $price, 2)
        : 'Contact for Price';

    // Pre-filled WhatsApp order message.
    $waMessage = "Hi Bheema Art Gallery, I'm interested in ordering \"" . $title . "\"";
    if ($category !== '') {
        $waMessage .= " (" . $category . ")";
    }
    $waMessage .= $hasPrice
        ? " priced at " . $priceLabel . ". Is it still available?"
        : ". Could you share the price and availability?";

    $waLink = "https://wa.me/" . GALLERY_WHATSAPP_NUMBER . "?text=" . rawurlencode($waMessage);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
<?php echo $found ? htmlspecialchars($title) . " | Bheema Art Gallery" : "Artwork Not Found | Bheema Art Gallery"; ?>
</title>

<meta
name="description"
content="<?php echo $found ? htmlspecialchars($title . ' - ' . $category . ' original artwork available at Bheema Art Gallery, Bengaluru.') : 'Artwork not found at Bheema Art Gallery.'; ?>"
>

<!-- GOOGLE FONTS -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Inter:wght@300;400;500;600;700&display=swap"
rel="stylesheet"
>

<style>

/* =====================================================
   TOKENS
===================================================== */

:root {

    /* -- gallery at night -- */
    --void: #0e0c0a;
    --void-2: #17130f;
    --void-line: #2c251d;

    /* -- daylight viewing room -- */
    --paper: #f6f1e6;
    --paper-2: #efe6d3;
    --mat: #fffdf8;

    /* -- accents -- */
    --gold: #c9a24b;
    --gold-soft: #e8cf95;
    --gold-dim: #8a713a;
    --oxblood: #5c1c26;
    --oxblood-2: #3f1119;

    /* -- text -- */
    --ink: #f3ecdd;
    --ink-dim: #b7ab97;
    --coal: #241c15;
    --coal-dim: #6d6252;

    --serif: 'Fraunces', serif;
    --sans: 'Inter', sans-serif;

    --ease: cubic-bezier(.16,.84,.44,1);
}


/* =====================================================
   RESET
===================================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


html {
    scroll-behavior: smooth;
}


body {

    font-family: var(--sans);

    background: var(--paper);

    color: var(--coal);

    overflow-x: hidden;

}


img {
    max-width: 100%;
}


a {
    font-family: inherit;
}


::selection {
    background: var(--gold);
    color: var(--void);
}


/* =====================================================
   SCROLL REVEAL (JS adds .in-view)
===================================================== */

.reveal {
    opacity: 0;
    transform: translateY(36px);
    transition: opacity .9s var(--ease), transform .9s var(--ease);
}

.reveal.in-view {
    opacity: 1;
    transform: translateY(0);
}

.reveal-stagger.in-view .stagger-item {
    opacity: 1;
    transform: translateY(0);
}

.stagger-item {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity .8s var(--ease), transform .8s var(--ease);
}

.reveal-stagger.in-view .stagger-item:nth-child(1) { transition-delay: .05s; }
.reveal-stagger.in-view .stagger-item:nth-child(2) { transition-delay: .16s; }
.reveal-stagger.in-view .stagger-item:nth-child(3) { transition-delay: .27s; }
.reveal-stagger.in-view .stagger-item:nth-child(4) { transition-delay: .38s; }
.reveal-stagger.in-view .stagger-item:nth-child(5) { transition-delay: .49s; }
.reveal-stagger.in-view .stagger-item:nth-child(6) { transition-delay: .60s; }


@media (prefers-reduced-motion: reduce) {

    html {
        scroll-behavior: auto;
    }

    .reveal,
    .stagger-item,
    .marquee-track,
    .load-in,
    * {
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }

    .reveal,
    .stagger-item {
        opacity: 1 !important;
        transform: none !important;
    }

    .fx-curtain {
        display: none !important;
    }

    .fx-frame {
        transform: none !important;
    }

}


/* =====================================================
   PAINTING FRAME EFFECTS
   (corner marks, glass shine, curtain unveil, 3D tilt)
   — applied inside every image section on the page
===================================================== */

.fx-frame {

    position: relative;

    will-change: transform;

}


/* gold corner brackets, like approaching a placard in a gallery */

.corner-mark {

    position: absolute;

    width: 16px;

    height: 16px;

    opacity: 0;

    pointer-events: none;

    z-index: 4;

    transition: opacity .4s var(--ease), width .5s var(--ease), height .5s var(--ease);

}


.corner-mark.tl { top: 10px;    left: 10px;  border-top: 2px solid var(--gold-soft);    border-left: 2px solid var(--gold-soft); }
.corner-mark.tr { top: 10px;    right: 10px; border-top: 2px solid var(--gold-soft);    border-right: 2px solid var(--gold-soft); }
.corner-mark.bl { bottom: 10px; left: 10px;  border-bottom: 2px solid var(--gold-soft); border-left: 2px solid var(--gold-soft); }
.corner-mark.br { bottom: 10px; right: 10px; border-bottom: 2px solid var(--gold-soft); border-right: 2px solid var(--gold-soft); }


.fx-frame:hover .corner-mark {

    opacity: 1;

    width: 26px;

    height: 26px;

}


/* a bar of light sweeping across the glass, like a passing spotlight */

.fx-shine {

    position: absolute;

    top: 0;

    left: -60%;

    width: 35%;

    height: 100%;

    background: linear-gradient(115deg, transparent, rgba(255,255,255,.38), transparent);

    transform: skewX(-20deg);

    transition: left .95s var(--ease);

    pointer-events: none;

    z-index: 3;

}


.fx-frame:hover .fx-shine {
    left: 130%;
}


/* the curtain that unveils each artwork as it scrolls into view */

.fx-curtain {

    position: absolute;

    inset: 0;

    background: linear-gradient(135deg, var(--gold-dim), var(--gold-soft));

    transform-origin: right;

    transform: scaleX(1);

    transition: transform 1.15s var(--ease) .15s;

    z-index: 5;

    pointer-events: none;

}


.reveal.in-view .fx-curtain {
    transform: scaleX(0);
}


/* wrapper used where the outer frame must not clip its own border */

.img-clip {

    position: relative;

    overflow: hidden;

}


/* =====================================================
   NAVIGATION
===================================================== */

nav {

    width: 100%;

    height: 76px;

    padding: 0 7%;

    position: fixed;

    top: 0;

    left: 0;

    z-index: 1000;

    display: flex;

    align-items: center;

    justify-content: space-between;

    background: rgba(14,12,10,.55);

    backdrop-filter: blur(14px);

    border-bottom: 1px solid rgba(201,162,75,.16);

    transition: background .4s var(--ease), border-color .4s var(--ease);

}


nav.scrolled {

    background: rgba(14,12,10,.92);

    border-bottom-color: rgba(201,162,75,.3);

}


.logo {

    text-decoration: none;

    color: var(--gold-soft);

    font-family: var(--serif);

    font-style: italic;

    font-size: 29px;

    font-weight: 500;

    letter-spacing: .3px;

}


.logo span {

    display: block;

    text-align: center;

    margin-top: -4px;

    font-family: var(--sans);

    font-style: normal;

    font-size: 8px;

    letter-spacing: 4.5px;

    color: var(--ink-dim);

}


.nav-links {

    list-style: none;

    display: flex;

    align-items: center;

    gap: 34px;

}


.nav-links a {

    text-decoration: none;

    color: var(--ink);

    font-size: 12px;

    letter-spacing: .5px;

    position: relative;

    padding-bottom: 4px;

}


.nav-links li:not(:last-child) a::after {

    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 1px;
    background: var(--gold);
    transition: width .35s var(--ease);

}


.nav-links li:not(:last-child) a:hover::after {
    width: 100%;
}


.nav-links li:not(:last-child) a:hover {
    color: var(--gold-soft);
}


.nav-contact {

    padding: 10px 22px;

    border: 1px solid var(--gold-dim);

    color: var(--gold-soft) !important;

    transition: .35s;

}


.nav-contact:hover {

    background: var(--gold);

    border-color: var(--gold);

    color: var(--void) !important;

}


/* =====================================================
   HERO — GALLERY AT NIGHT
===================================================== */

.hero {

    position: relative;

    min-height: 100vh;

    padding: 140px 7% 90px;

    display: grid;

    grid-template-columns: 1fr 1fr;

    align-items: center;

    gap: 60px;

    background: var(--void);

    overflow: hidden;

}


/* cursor-tracked spotlight, driven by --mx / --my set in JS */

.hero::before {

    content: "";

    position: absolute;

    inset: 0;

    background: radial-gradient(
        520px circle at var(--mx, 70%) var(--my, 38%),
        rgba(232,207,149,.16),
        rgba(232,207,149,.05) 38%,
        transparent 70%
    );

    pointer-events: none;

    transition: background .15s linear;

}


.hero::after {

    content: "";

    position: absolute;

    inset: 0;

    background:
        radial-gradient(ellipse at 15% 10%, rgba(201,162,75,.10), transparent 45%),
        radial-gradient(ellipse at 85% 90%, rgba(92,28,38,.22), transparent 50%);

    pointer-events: none;

}


.hero-content {

    max-width: 650px;

    position: relative;

    z-index: 2;

}


.hero-small {

    color: var(--gold);

    letter-spacing: 5px;

    text-transform: uppercase;

    font-size: 11px;

    margin-bottom: 22px;

    display: flex;

    align-items: center;

    gap: 12px;

}


.hero-small::before {

    content: "";
    width: 26px;
    height: 1px;
    background: var(--gold);
    display: inline-block;

}


.hero h1 {

    font-family: var(--serif);

    font-weight: 500;

    font-size: clamp(52px, 6.6vw, 88px);

    line-height: .96;

    color: var(--ink);

}


.hero h1 .word {

    display: inline-block;
    opacity: 0;
    transform: translateY(46px);
    animation: word-up .9s var(--ease) forwards;

}


.hero h1 span {

    font-style: italic;
    color: var(--gold-soft);

}


@keyframes word-up {
    to { opacity: 1; transform: translateY(0); }
}


.hero-description {

    max-width: 500px;

    margin-top: 28px;

    color: var(--ink-dim);

    line-height: 1.85;

    font-size: 14.5px;

    opacity: 0;

    animation: fade-up .9s var(--ease) .75s forwards;

}


.hero-buttons {

    display: flex;

    gap: 15px;

    margin-top: 36px;

    opacity: 0;

    animation: fade-up .9s var(--ease) .95s forwards;

}


@keyframes fade-up {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}


.btn {

    display: inline-block;

    padding: 15px 28px;

    text-decoration: none;

    font-size: 11.5px;

    letter-spacing: 1px;

    text-transform: uppercase;

    cursor: pointer;

    position: relative;

    overflow: hidden;

    z-index: 1;

    transition: color .4s var(--ease), border-color .4s var(--ease);

}


.btn::before {

    content: "";

    position: absolute;

    inset: 0;

    background: var(--gold);

    transform: scaleX(0);

    transform-origin: left;

    transition: transform .45s var(--ease);

    z-index: -1;

}


.btn:hover::before {
    transform: scaleX(1);
}


.btn-primary {

    background: var(--gold);

    color: var(--void);

    border: 1px solid var(--gold);

}


.btn-primary::before {
    background: var(--void);
}


.btn-primary:hover {
    color: var(--gold-soft);
}


.btn-outline {

    border: 1px solid var(--gold-dim);

    color: var(--gold-soft);

}


.btn-outline:hover {
    color: var(--void);
}


/* =====================================================
   HERO ART — SPOTLIT FRAME
===================================================== */

.hero-art {

    display: flex;

    justify-content: center;

    position: relative;

    z-index: 2;

}


.hero-frame {

    width: min(410px, 90%);

    padding: 14px;

    background: var(--mat);

    box-shadow:
        0 30px 70px rgba(0,0,0,.55),
        0 0 0 1px var(--gold-dim);

    transform: rotate(1.3deg);

    position: relative;

    opacity: 0;

    animation: frame-in 1.1s var(--ease) .3s forwards;

}


@keyframes frame-in {
    from { opacity: 0; transform: rotate(1.3deg) translateY(24px) scale(.97); }
    to   { opacity: 1; transform: rotate(1.3deg) translateY(0) scale(1); }
}


.hero-frame::before {

    /* light beam falling on the canvas from above */

    content: "";

    position: absolute;

    top: -120px;

    left: 50%;

    width: 340px;

    height: 240px;

    transform: translateX(-50%);

    background: conic-gradient(from 200deg at 50% 0%, transparent 40deg, rgba(232,207,149,.16) 60deg, transparent 80deg);

    pointer-events: none;

}


.hero-frame .img-clip {

    height: 500px;

}


.hero-frame img {

    width: 100%;

    height: 500px;

    object-fit: contain;

    background: var(--paper-2);

    display: block;

}


/* hero unveils on load rather than on scroll, so it gets its own timing */

.hero-frame .fx-curtain {

    transform: scaleX(1);

    animation: curtain-open 1.3s var(--ease) 1s forwards;

}


@keyframes curtain-open {
    to { transform: scaleX(0); }
}


/* =====================================================
   MARQUEE
===================================================== */

.marquee {

    background: var(--void-2);

    border-top: 1px solid var(--void-line);

    border-bottom: 1px solid var(--void-line);

    padding: 16px 0;

    overflow: hidden;

    white-space: nowrap;

}


.marquee-track {

    display: inline-flex;

    animation: marquee 28s linear infinite;

}


.marquee-track span {

    font-family: var(--serif);

    font-style: italic;

    font-size: 15px;

    color: var(--gold-soft);

    letter-spacing: .5px;

    padding: 0 28px;

    display: inline-flex;

    align-items: center;

    gap: 28px;

}


.marquee-track span::after {

    content: "\2726";
    font-style: normal;
    color: var(--gold-dim);
    font-size: 11px;

}


@keyframes marquee {

    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }

}


/* =====================================================
   SECTION HEADINGS
===================================================== */

.section-heading {

    text-align: center;

    margin-bottom: 60px;

}


.section-heading small {

    color: var(--gold-dim);

    text-transform: uppercase;

    letter-spacing: 4px;

    font-size: 11px;

}


.section-heading h2 {

    font-family: var(--serif);

    font-weight: 500;

    font-size: 54px;

    margin-top: 10px;

}


.section-heading p {

    max-width: 560px;

    margin: 14px auto 0;

    font-size: 14px;

    line-height: 1.8;

}


/* =====================================================
   CATEGORIES — GALLERY WALL
===================================================== */

.section-categories {

    padding: 110px 7%;

    background: var(--paper);

}


.section-categories .section-heading h2,
.section-categories .section-heading small {
    color: var(--coal);
}


.section-categories .section-heading p {
    color: var(--coal-dim);
}


.categories {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 22px;

}


.category {

    height: 340px;

    position: relative;

    overflow: hidden;

    text-decoration: none;

    border: 1px solid var(--paper-2);

}


.category img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition: transform 1.1s var(--ease), filter .6s var(--ease);

    filter: saturate(.85);

}


.category:hover img {

    transform: scale(1.1);
    filter: saturate(1.05);

}


.category-overlay {

    position: absolute;

    inset: 0;

    display: flex;

    flex-direction: column;

    justify-content: flex-end;

    padding: 24px;

    color: var(--ink);

    background: linear-gradient(transparent 35%, rgba(14,12,10,.92));

}


.category-eyebrow {

    font-size: 9px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 6px;
    opacity: 0;
    transform: translateY(8px);
    transition: opacity .4s var(--ease), transform .4s var(--ease);

}


.category:hover .category-eyebrow {
    opacity: 1;
    transform: translateY(0);
}


.category-overlay h3 {

    font-family: var(--serif);

    font-style: italic;

    font-weight: 500;

    font-size: 29px;

}


.category-overlay p {

    font-size: 11px;

    margin-top: 4px;

    color: var(--ink-dim);

}


/* =====================================================
   FEATURED — VIEWING ROOM
===================================================== */

.section-featured {

    padding: 110px 7%;

    background: var(--paper-2);

}


.section-featured .section-heading h2,
.section-featured .section-heading small {
    color: var(--coal);
}


.section-featured .section-heading p {
    color: var(--coal-dim);
}


.paintings {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 34px;

}


/* =====================================================
   PAINTING CARD — FRAMED, SPOTLIT ON HOVER
===================================================== */

.painting {

    background: var(--mat);

    overflow: hidden;

    box-shadow: 0 10px 34px rgba(36,28,21,.08);

    transition: transform .5s var(--ease), box-shadow .5s var(--ease);

    position: relative;

}


.painting:hover {

    transform: translateY(-9px);

    box-shadow: 0 26px 60px rgba(36,28,21,.16);

}


.painting-image {

    width: 100%;

    height: 400px;

    display: flex;

    justify-content: center;

    align-items: center;

    overflow: hidden;

    background: var(--paper-2);

    position: relative;

}


.painting-image::before {

    /* spotlight glow that switches on when the card is hovered */

    content: "";

    position: absolute;

    inset: 0;

    background: radial-gradient(circle at 50% 0%, rgba(201,162,75,.28), transparent 60%);

    opacity: 0;

    transition: opacity .5s var(--ease);

    pointer-events: none;

    z-index: 1;

}


.painting:hover .painting-image::before {
    opacity: 1;
}


.painting-image img {

    width: 100%;

    height: 100%;

    object-fit: contain;

    object-position: center;

    display: block;

    background: var(--paper-2);

    transition: transform .6s var(--ease);

}


.painting:hover .painting-image img {

    transform: scale(1.045);

}


.painting-info {

    padding: 24px;

    border-top: 1px solid var(--paper-2);

}


.painting-info h3 {

    font-family: var(--serif);

    font-weight: 500;

    font-size: 27px;

    color: var(--coal);

}


.painting-category {

    color: var(--gold-dim);

    text-transform: uppercase;

    font-size: 10px;

    letter-spacing: 2px;

    margin-top: 6px;

}


.painting-description {

    color: var(--coal-dim);

    font-size: 12px;

    line-height: 1.6;

    margin-top: 10px;

}


.painting-price {

    display: block;

    color: var(--oxblood);

    font-weight: 600;

    margin-top: 14px;

    font-size: 15px;

}


.artwork-btn {

    margin-top: 18px;

    padding: 11px 20px;

    border: 1px solid var(--coal);

    color: var(--coal);

}


.artwork-btn::before {
    background: var(--coal);
}


.artwork-btn:hover {
    color: var(--paper);
}


/* =====================================================
   EMPTY
===================================================== */

.empty-collection {

    grid-column: 1 / -1;

    background: var(--mat);

    padding: 90px 20px;

    text-align: center;

    border: 1px dashed var(--gold-dim);

}


.empty-collection h3 {

    font-family: var(--serif);

    font-style: italic;

    font-size: 32px;

    color: var(--coal);

}


.empty-collection p {

    color: var(--coal-dim);

    margin-top: 8px;

}


/* =====================================================
   ABOUT — CURATOR'S ROOM (DARK)
===================================================== */

.section-about {

    padding: 120px 7%;

    background: var(--void);

}


.about {

    display: grid;

    grid-template-columns: 1fr 1fr;

    align-items: center;

    gap: 80px;

}


.about-image {

    position: relative;

}


.about-image::before {

    content: "";

    position: absolute;

    inset: -14px;

    border: 1px solid var(--gold-dim);

    z-index: 0;

    transition: inset .6s var(--ease);

}


.about-image:hover::before {
    inset: -8px;
}


.about-image img {

    width: 100%;

    height: 520px;

    object-fit: cover;

    position: relative;

    z-index: 1;

    filter: saturate(.9) brightness(.96);

}


.about-content small {

    color: var(--gold);

    text-transform: uppercase;

    letter-spacing: 4px;

    font-size: 11px;

}


.about-content h2 {

    font-family: var(--serif);

    font-weight: 500;

    font-size: 52px;

    line-height: 1.05;

    margin: 16px 0 10px;

    color: var(--ink);

}


.about-divider {

    width: 0;

    height: 1px;

    background: var(--gold);

    margin-bottom: 22px;

    transition: width 1.1s var(--ease) .2s;

}


.reveal.in-view .about-divider {
    width: 80px;
}


.about-content p {

    color: var(--ink-dim);

    font-size: 14px;

    line-height: 1.9;

    margin-bottom: 17px;

}


.section-about .btn-primary {

    margin-top: 8px;

}


/* =====================================================
   CTA — VELVET ROPE
===================================================== */

.cta {

    padding: 110px 7%;

    text-align: center;

    background:
        radial-gradient(ellipse at 50% -10%, rgba(201,162,75,.14), transparent 55%),
        linear-gradient(160deg, var(--oxblood), var(--oxblood-2));

    color: var(--ink);

    position: relative;

}


.cta small {

    color: var(--gold-soft);

    text-transform: uppercase;

    letter-spacing: 5px;

    font-size: 11px;

}


.cta h2 {

    font-family: var(--serif);

    font-style: italic;

    font-weight: 500;

    font-size: 56px;

    margin: 16px 0;

}


.cta p {

    max-width: 580px;

    margin: auto;

    color: rgba(243,236,221,.78);

    font-size: 14px;

    line-height: 1.8;

}


.cta-buttons {

    margin-top: 34px;

    display: flex;

    justify-content: center;

    gap: 15px;

    flex-wrap: wrap;

}


.cta .btn-primary {

    background: var(--gold);

    border-color: var(--gold);

    color: var(--oxblood-2);

}


.cta .btn-primary::before {
    background: var(--ink);
}


.cta .btn-outline {

    border-color: rgba(243,236,221,.5);

    color: var(--ink);

}


.cta .btn-outline::before {
    background: var(--ink);
}


.cta .btn-outline:hover {
    color: var(--oxblood);
}


/* =====================================================
   INQUIRY FORM
===================================================== */

.inquiry-wrap {

    max-width: 560px;

    margin: 50px auto 0;

    text-align: left;

}


.form-alert {

    padding: 14px 18px;

    margin-bottom: 20px;

    font-size: 13px;

    line-height: 1.6;

    border: 1px solid;

}


.form-alert-success {

    background: rgba(232,207,149,.12);

    border-color: var(--gold);

    color: var(--gold-soft);

}


.form-alert-error {

    background: rgba(0,0,0,.15);

    border-color: rgba(243,236,221,.4);

    color: var(--ink);

}


/* honeypot: visually hidden but still reachable by bots that
   ignore CSS, which is exactly who we want to catch */

.hp-field {

    position: absolute;

    left: -9999px;

    width: 1px;

    height: 1px;

    overflow: hidden;

}


.inquiry-form {

    display: flex;

    flex-direction: column;

    gap: 14px;

}


.form-row {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 14px;

}


.inquiry-form input,
.inquiry-form textarea {

    width: 100%;

    padding: 14px 16px;

    background: rgba(243,236,221,.06);

    border: 1px solid rgba(243,236,221,.28);

    color: var(--ink);

    font-family: var(--sans);

    font-size: 13.5px;

    transition: border-color .35s var(--ease), background .35s var(--ease), box-shadow .35s var(--ease);

}


.inquiry-form textarea {

    resize: vertical;

    min-height: 100px;

}


.inquiry-form input::placeholder,
.inquiry-form textarea::placeholder {

    color: rgba(243,236,221,.5);

}


.inquiry-form input:focus,
.inquiry-form textarea:focus {

    outline: none;

    border-color: var(--gold);

    background: rgba(243,236,221,.1);

    box-shadow: 0 0 0 3px rgba(201,162,75,.18);

}


.inquiry-submit {

    align-self: flex-start;

    margin-top: 4px;

}


@media(max-width: 650px) {

    .form-row {
        grid-template-columns: 1fr;
    }

}

footer {

    background: var(--void);

    color: var(--ink-dim);

    padding: 64px 7% 26px;

    border-top: 1px solid var(--void-line);

}


.footer-grid {

    display: grid;

    grid-template-columns: 2fr 1fr 1fr 1fr;

    gap: 50px;

}


footer h3 {

    font-family: var(--serif);

    font-style: italic;

    font-size: 30px;

    color: var(--gold-soft);

}


footer h4 {

    color: var(--ink);

    font-size: 12px;

    letter-spacing: 1px;

    text-transform: uppercase;

    margin-bottom: 16px;

}


footer p,
footer a {

    color: var(--ink-dim);

    font-size: 12px;

    line-height: 2;

    text-decoration: none;

    transition: color .3s;

}


footer a:hover {

    color: var(--gold-soft);

}


.copyright {

    border-top: 1px solid var(--void-line);

    margin-top: 46px;

    padding-top: 20px;

    text-align: center;

    font-size: 11px;

    color: var(--coal-dim);

}


/* =====================================================
   BACK TO TOP
===================================================== */

.to-top {

    position: fixed;

    right: 26px;

    bottom: 26px;

    width: 46px;

    height: 46px;

    border: 1px solid var(--gold-dim);

    background: rgba(14,12,10,.75);

    backdrop-filter: blur(8px);

    color: var(--gold-soft);

    display: flex;

    align-items: center;

    justify-content: center;

    text-decoration: none;

    font-size: 16px;

    z-index: 900;

    opacity: 0;

    transform: translateY(12px);

    pointer-events: none;

    transition: opacity .4s var(--ease), transform .4s var(--ease), background .3s;

}


.to-top.visible {

    opacity: 1;

    transform: translateY(0);

    pointer-events: auto;

}


.to-top:hover {

    background: var(--gold);

    color: var(--void);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 950px) {

    .nav-links {

        gap: 15px;

    }


    .hero {

        grid-template-columns: 1fr;

    }


    .hero-art {

        display: none;

    }


    .categories {

        grid-template-columns: repeat(2, 1fr);

    }


    .paintings {

        grid-template-columns: repeat(2, 1fr);

    }


    .about {

        grid-template-columns: 1fr;

        gap: 40px;

    }


    .footer-grid {

        grid-template-columns: repeat(2, 1fr);

    }

}


@media(max-width: 650px) {

    nav {

        padding: 0 5%;

    }


    .nav-links {

        display: none;

    }


    .hero {

        padding: 130px 5% 70px;

    }


    .hero h1 {

        font-size: 54px;

    }


    .hero-buttons {

        flex-direction: column;

    }


    .section-categories,
    .section-featured,
    .section-about,
    .cta {

        padding: 74px 5%;

    }


    .section-heading h2 {

        font-size: 40px;

    }


    .categories,
    .paintings {

        grid-template-columns: 1fr;

    }


    .painting-image {

        height: 410px;

    }


    .about-content h2 {

        font-size: 42px;

    }


    .footer-grid {

        grid-template-columns: 1fr;

    }


    .cta h2 {

        font-size: 40px;

    }

}

/* =====================================================
   SPLASH SCREEN (logo shown on load, then fades to site)
===================================================== */

#splashScreen {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background:
        radial-gradient(circle at 50% 50%, rgba(201, 162, 75, 0.16), transparent 60%),
        var(--void);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.6s ease;
    overflow: hidden;
}

#splashScreen .glow-ring {
    position: absolute;
    width: min(420px, 80vw);
    height: min(420px, 80vw);
    border-radius: 50%;
    background: radial-gradient(circle, rgba(201, 162, 75, 0.35), rgba(201, 162, 75, 0) 70%);
    animation: splashRingPulse 2.4s ease-in-out infinite;
}

#splashScreen img {
    position: relative;
    z-index: 2;
    width: min(260px, 55vw);
    height: auto;
    opacity: 0;
    filter: drop-shadow(0 0 0 rgba(201, 162, 75, 0));
    animation:
        splashLogoEntrance 0.9s cubic-bezier(0.34, 1.56, 0.64, 1) forwards,
        splashLogoGlow 2.2s ease-in-out 0.9s infinite;
}

#splashScreen.splash-fade-out {
    opacity: 0;
    pointer-events: none;
}

@keyframes splashRingPulse {
    0%, 100% { transform: scale(0.85); opacity: 0.6; }
    50% { transform: scale(1.15); opacity: 1; }
}

@keyframes splashLogoEntrance {
    0% { opacity: 0; transform: scale(0.55) rotate(-8deg) translateY(24px); }
    70% { opacity: 1; transform: scale(1.06) rotate(1.5deg) translateY(-4px); }
    100% { opacity: 1; transform: scale(1) rotate(0deg) translateY(0); }
}

@keyframes splashLogoGlow {
    0%, 100% { filter: drop-shadow(0 0 6px rgba(201, 162, 75, 0.25)); transform: scale(1); }
    50% { filter: drop-shadow(0 0 26px rgba(201, 162, 75, 0.55)); transform: scale(1.035); }
}

@media (prefers-reduced-motion: reduce) {
    #splashScreen img { animation: none; opacity: 1; }
    #splashScreen .glow-ring { animation: none; }
}



/* =====================================================
   ARTWORK DETAIL PAGE
===================================================== */

.artwork-detail-section {
    padding: 160px 8vw 100px;
    max-width: 1280px;
    margin: 0 auto;
}

.artwork-breadcrumb {
    font-family: var(--sans, 'Inter', sans-serif);
    font-size: 12px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--gold-dim);
    margin-bottom: 40px;
}

.artwork-breadcrumb a {
    color: var(--gold-dim);
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: color 0.2s, border-color 0.2s;
}

.artwork-breadcrumb a:hover {
    color: var(--gold);
    border-color: var(--gold);
}

.artwork-detail-grid {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: 72px;
    align-items: start;
}

.artwork-image-frame {
    position: relative;
    border: 1px solid var(--void-line);
    background: var(--void-2);
    padding: 18px;
}

.artwork-image-frame img {
    width: 100%;
    height: auto;
    display: block;
    aspect-ratio: 4 / 5;
    object-fit: cover;
}

.artwork-panel {
    padding-top: 8px;
}

.artwork-panel .painting-category {
    margin-bottom: 14px;
}

.artwork-panel h1 {
    font-family: var(--serif);
    font-size: clamp(32px, 3.6vw, 48px);
    line-height: 1.08;
    color: var(--ink);
    margin: 0 0 20px;
}

.artwork-panel .artwork-price {
    display: inline-block;
    font-family: var(--serif);
    font-size: 26px;
    color: var(--gold);
    margin-bottom: 28px;
}

.artwork-meta-list {
    list-style: none;
    margin: 0 0 32px;
    padding: 0;
    border-top: 1px solid var(--void-line);
}

.artwork-meta-list li {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 14px 0;
    border-bottom: 1px solid var(--void-line);
    font-size: 14.5px;
}

.artwork-meta-list .meta-label {
    font-family: var(--sans, 'Inter', sans-serif);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-size: 11px;
    color: var(--coal-dim);
}

.artwork-meta-list .meta-value {
    color: var(--coal);
    text-align: right;
}

.artwork-description {
    font-size: 16px;
    line-height: 1.85;
    color: var(--coal-dim);
    margin-bottom: 36px;
    font-weight: 300;
}

.artwork-order-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}

.btn-whatsapp {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #25D366;
    color: #0e0c0a;
    border: 1px solid #25D366;
    font-weight: 600;
}

.btn-whatsapp:hover {
    background: #1ebe5a;
    border-color: #1ebe5a;
}

.artwork-order-note {
    font-size: 13px;
    color: var(--coal-dim);
    line-height: 1.6;
}

.artwork-not-found {
    text-align: center;
    padding: 220px 6vw 160px;
}

.artwork-not-found h1 {
    font-family: var(--serif);
    font-size: clamp(30px, 4vw, 44px);
    color: var(--ink);
    margin-bottom: 16px;
}

.artwork-not-found p {
    color: var(--ink-dim);
    margin-bottom: 32px;
}

@media (max-width: 860px) {
    .artwork-detail-section { padding: 130px 6vw 70px; }
    .artwork-detail-grid { grid-template-columns: 1fr; gap: 40px; }
}

</style>

</head>


<body>


<nav id="siteNav">

<a href="index.php" class="logo">

    Bheema

    <span>
        ART GALLERY
    </span>

</a>


<ul class="nav-links">

    <li>
        <a href="index.php">
            Home
        </a>
    </li>

    <li>
        <a href="collections.php">
            Collections
        </a>
    </li>

    <li>
        <a href="index.php#featured">
            Paintings
        </a>
    </li>

    <li>
        <a href="index.php#about">
            About
        </a>
    </li>

    <li>
        <a href="index.php#contact" class="nav-contact">
            Contact
        </a>
    </li>

</ul>

</nav>


<?php if ($found) { ?>

<section class="artwork-detail-section reveal in-view">

    <div class="artwork-breadcrumb">
        <a href="index.php">Home</a> / <a href="collections.php">Collections</a> / <?php echo htmlspecialchars($title); ?>
    </div>

    <div class="artwork-detail-grid">

        <div class="artwork-image-frame fx-frame tilt">
            <img
                src="<?php echo htmlspecialchars($imagePath); ?>"
                alt="<?php echo htmlspecialchars($title); ?>"
                onerror="this.onerror=null;this.src='images/image-not-found.png';"
            >
        </div>

        <div class="artwork-panel">

            <?php if ($category !== '') { ?>
            <div class="painting-category">
                <?php echo htmlspecialchars($category); ?>
            </div>
            <?php } ?>

            <h1><?php echo htmlspecialchars($title); ?></h1>

            <span class="artwork-price"><?php echo $priceLabel; ?></span>

            <ul class="artwork-meta-list">

                <?php if ($category !== '') { ?>
                <li>
                    <span class="meta-label">Category</span>
                    <span class="meta-value"><?php echo htmlspecialchars($category); ?></span>
                </li>
                <?php } ?>

                <?php if ($medium !== '') { ?>
                <li>
                    <span class="meta-label">Medium</span>
                    <span class="meta-value"><?php echo htmlspecialchars($medium); ?></span>
                </li>
                <?php } ?>

                <?php if ($dimensions !== '') { ?>
                <li>
                    <span class="meta-label">Dimensions</span>
                    <span class="meta-value"><?php echo htmlspecialchars($dimensions); ?></span>
                </li>
                <?php } ?>

                <li>
                    <span class="meta-label">Availability</span>
                    <span class="meta-value">On view at the gallery</span>
                </li>

            </ul>

            <?php if ($description !== '') { ?>
            <p class="artwork-description">
                <?php echo nl2br(htmlspecialchars($description)); ?>
            </p>
            <?php } else { ?>
            <p class="artwork-description">
                An original piece from Bheema Art Gallery, Kengeri Satellite Town, Bengaluru.
                Contact us for more details on this artwork, including framing and delivery options.
            </p>
            <?php } ?>

            <div class="artwork-order-buttons">

                <a
                    href="<?php echo htmlspecialchars($waLink); ?>"
                    target="_blank"
                    rel="noopener"
                    class="btn btn-whatsapp"
                >
                    Order Now on WhatsApp
                </a>

                <a
                    href="tel:+<?php echo GALLERY_WHATSAPP_NUMBER; ?>"
                    class="btn btn-outline"
                >
                    Call Gallery
                </a>

            </div>

            <p class="artwork-order-note">
                Tapping "Order Now" opens WhatsApp with a message already filled in for this artwork —
                just hit send and our team will confirm availability, pricing and delivery.
            </p>

        </div>

    </div>

</section>

<?php } else { ?>

<section class="artwork-not-found reveal in-view">
    <h1>Artwork Not Found</h1>
    <p>We couldn't find the piece you were looking for. It may have been sold or the link may be outdated.</p>
    <a href="collections.php" class="btn btn-primary">Browse Collections</a>
</section>

<?php } ?>


<footer>


<div class="footer-grid">


<div>

    <h3>
        Bheema
    </h3>

    <p>
        Art Gallery
    </p>

    <p>
        Paintings • Art • Creativity
    </p>

</div>


<div>

    <h4>
        Explore
    </h4>


    <p>
        <a href="index.php">
            Home
        </a>
    </p>


    <p>
        <a href="collections.php">
            Collections
        </a>
    </p>


    <p>
        <a href="#featured">
            Paintings
        </a>
    </p>


    <p>
        <a href="#about">
            About Us
        </a>
    </p>

</div>


<div>

    <h4>
        Gallery
    </h4>


    <p>
        <a href="collections.php">
            Traditional Art
        </a>
    </p>


    <p>
        <a href="collections.php">
            Modern Art
        </a>
    </p>


    <p>
        <a href="collections.php">
            Portraits
        </a>
    </p>


    <p>
        <a href="collections.php">
            Nature
        </a>
    </p>

</div>


<div>

    <h4>
        Contact
    </h4>


    <p>
        📞 +91 94483 12348, +91 81059 6548
    </p>


    <p>
        📧 bheemaartgallery@gmail.com
    </p>


    <p>
        📍 Kengeri Satellite Town, Bengaluru 560060
    </p>

</div>


</div>


<div class="copyright">

    © <?php echo date("Y"); ?>

    Bheema Art Gallery.

    All Rights Reserved.

</div>


</footer>

<a href="#home" class="to-top" id="toTop" aria-label="Back to top">↑</a>


<script>

// ---- nav bar solidifies on scroll ----
(function () {

    var nav = document.getElementById('siteNav');
    var toTop = document.getElementById('toTop');

    if (!nav) return;

    function onScroll() {

        if (window.scrollY > 60) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }

        if (toTop) {

            if (window.scrollY > 700) {
                toTop.classList.add('visible');
            } else {
                toTop.classList.remove('visible');
            }

        }

    }

    document.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

})();


// ---- scroll-triggered reveal ----
(function () {

    var targets = document.querySelectorAll('.reveal');

    if (!('IntersectionObserver' in window)) {

        targets.forEach(function (el) {
            el.classList.add('in-view');
        });

        return;

    }

    var observer = new IntersectionObserver(function (entries) {

        entries.forEach(function (entry) {

            if (entry.isIntersecting) {

                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);

            }

        });

    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

    targets.forEach(function (el) {
        observer.observe(el);
    });

})();


// ---- 3D tilt on the artwork frame ----
(function () {

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    var tiltEls = document.querySelectorAll('.tilt');

    tiltEls.forEach(function (el) {

        el.addEventListener('mousemove', function (e) {

            var r = el.getBoundingClientRect();

            var px = (e.clientX - r.left) / r.width - 0.5;
            var py = (e.clientY - r.top) / r.height - 0.5;

            var rx = (py * -7).toFixed(2);
            var ry = (px * 7).toFixed(2);

            el.style.transform = 'perspective(800px) rotateX(' + rx + 'deg) rotateY(' + ry + 'deg)';

        });

        el.addEventListener('mouseleave', function () {
            el.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg)';
        });

    });

})();

</script>

</body>
</html>