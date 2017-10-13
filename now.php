<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
    include('config.php');
	global $conn;

	date_default_timezone_set("Europe/Ulyanovsk");
	$datenow = date('Y.m.d') ;
?>

<?php
    function TimeSearch($InputPara) 
     {
		switch ($InputPara) 
		{
		case 0:
			return "00:00";
			break;
		case 1:
			return "8:30";
			break;
		case 2:
			return "10:15";
			break;
		case 3:
			return "12:20";
			break;
		case 4:
			return "14:05";
			break;
		case 5:
			return "15:50";
			break;
		case 6:
			return "17:30";
			break;
		case 7:
			return "19:10";
			break;
		 }
	 }
?>

<?php
    function ParaSearch() 
     {
		$TIMENOW = date("H:i:s") ;
//		$TIMENOW = "14:37" ;

		$para1 = new DateTime("8:30") ; //1 пара
		$para2 = new DateTime("10:15") ;//2 пара
		$para3 = new DateTime("12:20") ;//3 пара (АТМБд)
		$para4 = new DateTime("14:05") ;//4 пара
		$para5 = new DateTime("15:50") ;//5 пара
		$para6 = new DateTime("17:30") ;//6 пара (Вечерники)
		$para7 = new DateTime("19:10") ;//7 пара
		$para8 = new DateTime("20:40") ;//Конец занятий
		$date_now = new DateTime($TIMENOW) ;// текущее значение времени
		if ($date_now >= $para1 && $date_now <= $para2) 
			return 1;
		else
		if ($date_now >= $para2 && $date_now <= $para3) 
		   return 2;
		else
		if ($date_now >= $para3 && $date_now <= $para4) 
			return 3;
		else
		if ($date_now >= $para4 && $date_now <= $para5) 
			return 4;
		else
		if ($date_now >= $para5 && $date_now <= $para6) 
			return 5;
		else
		if ($date_now >= $para6 && $date_now <= $para7) 
			return 6;
		else
		if ($date_now >= $para7 && $date_now <= $para8) 
			return 7;
		else
			return 0;
	 }
?>

<html>
	<head>
		<meta charset="utf-8" />
		<meta name="language" content="ru">
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/bootstrap-responsive.min.css" media="screen">		
		
		<script type="text/javascript" src="./js/scripts.js"></script>
		<title>ИАТУ Сейчас</title>
		<meta name="description" content="Как загружены аудитории? Где студенток больше, чем студентов? Какие аудитории свободны?">
		<meta name="viewport" content="width=400, initial-scale=1">
	</head>
	<div class="container" id="page">
	<div class="jumbotron">
		<div id="sfld">
		<?php
			echo '<h1>ИАТУ сейчас (пара №'.ParaSearch().')</h1>' ;

		?>
			основано на информации из <a href="http://xn--80ap5ae.xn--p1ai/raspisanie/">расписания</a>*
		</div>
		<hr>
		<p><a href="#main">главный корпус</a></p>
	</div>
	<div class="board">
		<hr>
		<h2><a href="#main" name="passage">Главный корпус<i class="icon-search"></i></a></h2>
		<div class="row-fluid marketing">
			<p></p>
			<?php
				$query = "SELECT cabinet FROM TimeTableView GROUP BY cabinet" ;
				$result = $conn->query($query) ;				
				while ($row = $result->fetch_assoc())
				 {
					$groups = '' ;
					$cab = $row['cabinet'] ;					
					$query2 = "SELECT class FROM TimeTableView WHERE (date = '$datenow') and (TimeStart = '".TimeSearch(ParaSearch())."') and (cabinet = '".$row['cabinet']."')" ;
					$result2 = $conn->query($query2) ;
					while ($row2 = $result2->fetch_assoc())
						$groups = $groups.$row2['class'].', ' ;					
					echo '<div class="span2">' ;
						echo '<div class="alert alert-success">' ;
							echo '<h4><a href="#cab">'.$cab.'<i class="icon-search"></i></a></h4>' ;
							echo '<p>Студенток: <span class="female">0</span></p>' ;
							echo '<p>Студентов: <span class="male">0</span></p>' ;
							echo '<p>Мест: <span class="room">118 (0%)</span></p>' ;
							echo '<p>Группы: <span class="groups">'.$groups.'</span></p>' ;
						echo '</div>' ;
					echo '</div>' ;					
				 }
			?>

		</div>
		<hr>
		<div>* информация на этой странице не является точной, мы стараемся сделать статистический анализ расписания наиболее приближенным к реальности, но размеры аудиторий и групп, а так же их расположение могут отличаться</div>
	</div>
	<style id="extraClass">.extraClassAspect  {
-webkit-transform: scaleX(1.34)!important ;
 }
.extraClassCrop  {
-webkit-transform: scale(1.34)!important ;
 }
</style>
	</body>
</html>