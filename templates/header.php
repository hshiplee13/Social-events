<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/92dbd2f74f.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/search.js"></script>
    <script src="js/cleanUp.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php session_start(); ?>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo"><b>SOCIAL EVENTS</b></a>
                <div class="search">
                    <form action="#" id="searchBar" class="search-bar">
                        <input type="text" id="searchInput" class="search-input" placeholder="Search for events, organisers or locations">
                    </form>
                    <div id="searchResults" class="search-results"></div>
                </div>
                <?php if (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] == true)): ?>
                <div class="logged-right">
                    <a href="feed.php" class="link-right">Feed</a>
                    <div class="profile-menu">
                        <a href="#" class="link-right"><?php echo $_SESSION['username']; ?></a>
                        <div class="profile-dropdown">
                            <a href="profile.php">My Profile</a>
                            <a href="inbox.php">Messages</a>
                            <a href="createEvent.php">Create Event</a>
                            <a href="signOut.php">Sign Out</a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="nav-right">
                    <a href="register.php" class="link-right">Register</a>
                    <a href="signIn.php" class="link-right">Sign In</a>
                </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>
</body>
</html>