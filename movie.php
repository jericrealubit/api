<!DOCTYPE html>
<html>
<head>
	<title>Movie Search API</title>
	<style type="text/css">
		#container{
			padding: 30px 50px;			
			width: 715px;
		}
		#mform{
			padding: 10px;
		}
		
		.table {
		}
		.tr {
			overflow: hidden;
		}
		.tr:last-child .td {
			border-bottom: 1px solid #333;
		}

		.tr:nth-of-type(even) {
			background: #e0e0e0;	
		}

		.td:nth-child(1),
		.td:nth-child(2),
		.td:nth-child(3){
			float: left;
			width: 500px;
			height: 30px;
			border: 1px solid #333;
			border-bottom: 0;
			padding: 8px 0 0 20px;
		}
		.td:nth-child(2){
			width: 50px;
			border-left: 0;
			margin: auto;
		}
		.td:nth-child(3){
			width: 100px;
			border-left: 0;
			margin: auto;
		}

		.clear {
			clear: both;
		}
		.th{
			font-weight: bold;			
		}
		
	</style>
</head>
<body>
<?php
$mtitle = (isset($_POST['mtitle']))? $_POST['mtitle']: 'red';

$apikey = 's6hd5x8bct5fmsraf7ppvsjk';
$q = urlencode($mtitle); // make sure to url encode an query parameters

// construct the query with our apikey and the query we want to make
$endpoint = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=' . $apikey . '&q=' . $q;

// setup curl to make a call to the endpoint
$session = curl_init($endpoint);

// indicates that we want the response back
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// exec curl and get the data back
$data = curl_exec($session);

// remember to close the curl session once we are finished retrieveing the data
curl_close($session);

// decode the json data to make it easier to parse the php
$search_results = json_decode($data);
if ($search_results === NULL) die('Error parsing json');

// play with the data!
$movies = $search_results->movies;

?>
<div id="container">
<form id="mform" action="http://localhost/api/movie.php" method="POST">
Search Movie Title: <input type="text" name="mtitle" value="<?php echo $mtitle; ?>">
<input type="submit" value="Submit">
</form>

<?php if($movies): ?>
	<div class="tr th">
		<div class="td">Title</div>
		<div class="td">Year</div>
		<div class="td">Runtime</div>
	</div>
	<div class="clear"></div>
	<?php
	echo '<div class="table">';
	foreach ($movies as $movie) {
		$inmin = $movie->runtime;
		$runtime = floor($inmin/60) ." hr. ". $inmin%60 ." min.";
		echo '<div class="tr">';
		echo '<div class="td"><a href="' . $movie->links->alternate . '" target="_blank">' . $movie->title .'</a></div>';
		echo '<div class="td">' . $movie->year . '</div>';
		echo '<div class="td">' . $runtime . '</div>';
		echo '</div>';
	}
	echo '</div>';
	?>
<?php else: ?>
	Your search - <?php echo $mtitle; ?> - did not match any movie title. 
<?php endif; ?>
</div>
</body>
</html>