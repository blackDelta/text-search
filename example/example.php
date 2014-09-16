<?php
$path = dirname(__FILE__).'/../';
include($path.'TextSearch.php');

$rd = new TextSearch();

$subject = 'academy and academic research academy';

print_r($rd->get_total_occurrence('cad',$subject));
echo "<br>";
echo "Tootal Words Count: ".$rd->get_total_words() . "<br>";
echo "Number of letters in the shortest word: ".$rd->get_word_length('min') . "<br>";
echo "Number of letters in the longest word: ".$rd->get_word_length('max') . "<br>";
echo "New Line Chars: ".$rd->get_newline_char_count(). "<br>";
echo "Average Word length: ".$rd->get_average_word_length(). "<br>";
echo "longest recurrence: ".$rd->get_longest_recurrence(). "<br>";