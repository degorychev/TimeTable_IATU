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
			echo "\n".'<a href=setcookie.php?position=student&value='.$row['class'].'><li>Группа '.$row['class']."</li></a>";
			$FoundBool = TRUE;
		}
	} 
	$query=('SELECT teacher FROM TimeTableView where (date>DATE_ADD(now(), INTERVAL -31 DAY)) group by teacher');
	$result = $conn->query($query) ;				
	while ($row = $result->fetch_assoc()) 
	{
		if(search(mb_strtolower($row['teacher']), $referal))
		{
			echo "\n".'<a href=setcookie.php?position=teacher&value='.$row['teacher'].'><li>Преподаватель '.$row['teacher']."</li></a>";
			$FoundBool = TRUE;			
		}
	}
	$query=('SELECT cabinet FROM TimeTableView where (date>DATE_ADD(now(), INTERVAL -31 DAY)) group by cabinet');
	$result = $conn->query($query) ;				
	while ($row = $result->fetch_assoc()) 
	{
		if(search(mb_strtolower($row['cabinet']), $referal))
		{
			echo "\n".'<a href=setcookie.php?position=cabinet&value='.$row['cabinet'].'><li>Кабинет № '.$row['cabinet']."</li></a>";
			$FoundBool = TRUE;			
		}
	}
	
	if (!$FoundBool)
		echo "К сожалению, мы ничего не нашли";
}
?>