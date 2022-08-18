<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <title>Bing Shapiro</title> 
        <link rel="icon" type="image/x-icon" href="/images/ben.ico">
    </head>
    <body >
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "usbw";
            $database_in_use = "test";
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $database_in_use);

            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

			$query = htmlentities($_GET['q']); 
			$sql = "SELECT id, title, transcript, link FROM podcasts WHERE transcript LIKE '%${query}%'";

            // Deal with apostrophe escape character in sql query
            $sql = str_replace("'", "''", $sql);
            $sql = substr_replace($sql, '', 71, 1);
            $sql = substr($sql, 0, -1);

			$result = $conn->query($sql);
            $results_per_page = 10;
            $num_pages = ceil($result->num_rows / $results_per_page);
       
            
        ?>
        <form class="search-bar">
            <input id="input" name="q" type="search" placeholder="search a ben shapiro quote" pattern=".{1,}" required title="1 character minimum">
            <button id="btn" type="search" onclick="search()"><img src="images/search.png"></button>
        </form>
			
        <section class="container">
			<?php 
				echo $result->num_rows . ' matches for: ' . $query;

                if(!isset($_GET['page'])) {
                    $page = 1;
                } else {
                    $page = $_GET['page'];
                }
                if($num_pages > 0) echo '<br>Page ' . $page . ' of ' . $num_pages;
                $start_sql_limit = ($page - 1) * $results_per_page;
                $sql .= " LIMIT ${start_sql_limit}, 10";

                $result = $conn->query($sql);

                $conn->close();

				if ($result->num_rows > 0) {
				  // output data of each row
                    //window.location=\'transcript.html\'
				  while($row = $result->fetch_assoc()) {
					$left_text_ptr = strpos($row["transcript"], $query) - 250;
					if($left_text_ptr < 0) {$left_text_ptr = 0;}
					echo '<div class="card" title="show full transcript and timestamps" onclick="window.location=\'transcript.php?id=' . $row['id'] . '\'">' . '<a href=' . $row["link"] . '>' . $row["title"] . '</a><br><br>' . '<p>"' . substr($row["transcript"], $left_text_ptr, 500) . '"</p>' . '</div>';
				  }
                  echo '<div class="pagination"><br>';
                  for ($page = 1; $page <= $num_pages; $page++) {
                    echo '<a href="search.php?q=' . $query . '&page=' . $page . '">' . $page . '</a> '; 
                    if ($page % 14 == 0) {
                        echo '<br><br><br>';
                    }
                  }
                  echo '</div>';
				} 
			?>
        </section>
		<script src="index.js"></script>
    </body>
</html>

