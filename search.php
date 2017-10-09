<?php
	include('config.php');
	global $conn;
	$mass;
	
	function search($str, $word) {
		$str_words = explode(" ", $str);
		$size = sizeof($str_words);
		for($i = 0; $i < $size; $i++) {
			if(preg_match("/".$word."/i", $str_words[$i])) {
				return TRUE;
				break;
			}   
		}
		return FALSE;
	}

if(!empty($_POST["referal"])){ //Принимаем данные

    $referal = mb_strtolower(trim(strip_tags(stripcslashes(htmlspecialchars($_POST["referal"])))));
	$FoundBool = FALSE;
	$query=('SELECT class FROM TimeTableView  where (date>DATE_ADD(now(), INTERVAL -31 DAY)) group by class');
	$result = $conn->query($query) ;				
	while ($row = $result->fetch_assoc()) 
	{
		if(search(mb_strtolower($row['class']), $referal))
		{
			echo "\n<li>".'<a href=student.php?gruppa='.$row['class'].'>'.$row['class']."</a></li>";
			$FoundBool = TRUE;
		}
	} 
		$query=('SELECT teacher FROM TimeTableView where (date>DATE_ADD(now(), INTERVAL -31 DAY)) group by teacher');
	$result = $conn->query($query) ;				
	while ($row = $result->fetch_assoc()) 
	{
		if(search(mb_strtolower($row['teacher']), $referal))
		{
			echo "\n<li>".'<a href=teacher.php?teacher='.$row['teacher'].'>'.$row['teacher']."</a></li>";
			$FoundBool = TRUE;			
		}
	}
	
	if (!$FoundBool)
		echo "К сожалению, мы ничего не нашли";
}
?>