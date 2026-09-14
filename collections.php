<?php

// ======================================================
// BHEEMA ART GALLERY
// COLLECTIONS PAGE
// ======================================================

include "includes/db.php";
include "includes/image.php";


// ======================================================
// SEARCH
// ======================================================

$search = trim($_GET['search'] ?? '');


// ======================================================
// CATEGORY
// ======================================================

$category = trim($_GET['category'] ?? '');


// ======================================================
// BUILD QUERY
// ======================================================

$sql = "SELECT * FROM paintings WHERE 1=1";

$params = [];
$types = "";


// ======================================================
// SEARCH FILTER
// ======================================================

if ($search !== '') {

    $sql .= " AND (
        title LIKE ?
        OR category LIKE ?
        OR description LIKE ?
    )";

    $searchValue = "%" . $search . "%";

    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;

    $types .= "sss";
}


// ======================================================
// CATEGORY FILTER
// ======================================================

if ($category !== '') {

    $sql .= " AND category = ?";

    $params[] = $category;

    $types .= "s";
}


// ======================================================
// SORT
// ======================================================

$sql .= " ORDER BY created_at DESC";


// ======================================================
// PREPARE QUERY
// ======================================================

$stmt = $conn->prepare($sql);


if (!$stmt) {

    die("Database query error: " . $conn->error);

}


if (!empty($params)) {

    $stmt->bind_param(
        $types,
        ...$params
    );

}


$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Collections | Bheema Art Gallery
</title>


<!-- =====================================================
     GOOGLE FONTS
===================================================== -->

<link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet"
>


<style>

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

    font-family: 'Poppins', sans-serif;

    background: #faf7f2;

    color: #29201a;

}


/* =====================================================
   NAVBAR
===================================================== */

nav {

    width: 100%;

    height: 78px;

    padding: 0 7%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    position: fixed;

    top: 0;

    left: 0;

    z-index: 1000;

    background: rgba(250,247,242,.97);

    border-bottom: 1px solid #e4d9cd;

    backdrop-filter: blur(10px);

}


.logo {

    font-family: 'Cormorant Garamond', serif;

    font-size: 31px;

    font-weight: 700;

    text-decoration: none;

    color: #704527;

}


.logo span {

    display: block;

    text-align: center;

    font-family: Poppins, sans-serif;

    font-size: 8px;

    letter-spacing: 4px;

    color: #9b7657;

    margin-top: -6px;

}


.nav-links {

    display: flex;

    gap: 30px;

    list-style: none;

}


.nav-links a {

    color: #49372b;

    text-decoration: none;

    font-size: 13px;

    transition: .3s;

}


.nav-links a:hover {

    color: #a06d43;

}


.nav-contact {

    padding: 10px 20px;

    border: 1px solid #704527;

}


/* =====================================================
   HERO
===================================================== */

.collection-hero {

    padding: 155px 7% 80px;

    text-align: center;

    background:

        linear-gradient(
            rgba(250,247,242,.92),
            rgba(250,247,242,.92)
        ),

        url("images/gallery.jpg");

    background-size: cover;

    background-position: center;

}


.collection-hero small {

    color: #a06d43;

    letter-spacing: 5px;

    font-size: 11px;

    text-transform: uppercase;

}


.collection-hero h1 {

    font-family: 'Cormorant Garamond', serif;

    font-size: clamp(55px, 7vw, 80px);

    color: #35261d;

    margin-top: 10px;

}


.collection-hero p {

    max-width: 650px;

    margin: 15px auto 0;

    color: #716860;

    font-size: 14px;

    line-height: 1.8;

}


/* =====================================================
   STORE
===================================================== */

.store {

    padding: 70px 7% 110px;

}


/* =====================================================
   SEARCH AREA
===================================================== */

.search-area {

    display: flex;

    justify-content: center;

    margin-bottom: 35px;

}


.search-box {

    display: flex;

    background: white;

    border: 1px solid #ded5ca;

    width: 500px;

}


.search-box input {

    width: 100%;

    padding: 14px 17px;

    border: none;

    outline: none;

    font-family: Poppins;

    font-size: 12px;

    color: #49372b;

}


.search-box button {

    padding: 14px 24px;

    background: #704527;

    color: white;

    border: none;

    cursor: pointer;

    font-family: Poppins;

    font-size: 12px;

    transition: .3s;

}


.search-box button:hover {

    background: #4d2e1c;

}


/* =====================================================
   CATEGORY TITLE
===================================================== */

.category-heading {

    text-align: center;

    margin-bottom: 25px;

}


.category-heading small {

    color: #a06d43;

    text-transform: uppercase;

    letter-spacing: 3px;

    font-size: 10px;

}


.category-heading h2 {

    font-family: 'Cormorant Garamond', serif;

    font-size: 38px;

    font-weight: 600;

    color: #35261d;

    margin-top: 5px;

}


/* =====================================================
   CATEGORY FILTER BUTTONS
===================================================== */

.category-filters {

    display: flex;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

    gap: 10px;

    margin-bottom: 65px;

}


.category-btn {

    display: inline-block;

    text-decoration: none;

    padding: 11px 18px;

    border: 1px solid #d8cabe;

    background: #fff;

    color: #624c3c;

    font-family: Poppins, sans-serif;

    font-size: 11px;

    transition: all .3s ease;

}


.category-btn:hover {

    border-color: #704527;

    background: #704527;

    color: white;

    transform: translateY(-2px);

}


.category-btn.active {

    background: #704527;

    border-color: #704527;

    color: white;

}


/* =====================================================
   CURRENT FILTER
===================================================== */

.current-filter {

    text-align: center;

    margin-bottom: 30px;

    color: #776b61;

    font-size: 12px;

}


.current-filter strong {

    color: #704527;

}


/* =====================================================
   PAINTING GRID
===================================================== */

.painting-grid {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 32px;

}


/* =====================================================
   PAINTING CARD
===================================================== */

.painting-card {

    background: white;

    overflow: hidden;

    box-shadow: 0 7px 30px rgba(0,0,0,.06);

    transition: .3s;

}


.painting-card:hover {

    transform: translateY(-7px);

    box-shadow: 0 18px 45px rgba(0,0,0,.12);

}


/* =====================================================
   IMAGE
===================================================== */

.painting-image {

    width: 100%;

    height: 450px;

    display: flex;

    align-items: center;

    justify-content: center;

    overflow: hidden;

    background: #f4f0ea;

}


.painting-image img {

    width: 100%;

    height: 100%;

    object-fit: contain;

    object-position: center;

    display: block;

    background: #f4f0ea;

    transition: transform .5s ease;

}


.painting-card:hover .painting-image img {

    transform: scale(1.03);

}


/* =====================================================
   INFORMATION
===================================================== */

.painting-info {

    padding: 23px;

}


/* =====================================================
   CATEGORY
===================================================== */

.category {

    color: #a06d43;

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: 2px;

}


/* =====================================================
   ARTIST SIGNATURE
===================================================== */

.art-by {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 5px;

    margin-top: 18px;

    margin-bottom: 0;

    font-family: 'Cormorant Garamond', serif;

    font-size: 14px;

    font-style: italic;

    letter-spacing: .8px;

    color: #9b7657;

    opacity: .95;

    text-align: right;

}


/* Small decorative line */

.art-by::before {

    content: "";

    width: 22px;

    height: 1px;

    background: #c8a98a;

    display: inline-block;

}


/* Artist name */

.art-by span {

    color: #704527;

    font-weight: 600;

    font-style: italic;

    letter-spacing: 1px;

}


/* =====================================================
   TITLE
===================================================== */

.painting-info h2 {

    font-family: 'Cormorant Garamond', serif;

    font-size: 32px;

    color: #35261d;

    margin-top: 5px;

}


/* =====================================================
   DESCRIPTION
===================================================== */

.description {

    color: #777;

    font-size: 12px;

    line-height: 1.7;

    margin-top: 10px;

}


/* =====================================================
   PRICE
===================================================== */

.price {

    display: block;

    color: #704527;

    font-size: 15px;

    font-weight: 600;

    margin-top: 15px;

}


/* =====================================================
   BUTTONS
===================================================== */

.buttons {

    display: flex;

    gap: 10px;

    margin-top: 18px;

}


.btn {

    display: inline-block;

    text-decoration: none;

    padding: 10px 17px;

    font-size: 11px;

    transition: .3s;

}


.btn-view {

    border: 1px solid #704527;

    color: #704527;

}


.btn-view:hover {

    background: #704527;

    color: white;

}


.btn-whatsapp {

    background: #704527;

    color: white;

}


.btn-whatsapp:hover {

    background: #4d2e1c;

}


/* =====================================================
   NO RESULTS
===================================================== */

.no-results {

    grid-column: 1 / -1;

    text-align: center;

    background: white;

    padding: 80px 20px;

}


.no-results h2 {

    font-family: 'Cormorant Garamond', serif;

    font-size: 35px;

    color: #35261d;

}


.no-results p {

    color: #777;

    margin-top: 10px;

}


/* =====================================================
   FOOTER
===================================================== */

footer {

    background: #241812;

    color: #c9beb6;

    padding: 60px 7% 25px;

}


.footer-grid {

    display: grid;

    grid-template-columns: 2fr 1fr 1fr 1fr;

    gap: 50px;

}


footer h3 {

    font-family: 'Cormorant Garamond', serif;

    font-size: 32px;

    color: white;

}


footer h4 {

    color: white;

    font-size: 13px;

    margin-bottom: 15px;

}


footer p,
footer a {

    color: #a99d95;

    font-size: 12px;

    line-height: 2;

    text-decoration: none;

}


footer a:hover {

    color: white;

}


.copyright {

    border-top: 1px solid #44352e;

    margin-top: 45px;

    padding-top: 20px;

    text-align: center;

    font-size: 11px;

    color: #81756e;

}


/* =====================================================
   TABLET
===================================================== */

@media(max-width: 950px) {

    .painting-grid {

        grid-template-columns: repeat(2, 1fr);

    }


    .footer-grid {

        grid-template-columns: repeat(2, 1fr);

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 650px) {

    nav {

        padding: 0 5%;

    }


    .nav-links {

        display: none;

    }


    .collection-hero {

        padding: 130px 5% 70px;

    }


    .store {

        padding: 60px 5% 80px;

    }


    .painting-grid {

        grid-template-columns: 1fr;

    }


    .painting-image {

        height: 450px;

    }


    .search-box {

        width: 100%;

    }


    .category-filters {

        gap: 8px;

        margin-bottom: 45px;

    }


    .category-btn {

        font-size: 10px;

        padding: 9px 12px;

    }


    .footer-grid {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     NAVIGATION
===================================================== -->

<nav>

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
            <a href="artist.php">
                Artist
            </a>
        </li>

        <li>
            <a href="offers.php">
                Offers
            </a>
        </li>

        <li>
            <a href="#contact" class="nav-contact">
                Contact
            </a>
        </li>

    </ul>

</nav>


<!-- =====================================================
     HERO
===================================================== -->

<section class="collection-hero">

    <small>
        Bheema Art Gallery
    </small>

    <h1>
        Our Collections
    </h1>

    <p>

        Explore paintings, drawings, sculptures and artistic
        creations carefully selected from the world of art.

    </p>

</section>


<!-- =====================================================
     STORE
===================================================== -->

<section class="store">


<!-- =====================================================
     SEARCH
===================================================== -->

<div class="search-area">

    <form
        method="GET"
        class="search-box"
    >

        <input
            type="text"
            name="search"
            placeholder="Search paintings, sculptures, portraits..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <?php if ($category !== '') { ?>

            <input
                type="hidden"
                name="category"
                value="<?php echo htmlspecialchars($category); ?>"
            >

        <?php } ?>

        <button type="submit">
            Search
        </button>

    </form>

</div>


<!-- =====================================================
     CATEGORY HEADING
===================================================== -->

<div class="category-heading">

    <small>
        Explore By Style
    </small>

    <h2>
        Choose Your Art
    </h2>

</div>


<!-- =====================================================
     CATEGORY FILTERS
===================================================== -->

<div class="category-filters">


    <!-- ALL -->

    <a
        href="collections.php<?php echo ($search !== '') ? '?search=' . urlencode($search) : ''; ?>"
        class="category-btn <?php echo ($category === '') ? 'active' : ''; ?>"
    >
        All Art
    </a>


    <!-- TRADITIONAL -->

    <a
        href="collections.php?category=Traditional%20Art"
        class="category-btn <?php echo ($category === 'Traditional Art') ? 'active' : ''; ?>"
    >
        🎨 Traditional Art
    </a>


    <!-- SCULPTURE -->

    <a
        href="collections.php?category=Sculpture"
        class="category-btn <?php echo ($category === 'Sculpture') ? 'active' : ''; ?>"
    >
        🗿 Sculpture
    </a>


    <!-- MODERN -->

    <a
        href="collections.php?category=Modern%20Art"
        class="category-btn <?php echo ($category === 'Modern Art') ? 'active' : ''; ?>"
    >
        🖼️ Modern Art
    </a>


    <!-- PORTRAIT -->

    <a
        href="collections.php?category=Portrait"
        class="category-btn <?php echo ($category === 'Portrait') ? 'active' : ''; ?>"
    >
        👤 Portrait
    </a>


    <!-- NATURE -->

    <a
        href="collections.php?category=Nature"
        class="category-btn <?php echo ($category === 'Nature') ? 'active' : ''; ?>"
    >
        🌿 Nature
    </a>


    <!-- SPIRITUAL -->

    <a
        href="collections.php?category=Spiritual"
        class="category-btn <?php echo ($category === 'Spiritual') ? 'active' : ''; ?>"
    >
        ✨ Spiritual
    </a>


    <!-- ABSTRACT -->

    <a
        href="collections.php?category=Abstract"
        class="category-btn <?php echo ($category === 'Abstract') ? 'active' : ''; ?>"
    >
        ◯ Abstract
    </a>


    <!-- SKETCHES -->

    <a
        href="collections.php?category=Sketches%20%26%20Drawings"
        class="category-btn <?php echo ($category === 'Sketches & Drawings') ? 'active' : ''; ?>"
    >
        ✏️ Sketches & Drawings
    </a>


    <!-- CANVAS -->

    <a
        href="collections.php?category=Canvas%20Art"
        class="category-btn <?php echo ($category === 'Canvas Art') ? 'active' : ''; ?>"
    >
        🖌️ Canvas Art
    </a>


    <!-- WATERCOLOR -->

    <a
        href="collections.php?category=Watercolor"
        class="category-btn <?php echo ($category === 'Watercolor') ? 'active' : ''; ?>"
    >
        💧 Watercolor
    </a>


    <!-- OIL PAINTING -->

    <a
        href="collections.php?category=Oil%20Painting"
        class="category-btn <?php echo ($category === 'Oil Painting') ? 'active' : ''; ?>"
    >
        🖼️ Oil Painting
    </a>


    <!-- ACRYLIC -->

    <a
        href="collections.php?category=Acrylic%20Painting"
        class="category-btn <?php echo ($category === 'Acrylic Painting') ? 'active' : ''; ?>"
    >
        🎨 Acrylic Painting
    </a>


    <!-- INDIAN ART -->

    <a
        href="collections.php?category=Indian%20Art"
        class="category-btn <?php echo ($category === 'Indian Art') ? 'active' : ''; ?>"
    >
        🇮🇳 Indian Art
    </a>


    <!-- FOLK ART -->

    <a
        href="collections.php?category=Folk%20Art"
        class="category-btn <?php echo ($category === 'Folk Art') ? 'active' : ''; ?>"
    >
        🪷 Folk Art
    </a>


    <!-- OTHER -->

    <a
        href="collections.php?category=Other"
        class="category-btn <?php echo ($category === 'Other') ? 'active' : ''; ?>"
    >
        Other
    </a>


</div>


<!-- =====================================================
     CURRENT FILTER
===================================================== -->

<?php if ($category !== '') { ?>

<div class="current-filter">

    Showing artworks from:

    <strong>
        <?php echo htmlspecialchars($category); ?>
    </strong>

    &nbsp; | &nbsp;

    <a
        href="collections.php"
        style="color:#704527;text-decoration:none;"
    >
        Clear Filter
    </a>

</div>

<?php } ?>


<!-- =====================================================
     PAINTING GRID
===================================================== -->

<div class="painting-grid">


<?php

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $imagePath = getPaintingImage(
            $row['image']
        );

?>


<!-- =====================================================
     PAINTING CARD
===================================================== -->

<div class="painting-card">


    <!-- =================================================
         IMAGE
    ================================================== -->

    <div class="painting-image">

        <img
            src="<?php echo htmlspecialchars($imagePath); ?>"
            alt="<?php echo htmlspecialchars($row['title']); ?>"
            loading="lazy"
            onerror="this.onerror=null;this.src='images/no-image.jpg';"
        >

    </div>


    <!-- =================================================
         INFORMATION
    ================================================== -->

    <div class="painting-info">


        <!-- CATEGORY -->

        <div class="category">

            <?php

            echo htmlspecialchars(
                $row['category']
            );

            ?>

        </div>


        <!-- =================================================
             TITLE
        ================================================== -->

        <h2>

            <?php

            echo htmlspecialchars(
                $row['title']
            );

            ?>

        </h2>


        <!-- =================================================
             DESCRIPTION
        ================================================== -->

        <?php

        if (!empty($row['description'])) {

        ?>

        <p class="description">

            <?php

            echo htmlspecialchars(
                $row['description']
            );

            ?>

        </p>

        <?php

        }

        ?>


        <!-- =================================================
             PRICE
        ================================================== -->

        <?php

        if ((float)$row['price'] > 0) {

        ?>

        <span class="price">

            ₹<?php

            echo number_format(
                (float)$row['price'],
                2
            );

            ?>

        </span>

        <?php

        } else {

        ?>

        <span class="price">

            Contact for Price

        </span>

        <?php

        }

        ?>


        <!-- =================================================
             BUTTONS
        ================================================== -->

        <div class="buttons">


            <!-- VIEW ARTWORK -->

            <a
                href="painting.php?id=<?php echo (int)$row['id']; ?>"
                class="btn btn-view"
            >

                View Artwork

            </a>


            <!-- WHATSAPP -->

            <a
                href="https://wa.me/919448312348?text=<?php echo urlencode(
                    'Hello, I am interested in the artwork: ' .
                    $row['title'] .
                    ' (' .
                    $row['category'] .
                    ')'
                ); ?>"
                target="_blank"
                class="btn btn-whatsapp"
            >

                Enquire

            </a>


        </div>


        <!-- =================================================
             ARTIST NAME (bottom right of card)
        ================================================== -->

        <div class="art-by">

            Art by

            <span>
                Athmananda H A
            </span>

        </div>


    </div>


</div>


<?php

    }

}

else {

?>


<!-- =====================================================
     NO RESULTS
===================================================== -->

<div class="no-results">

    <h2>
        No Artworks Found
    </h2>

    <p>

        We couldn't find any artwork matching your search
        or selected category.

    </p>

    <br>

    <a
        href="collections.php"
        class="btn btn-view"
    >

        View All Artworks

    </a>

</div>


<?php

}

?>


</div>


</section>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer id="contact">


<div class="footer-grid">


<!-- =================================================
     ABOUT
================================================== -->

<div>

    <h3>
        Bheema
    </h3>

    <p>
        Art Gallery
    </p>

    <p>
        Paintings • Sculpture • Drawing • Creativity
    </p>

</div>


<!-- =================================================
     EXPLORE
================================================== -->

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
        <a href="artist.php">
            Artist
        </a>
    </p>

    <p>
        <a href="offers.php">
            Offers
        </a>
    </p>

</div>


<!-- =================================================
     CATEGORIES
================================================== -->

<div>

    <h4>
        Art Categories
    </h4>

    <p>
        <a href="collections.php?category=Traditional%20Art">
            Traditional Art
        </a>
    </p>

    <p>
        <a href="collections.php?category=Modern%20Art">
            Modern Art
        </a>
    </p>

    <p>
        <a href="collections.php?category=Portrait">
            Portrait
        </a>
    </p>

    <p>
        <a href="collections.php?category=Nature">
            Nature
        </a>
    </p>

</div>


<!-- =================================================
     CONTACT
================================================== -->

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


<!-- =================================================
     COPYRIGHT
================================================== -->

<div class="copyright">

    © <?php echo date("Y"); ?>

    Bheema Art Gallery.

    All Rights Reserved.

</div>


</footer>


</body>

</html>