<!-- Navigation -->
<nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand mr-auto" href="index.php">dB</a>
        <?php 
            if (isset($_SESSION['logged-in'])) {
                echo '<a class="order-md-last" href="logout.php">';
                echo '<button type="" class="btn btn-outline-danger">';
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
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="account.php">account</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="login.php">sign in</a>';
                        echo '</li>';
                        
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="register.php">sign up</a>';
                        echo '</li>';
                    } 
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="post-create-edit.php">new/edit post</a>
                </li>
            <!-- Search form -->
            <form class="form-inline order-md-first" action="search.php">
                <input class="form-control" type="search" placeholder="YYYY-MM-DD" aria-label="Search">
                <button class="btn btn-outline-success mt-2 mt-md-0" type="submit">search</button>
            </form>   
            </ul>
        </div>
    </div>
</nav>