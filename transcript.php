<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css" type="text/css">
        <title>Bing Shapiro</title> 
        <link rel="icon" type="image/x-icon" href="/images/ben.ico">
    </head>
    <body background='images/shapiro_moment.jpg'>
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

			//echo $_COOKIE['name'];
			
			$sql = "SELECT title, transcript, srt FROM podcasts WHERE id = " . $_GET['id'];
			$result = $conn->query($sql);
            $conn->close();
		?>
		<div>
			<?php 
				$row = $result->fetch_assoc();
				echo "<p>" . $row['title'] . "<br><br>" . "SRT (SubRip Subtitles): " . "<br>" . nl2br($row['srt']);
				if($row['srt'] == '') echo "No SRT file available for this video.";
				echo "<br><br>" . "Transcript: " . $row['transcript'] . "</p>";
			?>
        </div>
        <script>
			let id = sessionStorage.getItem("id");
			console.log(id);
		</script>
    </body>
</html>


