<?php
// artist.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About the Artist | Athmananda H A - Bheema Art Gallery</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --black: #0d0b09;
            --black2: #15110e;
            --gold: #c9a45c;
            --gold-light: #e3c98d;
            --cream: #f5efe3;
            --cream2: #e9dfcf;
            --text: #d8d0c2;
            --muted: #9c9386;
            --line: rgba(201, 164, 92, 0.25);
        }

        body {
            background: var(--black);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
        }

        /* NAVBAR */

        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 80px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 0 7%;

            background: rgba(13, 11, 9, 0.92);
            backdrop-filter: blur(12px);

            border-bottom: 1px solid var(--line);

            z-index: 1000;
        }

        .logo {
            font-family: 'Fraunces', serif;
            font-size: 25px;
            color: var(--gold);
            text-decoration: none;
            letter-spacing: 1px;
        }

        .logo span {
            color: var(--gold);
        }

        .nav-links {
            display: flex;
            gap: 35px;
        }

        .nav-links a {
            color: var(--text);
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--gold);
        }

        /* HERO */

        .hero {
            min-height: 65vh;

            display: flex;
            align-items: center;
            justify-content: center;

            text-align: center;

            padding: 140px 20px 90px;

            background:
                radial-gradient(circle at center, rgba(201,164,92,0.12), transparent 50%),
                var(--black);
        }

        .hero-content {
            max-width: 850px;
        }

        .small-title {
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 5px;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-family: 'Fraunces', serif;
            font-size: clamp(55px, 9vw, 110px);
            font-weight: 300;
            color: var(--cream);
            line-height: 1;
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
            font-size: 17px;
        }

        /* ARTIST SECTION */

        .artist-section {
            max-width: 1200px;
            margin: auto;
            padding: 100px 7%;
        }

        .artist-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 80px;
            align-items: center;
        }

        .artist-image {
            position: relative;
            padding: 18px;
        }

        .artist-image img {
            width: 100%;
            height: min(600px, 62vw);
            min-height: 360px;
            object-fit: cover;
            display: block;

            border: 1px solid var(--gold);
            position: relative;
            z-index: 1;
        }

        .image-frame {
            position: absolute;
            inset: 0;
            border: 1px solid var(--line);
            z-index: 0;
            pointer-events: none;
        }

        .artist-text h2 {
            font-family: 'Fraunces', serif;
            font-size: 50px;
            color: var(--cream);
            font-weight: 400;
            margin-bottom: 25px;
        }

        .artist-text h2 span {
            color: var(--gold);
        }

        .artist-text p {
            color: var(--muted);
            margin-bottom: 20px;
            font-size: 15px;
        }

        .artist-signature {
            margin-top: 35px;
            font-family: 'Fraunces', serif;
            font-style: italic;
            color: var(--gold-light);
            font-size: 28px;
        }

        /* QUOTE */

        .quote {
            padding: 110px 7%;
            text-align: center;

            background: var(--black2);

            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .quote p {
            max-width: 850px;
            margin: auto;

            font-family: 'Fraunces', serif;
            font-size: clamp(28px, 4vw, 48px);
            font-weight: 300;

            color: var(--cream);
            line-height: 1.35;
        }

        .quote span {
            display: block;
            margin-top: 25px;

            font-family: 'Inter', sans-serif;
            font-size: 12px;
            letter-spacing: 4px;
            color: var(--gold);
            text-transform: uppercase;
        }

        /* ART PHILOSOPHY */

        .philosophy {
            max-width: 1200px;
            margin: auto;
            padding: 110px 7%;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 65px;
        }

        .section-heading small {
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: 11px;
        }

        .section-heading h2 {
            font-family: 'Fraunces', serif;
            font-size: 50px;
            color: var(--cream);
            font-weight: 400;
            margin-top: 12px;
        }

        .philosophy-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .card {
            padding: 40px 30px;

            border: 1px solid var(--line);
            background: rgba(255,255,255,0.02);

            transition: 0.4s;
        }

        .card:hover {
            transform: translateY(-8px);
            border-color: var(--gold);
        }

        .card-number {
            color: var(--gold);
            font-family: 'Fraunces', serif;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .card h3 {
            font-family: 'Fraunces', serif;
            color: var(--cream);
            font-size: 25px;
            margin-bottom: 15px;
        }

        .card p {
            color: var(--muted);
            font-size: 14px;
        }

        /* ABOUT ART */

        .about-art {
            background: var(--cream);
            color: #272018;
            padding: 110px 7%;
        }

        .about-art-inner {
            max-width: 1000px;
            margin: auto;
        }

        .about-art small {
            color: #8d6b32;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-size: 11px;
        }

        .about-art h2 {
            font-family: 'Fraunces', serif;
            font-size: 55px;
            font-weight: 400;
            margin: 15px 0 30px;
        }

        .about-art p {
            font-size: 16px;
            line-height: 1.9;
            margin-bottom: 20px;
            color: #62594d;
        }

        /* ART TYPES */

        .art-types {
            max-width: 1200px;
            margin: auto;
            padding: 110px 7%;
        }

        .types-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .type {
            padding: 35px;
            border-left: 2px solid var(--gold);
            background: var(--black2);
        }

        .type h3 {
            font-family: 'Fraunces', serif;
            font-size: 27px;
            color: var(--cream);
            margin-bottom: 10px;
        }

        .type p {
            color: var(--muted);
            font-size: 14px;
        }

        /* CONTACT CTA */

        .cta {
            text-align: center;
            padding: 100px 20px;
            background:
                linear-gradient(rgba(13,11,9,0.85), rgba(13,11,9,0.95)),
                url('image.png') center/cover;
        }

        .cta h2 {
            font-family: 'Fraunces', serif;
            font-size: 55px;
            color: var(--cream);
            font-weight: 400;
        }

        .cta p {
            color: var(--muted);
            margin: 15px auto 30px;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            border: 1px solid var(--gold);
            color: var(--gold-light);
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: var(--gold);
            color: var(--black);
        }

        /* FOOTER */

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

        /* MOBILE */

        @media(max-width: 800px) {

            .nav-links {
                gap: 15px;
            }

            .nav-links a {
                font-size: 12px;
            }

            .artist-grid {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .philosophy-grid {
                grid-template-columns: 1fr;
            }

            .types-grid {
                grid-template-columns: 1fr;
            }

            .artist-text h2,
            .section-heading h2,
            .about-art h2,
            .cta h2 {
                font-size: 40px;
            }
        }

        @media(max-width: 550px) {

            nav {
                padding: 0 20px;
            }

            .logo {
                font-size: 20px;
            }

            .nav-links {
                gap: 10px;
            }

            .nav-links a:nth-child(2) {
                display: none;
            }

            .hero {
                padding-top: 130px;
            }

            .hero h1 {
                font-size: 60px;
            }
        }
    </style>
</head>

<body>

<!-- NAVIGATION -->

<nav>

    <a href="index.php" class="logo">
        Bheema <span>Art Gallery</span>
    </a>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="collections.php">Collections</a>
        <a href="artist.php" class="active">Artist</a>
        <a href="index.php#contact">Contact</a>
    </div>

</nav>


<!-- HERO -->

<section class="hero">

    <div class="hero-content">

        <div class="small-title">
            The Artist Behind the Canvas
        </div>

        <h1>
            <span>Athmananda H A</span>
        </h1>

        <p>
            An artist who transforms thoughts, emotions and observations
            into meaningful expressions through colour, form and imagination.
        </p>

    </div>

</section>


<!-- ARTIST INTRODUCTION -->

<section class="artist-section">

    <div class="artist-grid">

        <div class="artist-image">

            <!-- Change image.png to your artist photo if you have one -->

            <img
                src="images/image copy.png"
                alt="Artist Athmananda H A"
                onerror="this.onerror=null;this.src='images/no-image.jpg';"
            >

            <div class="image-frame"></div>

        </div>


        <div class="artist-text">

            <div class="small-title">
                About the Artist
            </div>

            <h2>
                Meet <span>Athmananda H A</span>
            </h2>

            <p>
                Athmananda H A is an artist driven by a deep appreciation for
                creativity, beauty and human expression. Through drawings
                and paintings, every artwork becomes an opportunity to
                capture an emotion, a memory, a person or a moment in time.
            </p>

            <p>
                His artistic approach combines observation with imagination.
                From detailed drawings to expressive paintings, the focus is
                always on creating artwork that connects with the viewer
                beyond its visual appearance.
            </p>

            <p>
                For Athmananda H A, art is not simply about creating something
                beautiful. It is about telling a story without using words
                and allowing colours, lines and textures to communicate
                feelings.
            </p>

            <div class="artist-signature">
                — Athmananda H A
            </div>

        </div>

    </div>

</section>


<!-- ARTIST QUOTE -->

<section class="quote">

    <p>
        “Every drawing begins with a thought,
        and every painting carries a piece of the artist's soul.”
    </p>

    <span>
        Artistic Philosophy
    </span>

</section>


<!-- PHILOSOPHY -->

<section class="philosophy">

    <div class="section-heading">

        <small>The Creative Process</small>

        <h2>
            Art With Meaning
        </h2>

    </div>


    <div class="philosophy-grid">

        <div class="card">

            <div class="card-number">01</div>

            <h3>Observation</h3>

            <p>
                Great artwork often begins by observing the world carefully.
                People, nature, architecture, emotions and everyday moments
                can all become sources of inspiration.
            </p>

        </div>


        <div class="card">

            <div class="card-number">02</div>

            <h3>Imagination</h3>

            <p>
                Imagination transforms ordinary observations into something
                unique. It allows an artist to explore ideas, emotions and
                possibilities beyond what the eyes can immediately see.
            </p>

        </div>


        <div class="card">

            <div class="card-number">03</div>

            <h3>Expression</h3>

            <p>
                The final artwork becomes a form of communication. Colours,
                lines, shadows, textures and composition work together to
                express an idea or feeling.
            </p>

        </div>

    </div>

</section>


<!-- ABOUT ART -->

<section class="about-art">

    <div class="about-art-inner">

        <small>The Language of Creativity</small>

        <h2>
            What Makes Art Special?
        </h2>

        <p>
            Art is one of the oldest forms of human expression. Long before
            written language, people used drawings, symbols and paintings
            to communicate their experiences and stories.
        </p>

        <p>
            Drawing is the foundation of many artistic practices. Through
            simple lines and shapes, an artist can create realistic portraits,
            landscapes, objects and completely imaginary worlds.
        </p>

        <p>
            Painting expands this language by introducing colour, texture,
            light and atmosphere. A single colour can create warmth,
            peacefulness, energy or even mystery.
        </p>

        <p>
            Art therefore has no single definition. It can be realistic,
            abstract, traditional or modern. What matters most is the
            connection between the artist, the artwork and the person
            experiencing it.
        </p>

    </div>

</section>


<!-- ART STYLES -->

<section class="art-types">

    <div class="section-heading">

        <small>Exploring Different Forms</small>

        <h2>
            The World of Drawing & Painting
        </h2>

    </div>


    <div class="types-grid">

        <div class="type">

            <h3>Portrait Drawing</h3>

            <p>
                Portrait drawing focuses on capturing the character,
                expression and individuality of a person through careful
                observation of facial features and proportions.
            </p>

        </div>


        <div class="type">

            <h3>Nature & Landscapes</h3>

            <p>
                Nature provides endless inspiration. Trees, mountains,
                rivers, flowers and changing skies can be transformed
                into peaceful and expressive artworks.
            </p>

        </div>


        <div class="type">

            <h3>Traditional Art</h3>

            <p>
                Traditional art carries cultural stories and artistic
                heritage from one generation to another through familiar
                subjects, techniques and visual traditions.
            </p>

        </div>


        <div class="type">

            <h3>Creative Expression</h3>

            <p>
                Creative artworks allow the artist to move beyond reality
                and explore ideas, emotions and imagination through unique
                compositions and artistic techniques.
            </p>

        </div>

    </div>

</section>


<!-- CTA -->

<section class="cta">

    <h2>
        Discover the Art
    </h2>

    <p>
        Explore the collection and experience the world created by Athmananda H A.
    </p>

    <a href="collections.php" class="btn">
        View Collection
    </a>

</section>


<!-- FOOTER -->

<footer>

    © <?php echo date("Y"); ?>

    <strong>Bheema Art Gallery</strong>

    · Art created with passion by Athmananda H A

</footer>


</body>
</html>