<?php
// offers.php
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Special Offers | Bheema Art Gallery</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --black: #0d0b09;
            --black2: #15110e;
            --black3: #1c1713;

            --gold: #c9a45c;
            --gold-light: #e6cf99;
            --gold-dark: #92723a;

            --cream: #f5efe3;
            --cream2: #e8dece;

            --text: #d4ccc0;
            --muted: #999083;

            --line: rgba(201, 164, 92, 0.25);

            --red: #7d2925;
        }

        body {
            background: var(--black);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
        }

        /* =========================
           NAVBAR
        ========================= */

        nav {
            height: 80px;
            width: 100%;

            position: fixed;
            top: 0;
            left: 0;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 7%;

            background: rgba(13, 11, 9, 0.94);
            backdrop-filter: blur(15px);

            border-bottom: 1px solid var(--line);

            z-index: 1000;
        }

        .logo {
            text-decoration: none;
            color: var(--cream);

            font-family: 'Fraunces', serif;
            font-size: 25px;

            letter-spacing: 1px;
        }

        .logo span {
            color: var(--gold);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text);

            font-size: 14px;

            transition: 0.3s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--gold);
        }

        /* =========================
           HERO
        ========================= */

        .hero {
            min-height: 65vh;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;

            padding: 140px 20px 90px;

            background:
                radial-gradient(
                    circle at center,
                    rgba(201,164,92,0.13),
                    transparent 55%
                ),
                var(--black);
        }

        .hero-content {
            max-width: 850px;
        }

        .eyebrow {
            color: var(--gold);

            text-transform: uppercase;

            letter-spacing: 5px;

            font-size: 11px;

            margin-bottom: 20px;
        }

        .hero h1 {
            font-family: 'Fraunces', serif;

            font-weight: 300;

            font-size: clamp(55px, 9vw, 105px);

            line-height: 1;

            color: var(--cream);

            margin-bottom: 25px;
        }

        .hero h1 span {
            color: var(--gold);
            font-style: italic;
        }

        .hero p {
            max-width: 650px;

            margin: auto;

            color: var(--muted);

            font-size: 16px;
        }

        /* =========================
           OFFER BANNER
        ========================= */

        .offer-banner {
            background: var(--gold);

            color: var(--black);

            padding: 18px 20px;

            text-align: center;

            font-size: 14px;

            font-weight: 600;

            letter-spacing: 1px;
        }

        .offer-banner span {
            margin-left: 12px;

            font-family: 'Fraunces', serif;

            font-size: 20px;
        }

        /* =========================
           OFFERS SECTION
        ========================= */

        .offers {
            max-width: 1200px;

            margin: auto;

            padding: 100px 7%;
        }

        .section-title {
            text-align: center;

            margin-bottom: 60px;
        }

        .section-title small {
            color: var(--gold);

            text-transform: uppercase;

            letter-spacing: 4px;

            font-size: 11px;
        }

        .section-title h2 {
            font-family: 'Fraunces', serif;

            font-size: 52px;

            font-weight: 400;

            color: var(--cream);

            margin-top: 12px;
        }

        .section-title p {
            max-width: 650px;

            margin: 15px auto 0;

            color: var(--muted);

            font-size: 14px;
        }

        /* =========================
           OFFER CARDS
        ========================= */

        .offer-grid {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;
        }

        .offer-card {
            position: relative;

            background: var(--black2);

            border: 1px solid var(--line);

            padding: 42px 30px;

            overflow: hidden;

            transition: 0.4s;
        }

        .offer-card:hover {
            transform: translateY(-8px);

            border-color: var(--gold);
        }

        .offer-card::before {
            content: "";

            position: absolute;

            width: 100px;
            height: 100px;

            border-radius: 50%;

            background: rgba(201,164,92,0.06);

            top: -40px;
            right: -40px;
        }

        .discount {
            display: inline-block;

            padding: 6px 12px;

            background: var(--red);

            color: white;

            font-size: 11px;

            letter-spacing: 1px;

            margin-bottom: 25px;
        }

        .offer-icon {
            font-size: 38px;

            margin-bottom: 18px;
        }

        .offer-card h3 {
            font-family: 'Fraunces', serif;

            font-size: 28px;

            color: var(--cream);

            font-weight: 400;

            margin-bottom: 12px;
        }

        .offer-card p {
            color: var(--muted);

            font-size: 14px;

            margin-bottom: 25px;
        }

        .offer-price {
            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 25px;
        }

        .old-price {
            color: #777066;

            text-decoration: line-through;

            font-size: 14px;
        }

        .new-price {
            color: var(--gold-light);

            font-family: 'Fraunces', serif;

            font-size: 27px;
        }

        .offer-btn {
            display: inline-block;

            padding: 11px 20px;

            border: 1px solid var(--gold);

            color: var(--gold-light);

            text-decoration: none;

            font-size: 13px;

            transition: 0.3s;
        }

        .offer-btn:hover {
            background: var(--gold);

            color: var(--black);
        }

        /* =========================
           FEATURE OFFER
        ========================= */

        .featured-offer {
            max-width: 1100px;

            margin: 20px auto 110px;

            padding: 65px;

            display: grid;

            grid-template-columns: 1.2fr 1fr;

            gap: 60px;

            align-items: center;

            background:
                linear-gradient(
                    135deg,
                    rgba(201,164,92,0.13),
                    rgba(255,255,255,0.015)
                );

            border: 1px solid var(--line);
        }

        .featured-image {
            height: 390px;

            overflow: hidden;

            border: 1px solid var(--line);
        }

        .featured-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: 0.6s;
        }

        .featured-offer:hover .featured-image img {
            transform: scale(1.04);
        }

        .featured-content small {
            color: var(--gold);

            letter-spacing: 4px;

            text-transform: uppercase;

            font-size: 10px;
        }

        .featured-content h2 {
            font-family: 'Fraunces', serif;

            color: var(--cream);

            font-size: 48px;

            font-weight: 400;

            line-height: 1.15;

            margin: 15px 0 20px;
        }

        .featured-content p {
            color: var(--muted);

            font-size: 14px;

            margin-bottom: 25px;
        }

        .big-discount {
            font-family: 'Fraunces', serif;

            color: var(--gold-light);

            font-size: 42px;

            margin-bottom: 20px;
        }

        /* =========================
           BENEFITS
        ========================= */

        .benefits {
            background: var(--cream);

            color: #282118;

            padding: 100px 7%;
        }

        .benefit-inner {
            max-width: 1100px;

            margin: auto;
        }

        .benefits .section-title h2 {
            color: #282118;
        }

        .benefits .section-title p {
            color: #70685c;
        }

        .benefit-grid {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 25px;
        }

        .benefit {
            text-align: center;

            padding: 25px 15px;
        }

        .benefit-icon {
            font-size: 35px;

            margin-bottom: 15px;
        }

        .benefit h3 {
            font-family: 'Fraunces', serif;

            font-size: 22px;

            margin-bottom: 8px;
        }

        .benefit p {
            font-size: 13px;

            color: #70685c;
        }

        /* =========================
           HOW IT WORKS
        ========================= */

        .how {
            max-width: 1100px;

            margin: auto;

            padding: 110px 7%;
        }

        .steps {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 30px;
        }

        .step {
            border-top: 1px solid var(--gold);

            padding-top: 25px;
        }

        .step-number {
            font-family: 'Fraunces', serif;

            color: var(--gold);

            font-size: 30px;

            margin-bottom: 12px;
        }

        .step h3 {
            color: var(--cream);

            font-family: 'Fraunces', serif;

            font-size: 24px;

            margin-bottom: 10px;
        }

        .step p {
            color: var(--muted);

            font-size: 14px;
        }

        /* =========================
           CTA
        ========================= */

        .cta {
            text-align: center;

            padding: 110px 20px;

            background:
                linear-gradient(
                    rgba(13,11,9,0.88),
                    rgba(13,11,9,0.95)
                ),
                url('image.png') center/cover;
        }

        .cta h2 {
            font-family: 'Fraunces', serif;

            color: var(--cream);

            font-size: 55px;

            font-weight: 400;
        }

        .cta p {
            color: var(--muted);

            margin: 15px auto 30px;

            max-width: 600px;
        }

        .cta-btn {
            display: inline-block;

            padding: 14px 30px;

            border: 1px solid var(--gold);

            color: var(--gold-light);

            text-decoration: none;

            transition: 0.3s;
        }

        .cta-btn:hover {
            background: var(--gold);

            color: var(--black);
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            text-align: center;

            padding: 35px 20px;

            border-top: 1px solid var(--line);

            color: var(--muted);

            font-size: 13px;
        }

        footer strong {
            color: var(--gold);

            font-family: 'Fraunces', serif;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width: 900px) {

            .offer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .featured-offer {
                grid-template-columns: 1fr;

                padding: 40px;
            }

            .benefit-grid {
                grid-template-columns: 1fr 1fr;
            }

            .steps {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 650px) {

            nav {
                padding: 0 20px;
            }

            .logo {
                font-size: 19px;
            }

            .nav-links {
                gap: 12px;
            }

            .nav-links a {
                font-size: 11px;
            }

            .nav-links a:nth-child(2) {
                display: none;
            }

            .offer-grid {
                grid-template-columns: 1fr;
            }

            .benefit-grid {
                grid-template-columns: 1fr;
            }

            .featured-offer {
                padding: 25px;
            }

            .featured-image {
                height: 280px;
            }

            .section-title h2 {
                font-size: 40px;
            }

            .featured-content h2 {
                font-size: 38px;
            }

            .cta h2 {
                font-size: 40px;
            }

        }

    </style>

</head>

<body>


<!-- =========================
     NAVIGATION
========================= -->

<nav>

    <a href="index.php" class="logo">
        Bheema <span>Art Gallery</span>
    </a>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="collections.php">Collections</a>

        <a href="artist.php">Artist</a>

        <a href="offers.php" class="active">Offers</a>

        <a href="index.php#contact">Contact</a>

    </div>

</nav>


<!-- =========================
     HERO
========================= -->

<section class="hero">

    <div class="hero-content">

        <div class="eyebrow">
            Exclusive Gallery Offers
        </div>

        <h1>
            Art Worth <span>Owning</span>
        </h1>

        <p>
            Discover special opportunities to bring original artwork,
            custom drawings and meaningful pieces into your home at
            exceptional prices.
        </p>

    </div>

</section>


<!-- OFFER ANNOUNCEMENT -->

<div class="offer-banner">

    SPECIAL COLLECTION OFFER

    <span>
        Selected artworks available at exclusive prices
    </span>

</div>


<!-- =========================
     OFFER CARDS
========================= -->

<section class="offers">

    <div class="section-title">

        <small>Limited Opportunities</small>

        <h2>
            Current Offers
        </h2>

        <p>
            Explore our special art offers and discover a piece that
            deserves a place in your collection.
        </p>

    </div>


    <div class="offer-grid">


        <!-- OFFER 1 -->

        <div class="offer-card">

            <div class="discount">
                SPECIAL OFFER
            </div>

            <div class="offer-icon">
                🎨
            </div>

            <h3>
                Original Paintings
            </h3>

            <p>
                Selected original paintings are available at special
                prices for art lovers and collectors.
            </p>

            <div class="offer-price">

                <span class="old-price">
                    Selected Price
                </span>

                <span class="new-price">
                    Special
                </span>

            </div>

            <a href="collections.php" class="offer-btn">
                Explore Paintings
            </a>

        </div>


        <!-- OFFER 2 -->

        <div class="offer-card">

            <div class="discount">
                POPULAR
            </div>

            <div class="offer-icon">
                🖼️
            </div>

            <h3>
                Custom Portraits
            </h3>

            <p>
                Turn your favourite photograph into a personalised
                hand-drawn or painted portrait.
            </p>

            <div class="offer-price">

                <span class="old-price">
                    Custom
                </span>

                <span class="new-price">
                    Order
                </span>

            </div>

            <a href="#contact" class="offer-btn">
                Enquire Now
            </a>

        </div>


        <!-- OFFER 3 -->

        <div class="offer-card">

            <div class="discount">
                LIMITED
            </div>

            <div class="offer-icon">
                ✏️
            </div>

            <h3>
                Special Drawings
            </h3>

            <p>
                Detailed pencil and artistic drawings created with
                patience, precision and personal attention.
            </p>

            <div class="offer-price">

                <span class="old-price">
                    Limited
                </span>

                <span class="new-price">
                    Collection
                </span>

            </div>

            <a href="collections.php" class="offer-btn">
                View Drawings
            </a>

        </div>


    </div>

</section>


<!-- =========================
     FEATURED OFFER
========================= -->

<section class="featured-offer">

    <div class="featured-image">

       <img
    src="images/image6.png"
    alt=" Featured Art work"
>

    </div>


    <div class="featured-content">

        <small>
            Featured Opportunity
        </small>

        <h2>
            Make Your Memories
            a Work of Art
        </h2>

        <p>
            Have a favourite photograph, family memory or special moment?
            Turn it into a unique piece of artwork created especially for you.
        </p>

        <div class="big-discount">
            Custom Artwork
        </div>

        <a href="#contact" class="offer-btn">
            Discuss Your Artwork
        </a>

    </div>

</section>


<!-- =========================
     BENEFITS
========================= -->

<section class="benefits">

    <div class="benefit-inner">

        <div class="section-title">

            <small>Why Choose Us</small>

            <h2>
                More Than Just a Painting
            </h2>

            <p>
                Every artwork is created with attention, patience and
                a genuine love for artistic expression.
            </p>

        </div>


        <div class="benefit-grid">

            <div class="benefit">

                <div class="benefit-icon">
                    ✦
                </div>

                <h3>
                    Original Artwork
                </h3>

                <p>
                    Every original piece carries its own character
                    and artistic identity.
                </p>

            </div>


            <div class="benefit">

                <div class="benefit-icon">
                    ✎
                </div>

                <h3>
                    Personal Touch
                </h3>

                <p>
                    Custom artworks are created according to your
                    preferences and memories.
                </p>

            </div>


            <div class="benefit">

                <div class="benefit-icon">
                    ♢
                </div>

                <h3>
                    Thoughtful Design
                </h3>

                <p>
                    Composition, detail, colour and expression are
                    carefully considered.
                </p>

            </div>


            <div class="benefit">

                <div class="benefit-icon">
                    ♥
                </div>

                <h3>
                    Made With Passion
                </h3>

                <p>
                    Every artwork is created with patience and
                    dedication.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     HOW IT WORKS
========================= -->

<section class="how">

    <div class="section-title">

        <small>Custom Artwork</small>

        <h2>
            How It Works
        </h2>

    </div>


    <div class="steps">

        <div class="step">

            <div class="step-number">
                01
            </div>

            <h3>
                Choose Your Idea
            </h3>

            <p>
                Select an existing artwork or share your idea,
                photograph or subject for a custom drawing.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                02
            </div>

            <h3>
                Discuss the Details
            </h3>

            <p>
                Discuss the preferred style, size, colours and
                artistic requirements.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                03
            </div>

            <h3>
                Receive Your Artwork
            </h3>

            <p>
                Your artwork is carefully created and prepared
                for you to enjoy or gift to someone special.
            </p>

        </div>

    </div>

</section>


<!-- =========================
     CTA
========================= -->

<section class="cta" id="contact">

    <h2>
        Looking for Something Special?
    </h2>

    <p>
        Contact Bheema Art Gallery to ask about available artworks,
        custom drawings and current offers.
    </p>

    <a
        href="https://wa.me/919448312348"
        target="_blank"
        class="cta-btn"
    >
        Contact on WhatsApp
    </a>

</section>


<!-- =========================
     FOOTER
========================= -->

<footer>

    © <?php echo date("Y"); ?>

    <strong>Bheema Art Gallery</strong>

    · Created with passion by Athmananda

</footer>


</body>
</html>