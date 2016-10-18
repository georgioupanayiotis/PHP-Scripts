<div style="width:50%;">
 
<form method="post">
  <div>
    <label>Give Capital and Small Letters to Count</label>
    <input type="text" name="string_word" placeholder="Give Capital and Small Letters to Count . . . . ." autofocus="autofocus" />
  </div>
  <button type="submit" name="count_letter">Count . . . . .</button>
</form>
 
<?php
 
function count_Capital_Letter($string_letter)
	{
	return strlen(preg_replace('/[^A-Z]+/', '', $string_letter));
	}
 
function count_Small_Letter($string_letter)
	{
	return strlen(preg_replace('/[^a-z]+/', '', $string_letter));
	}
 
if (isset($_POST['count_letter']))
	{
	$string_word = $_POST['string_word'];
	$result1 = count_Capital_Letter($string_word);
	$result2 = count_Small_Letter($string_word);
?>
 
<br />
 
<div>
  <button type="button"><span>&times;</span></button>
  The word given by the user is <strong style="font-size:20px;"><?php
	echo $string_word; ?></strong>
</div>
 
<div>Count of Capital Letters is <strong style="font-size:20px;"><?php
	echo $result1; ?></strong></div>
<div>Count of Small Letters is <strong style="font-size:20px;"><?php
	echo $result2; ?></strong></div>
 
<?php
	} ?>
 
</div>
