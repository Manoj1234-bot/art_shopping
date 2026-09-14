<?php

// ======================================================
// BHEEMA ART GALLERY — ADMIN INQUIRIES
// File: admin/inquiry.php
// ======================================================

session_start();


// ======================================================
// ADMIN AUTHENTICATION
// ======================================================

if (empty($_SESSION["is_admin"])) {

    header("Location: admin_login.php");

    exit;
}


// ======================================================
// DATABASE CONNECTION
// admin/ -> ../includes/db.php
// ======================================================

require_once __DIR__ . "/../includes/db.php";


$inquiries = [];

$dbError = false;

$search = trim($_GET["search"] ?? "");


// ======================================================
// DELETE INQUIRY
// ======================================================

if (
    isset($_GET["delete"]) &&
    ctype_digit($_GET["delete"])
) {

    $deleteId = (int)$_GET["delete"];


    if ($deleteId > 0) {

        $stmt = $conn->prepare(
            "DELETE FROM inquiries WHERE id = ?"
        );


        if ($stmt) {

            $stmt->bind_param("i", $deleteId);

            $stmt->execute();

            $stmt->close();
        }
    }


    // Redirect after delete
    header("Location: inquiry.php");

    exit;
}


// ======================================================
// LOAD INQUIRIES
// ======================================================

if ($conn) {


    // --------------------------------------------------
    // SEARCH
    // --------------------------------------------------

    if ($search !== "") {


        $stmt = $conn->prepare("
            SELECT
                id,
                name,
                email,
                phone,
                message,
                created_at
            FROM inquiries
            WHERE
                name LIKE ?
                OR email LIKE ?
                OR phone LIKE ?
                OR message LIKE ?
            ORDER BY created_at DESC
        ");


        if ($stmt) {

            $searchValue = "%" . $search . "%";


            $stmt->bind_param(
                "ssss",
                $searchValue,
                $searchValue,
                $searchValue,
                $searchValue
            );


            $stmt->execute();


            $result = $stmt->get_result();


            if ($result) {

                while ($row = $result->fetch_assoc()) {

                    $inquiries[] = $row;
                }

            } else {

                $dbError = true;
            }


            $stmt->close();


        } else {

            $dbError = true;
        }


    } else {


        // --------------------------------------------------
        // ALL INQUIRIES
        // --------------------------------------------------

        $result = $conn->query("
            SELECT
                id,
                name,
                email,
                phone,
                message,
                created_at
            FROM inquiries
            ORDER BY created_at DESC
        ");


        if ($result) {

            while ($row = $result->fetch_assoc()) {

                $inquiries[] = $row;
            }

        } else {

            $dbError = true;
        }
    }


} else {

    $dbError = true;
}


// ======================================================
// TOTAL INQUIRIES
// ======================================================

$totalInquiries = 0;


if ($conn) {

    $countResult = $conn->query(
        "SELECT COUNT(*) AS total FROM inquiries"
    );


    if ($countResult) {

        $countRow = $countResult->fetch_assoc();

        $totalInquiries =
            (int)$countRow["total"];
    }
}

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
Inquiries | Bheema Art Gallery Admin
</title>


<!-- ==================================================
     GOOGLE FONTS
================================================== -->

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet"
>


<style>

/* ==================================================
   RESET
================================================== */

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;
}


/* ==================================================
   BODY
================================================== */

body {

    min-height: 100vh;

    background: #f6f1e6;

    color: #241c15;

    font-family: 'Inter', sans-serif;
}


/* ==================================================
   HEADER
================================================== */

header {

    background: #0e0c0a;

    padding: 20px 5%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    border-bottom: 1px solid rgba(201,162,75,.25);
}


.header-left {

    display: flex;

    align-items: center;

    gap: 18px;
}


header h1 {

    font-family: 'Fraunces', serif;

    font-style: italic;

    font-weight: 500;

    font-size: 25px;

    color: #e8cf95;
}


.admin-label {

    color: #8a713a;

    font-size: 9px;

    text-transform: uppercase;

    letter-spacing: 2px;
}


.logout {

    color: #b7ab97;

    text-decoration: none;

    font-size: 11px;

    letter-spacing: .7px;

    border: 1px solid #8a713a;

    padding: 9px 16px;

    transition: .3s;
}


.logout:hover {

    background: #c9a24b;

    color: #0e0c0a;

    border-color: #c9a24b;
}


/* ==================================================
   MAIN
================================================== */

main {

    width: 90%;

    max-width: 1400px;

    margin: 0 auto;

    padding: 42px 0 80px;
}


/* ==================================================
   PAGE TOP
================================================== */

.page-top {

    display: flex;

    justify-content: space-between;

    align-items: flex-end;

    gap: 25px;

    margin-bottom: 28px;
}


.page-title h2 {

    font-family: 'Fraunces', serif;

    font-size: 40px;

    font-weight: 500;

    color: #241c15;
}


.page-title p {

    margin-top: 7px;

    color: #6d6252;

    font-size: 13px;
}


/* ==================================================
   STAT
================================================== */

.stat {

    min-width: 160px;

    background: #fffdf8;

    border: 1px solid #efe6d3;

    padding: 15px 20px;

    box-shadow:
        0 6px 20px rgba(36,28,21,.05);
}


.stat-label {

    display: block;

    color: #8a713a;

    font-size: 9px;

    letter-spacing: 1.5px;

    text-transform: uppercase;
}


.stat-number {

    display: block;

    color: #5c1c26;

    font-family: 'Fraunces', serif;

    font-size: 30px;

    margin-top: 4px;
}


/* ==================================================
   SEARCH BOX
================================================== */

.search-box {

    background: #fffdf8;

    border: 1px solid #efe6d3;

    padding: 15px;

    margin-bottom: 22px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;
}


.search-form {

    width: 100%;

    max-width: 650px;

    display: flex;

    gap: 8px;
}


.search-input {

    flex: 1;

    height: 44px;

    border: 1px solid #ddd3c1;

    background: #faf7f0;

    padding: 0 14px;

    color: #241c15;

    font-family: 'Inter', sans-serif;

    font-size: 13px;

    outline: none;
}


.search-input:focus {

    border-color: #c9a24b;

    box-shadow:
        0 0 0 3px rgba(201,162,75,.1);
}


.search-button {

    height: 44px;

    padding: 0 20px;

    border: 1px solid #5c1c26;

    background: #5c1c26;

    color: white;

    cursor: pointer;

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: 1px;
}


.search-button:hover {

    background: #3f1119;
}


.clear {

    color: #8a713a;

    font-size: 11px;

    text-decoration: none;

    white-space: nowrap;
}


.clear:hover {

    text-decoration: underline;
}


/* ==================================================
   ERROR
================================================== */

.db-error {

    background: #fffdf8;

    border: 1px solid #5c1c26;

    color: #5c1c26;

    padding: 45px 25px;

    text-align: center;

    line-height: 1.7;
}


.db-error code {

    background: #f0e7d7;

    padding: 2px 5px;
}


/* ==================================================
   EMPTY
================================================== */

.empty {

    background: #fffdf8;

    border: 1px dashed #8a713a;

    padding: 70px 20px;

    text-align: center;

    color: #6d6252;
}


.empty h3 {

    font-family: 'Fraunces', serif;

    font-size: 30px;

    font-style: italic;

    color: #241c15;

    margin-bottom: 8px;
}


/* ==================================================
   RESULT COUNT
================================================== */

.result-count {

    margin-bottom: 12px;

    color: #6d6252;

    font-size: 12px;
}


/* ==================================================
   TABLE WRAPPER
================================================== */

.table-wrapper {

    overflow-x: auto;

    background: #fffdf8;

    box-shadow:
        0 8px 26px rgba(36,28,21,.08);
}


/* ==================================================
   TABLE
================================================== */

table {

    width: 100%;

    min-width: 1050px;

    border-collapse: collapse;
}


thead th {

    padding: 15px 18px;

    text-align: left;

    white-space: nowrap;

    color: #8a713a;

    background: #fffdf8;

    border-bottom: 1px solid #efe6d3;

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: 1.5px;
}


tbody td {

    padding: 17px 18px;

    border-bottom: 1px solid #efe6d3;

    font-size: 13px;

    vertical-align: top;
}


tbody tr:hover {

    background: #f6f1e6;
}


/* ==================================================
   NAME
================================================== */

.customer-name {

    color: #241c15;

    font-weight: 600;
}


/* ==================================================
   EMAIL / PHONE
================================================== */

.mail-link,
.tel-link {

    color: #5c1c26;

    text-decoration: none;
}


.mail-link:hover,
.tel-link:hover {

    text-decoration: underline;
}


.mail-link {

    word-break: break-all;
}


/* ==================================================
   MESSAGE
================================================== */

.message-cell {

    max-width: 350px;

    min-width: 250px;

    color: #6d6252;

    line-height: 1.6;

    word-break: break-word;
}


/* ==================================================
   DATE
================================================== */

.date-cell {

    color: #6d6252;

    font-size: 12px;

    white-space: nowrap;
}


/* ==================================================
   NEW BADGE
================================================== */

.new-badge {

    display: inline-block;

    margin-left: 6px;

    padding: 3px 6px;

    background: #5c1c26;

    color: white;

    font-size: 7px;

    text-transform: uppercase;

    letter-spacing: 1px;

    vertical-align: middle;
}


/* ==================================================
   DELETE
================================================== */

.delete-link {

    color: #a89c8a;

    text-decoration: none;

    text-transform: uppercase;

    font-size: 9px;

    letter-spacing: 1px;

    white-space: nowrap;
}


.delete-link:hover {

    color: #5c1c26;
}


/* ==================================================
   MOBILE
================================================== */

@media(max-width: 800px) {

    .page-top {

        flex-direction: column;

        align-items: flex-start;
    }


    .stat {

        width: 100%;
    }


    .search-box {

        flex-direction: column;

        align-items: stretch;
    }


    .search-form {

        max-width: none;
    }

}


@media(max-width: 500px) {

    header {

        padding: 18px 5%;
    }


    .header-left {

        gap: 8px;
    }


    header h1 {

        font-size: 20px;
    }


    .admin-label {

        display: none;
    }


    .logout {

        padding: 8px 11px;

        font-size: 9px;
    }


    main {

        width: 92%;

        padding-top: 30px;
    }


    .page-title h2 {

        font-size: 32px;
    }


    .search-form {

        flex-direction: column;
    }


    .search-button {

        width: 100%;
    }

}

</style>

</head>


<body>


<!-- ==================================================
     HEADER
================================================== -->

<header>


    <div class="header-left">

        <h1>
            Bheema — Inquiries
        </h1>

        <span class="admin-label">
            Admin Dashboard
        </span>

    </div>


    <a
        href="admin_logout.php"
        class="logout"
    >
        Log Out
    </a>


</header>


<!-- ==================================================
     MAIN
================================================== -->

<main>


    <!-- ==================================================
         PAGE HEADER
    ================================================== -->

    <div class="page-top">


        <div class="page-title">

            <h2>
                Customer Inquiries
            </h2>

            <p>
                Messages submitted by visitors from your gallery website.
            </p>

        </div>


        <div class="stat">

            <span class="stat-label">
                Total Inquiries
            </span>

            <span class="stat-number">
                <?php echo $totalInquiries; ?>
            </span>

        </div>


    </div>


    <!-- ==================================================
         SEARCH
    ================================================== -->

    <div class="search-box">


        <form
            method="GET"
            action="inquiry.php"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Search name, email, phone or message..."
                value="<?php echo htmlspecialchars($search); ?>"
            >


            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>

        </form>


        <?php if ($search !== "") { ?>

            <a
                href="inquiry.php"
                class="clear"
            >
                Clear Search
            </a>

        <?php } ?>


    </div>


    <?php if ($dbError) { ?>


        <!-- ==================================================
             DATABASE ERROR
        ================================================== -->

        <div class="db-error">

            <strong>
                Couldn't load inquiries.
            </strong>

            <br><br>

            Check:

            <br>

            <code>
                includes/db.php
            </code>

            <br>

            Database:

            <code>
                athmananda_gallery
            </code>

            <br>

            Table:

            <code>
                inquiries
            </code>

        </div>


    <?php } elseif (empty($inquiries)) { ?>


        <!-- ==================================================
             NO INQUIRIES
        ================================================== -->

        <div class="empty">


            <?php if ($search !== "") { ?>


                <h3>
                    No Matching Inquiries
                </h3>

                <p>
                    No inquiry was found for
                    "<?php echo htmlspecialchars($search); ?>"
                </p>


            <?php } else { ?>


                <h3>
                    No Inquiries Yet
                </h3>

                <p>
                    Customer submissions from the website will appear here.
                </p>


            <?php } ?>


        </div>


    <?php } else { ?>


        <!-- ==================================================
             RESULT COUNT
        ================================================== -->

        <div class="result-count">

            Showing

            <strong>
                <?php echo count($inquiries); ?>
            </strong>

            <?php
            echo count($inquiries) === 1
                ? "inquiry"
                : "inquiries";
            ?>


            <?php if ($search !== "") { ?>

                for

                <strong>
                    "<?php echo htmlspecialchars($search); ?>"
                </strong>

            <?php } ?>

        </div>


        <!-- ==================================================
             TABLE
        ================================================== -->

        <div class="table-wrapper">


            <table>


                <thead>

                    <tr>

                        <th>
                            Received
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Message
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php foreach ($inquiries as $row) { ?>


                    <?php

                    // --------------------------------------
                    // NEW = within last 24 hours
                    // --------------------------------------

                    $isNew = false;

                    if (!empty($row["created_at"])) {

                        $created =
                            strtotime($row["created_at"]);

                        if (
                            $created !== false &&
                            $created >= strtotime("-24 hours")
                        ) {

                            $isNew = true;
                        }
                    }

                    ?>


                    <tr>


                        <!-- RECEIVED -->

                        <td class="date-cell">

                            <?php

                            echo htmlspecialchars(
                                date(
                                    "d M Y, g:i a",
                                    strtotime($row["created_at"])
                                )
                            );

                            ?>


                            <?php if ($isNew) { ?>

                                <span class="new-badge">
                                    New
                                </span>

                            <?php } ?>

                        </td>


                        <!-- NAME -->

                        <td>

                            <span class="customer-name">

                                <?php

                                echo htmlspecialchars(
                                    $row["name"] ?? ""
                                );

                                ?>

                            </span>

                        </td>


                        <!-- EMAIL -->

                        <td>

                            <?php if (!empty($row["email"])) { ?>

                                <a
                                    href="mailto:<?php echo htmlspecialchars($row["email"]); ?>"
                                    class="mail-link"
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        $row["email"]
                                    );

                                    ?>

                                </a>

                            <?php } else { ?>

                                —

                            <?php } ?>

                        </td>


                        <!-- PHONE -->

                        <td>

                            <?php if (!empty($row["phone"])) { ?>

                                <a
                                    href="tel:<?php echo htmlspecialchars($row["phone"]); ?>"
                                    class="tel-link"
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        $row["phone"]
                                    );

                                    ?>

                                </a>

                            <?php } else { ?>

                                —

                            <?php } ?>

                        </td>


                        <!-- MESSAGE -->

                        <td class="message-cell">

                            <?php

                            $message =
                                trim($row["message"] ?? "");


                            if ($message !== "") {

                                echo nl2br(
                                    htmlspecialchars($message)
                                );

                            } else {

                                echo "No message provided.";
                            }

                            ?>

                        </td>


                        <!-- DELETE -->

                        <td>

                            <a
                                href="inquiry.php?delete=<?php echo (int)$row["id"]; ?>"
                                class="delete-link"
                                onclick="return confirm('Are you sure you want to delete this inquiry?');"
                            >
                                Delete
                            </a>

                        </td>


                    </tr>


                <?php } ?>


                </tbody>


            </table>


        </div>


    <?php } ?>


</main>


</body>

</html>