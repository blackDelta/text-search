<?php
// Turning off version change notices
ini_set('display_errors', 0);

if(isset($_POST) and $_POST['input']!='')
{
	$path = dirname(__FILE__).'/../';
	$filepath = $path.'carpus/data.txt';
	include($path.'TextSearch.php');

	$myfile = fopen($filepath, "r") or die("Unable to open file!");
	$subject = fread($myfile,filesize($filepath));
	$input = $_POST['input'];
	fclose($myfile);

	$rd = new RedundancyDetector();

	echo "Number of Times ".$input." Appears: ".count($rd->get_total_occurrence($input,$subject))."<br>";
	echo "Longest Recurrence: ".$rd->get_longest_recurrence()."<br>";
	echo "Longest Recurrence String: ".$rd->get_longest_recurrence_chunk(). "<br>";
	echo "--------------------------------------<br>";
	echo "Total Word Count: ".$rd->get_total_words() . "<br>";
	echo "Number of letters in the shortest word: ".$rd->get_word_length('min') . "<br>";
	echo "Number of letters in the longest word: ".$rd->get_word_length('max') . "<br>";
	echo "Number of newline characters: ".$rd->get_newline_char_count(). "<br>";
	echo "Average number of letters: ".$rd->get_average_word_length(). "<br>";
}else
{
	$message = "Please type string to check";
}
?>
<html>
<head>
	<title>Redundancy Detector in Text</title>
</head>
<body>
	<div style="width:50%; margin: 0 auto">
<!--		<h1>Redundancy Detector</h1>-->
		<p><?php echo isset($message)?$message:'';?></p>
		<form method="post" action="#">
			<input type="text" name="input" value="<?php echo isset($_POST['input'])?$_POST['input']:''; ?>"/><br />
			<input type="submit" name="Submit"/>
		</form>
	</div>
</body>
</html>