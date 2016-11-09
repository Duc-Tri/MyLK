<html>
	<head>
	<meta http-equiv="refresh" content="5">
	</head>
	<body>
	<font face='Verdana,Arial,sans-serif'>
		<?php

		include 'process_urls.php';
		
		$ip=$_SERVER['REMOTE_ADDR'];
		echo '<p>MyLK - your IP address is : ' . $ip . '</p>';
		//read_urls();
		print(html_content());

		?>
	</font>
	</body>
</html>