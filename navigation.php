<!-- Navigation -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand mr-auto" id="logo" href="index.php">dB</a>
        <?php
            /*
            * If logged in, show a 'sign out' button.
            */ 
            if (isset($_SESSION['logged-in'])) {
                echo '<a class="order-md-last" href="logout.php">';
                echo '<button type="button" class="btn btn-outline-danger">';
                echo 'sign out';
                echo '</button>';
                echo '</a>';
            }  
        ?>
        <button class="navbar-toggler ml-2" type="button" data-toggle="collapse" data-target="#nav-collapse" aria-controls="nav-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="nav-collapse">
            <ul class="navbar-nav">
                <?php
                    /*
                    * If the user is logged in, show 'account' page.
                    * Otherwise, show 'sign in' and 'sign up'.
                    */ 
                    if (isset($_SESSION['logged-in'])) {
                        echo '<li class="nav-item order-md-4">';
                        echo '<a class="nav-link" href="account.php">account</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="nav-item order-md-4">';
                        echo '<a class="nav-link" href="login.php">sign in</a>';
                        echo '</li>';
                        
                        echo '<li class="nav-item order-md-5">';
                        echo '<a class="nav-link" href="register.php">sign up</a>';
                        echo '</li>';
                    } 
                ?>
                <!-- articles page -->
                <li class="nav-item order-md-2">
                    <a class="nav-link" href="articles.php?pg=1">articles</a>
                </li>
                <!-- new post page -->
                <li class="nav-item order-md-3">
                    <a class="nav-link" href="post-create.php">new post</a>
                </li>
                <!-- Search form -->
                <form class="form-inline order-md-1" action="search.php">
                    <input name="search" class="form-control" type="search" placeholder="Title or YYYY-MM-DD" aria-label="Search">
                    <button class="btn btn-outline-success mt-2 mt-md-0 mx-md-2" type="submit">search</button>
                </form>   
            </ul>
        </div>
    </div>
</nav>