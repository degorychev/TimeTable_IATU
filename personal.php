<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
    include('config.php');
	global $conn;
	
	
	if (isset($_COOKIE['value'])) 
	{
		$position=$_COOKIE['position'];
		$value=$_COOKIE['value'];
	}
	
	$date = new DateTime();	
	if(date("w")<2)
	$date->add(new DateInterval('P2D')) ;//если сегодня воскресенье или понедельник то добавить 2 дня 	
	$date->modify('last Monday');//Найти НУЖНЫЙ понедельник (магия)
?>
<?php
	function getRusDate($datetime)  
	{
		$yy = (int) substr($datetime,0,4) ;
		$mm = (int) substr($datetime,5,2) ;
		$dd = (int) substr($datetime,8,2) ;    
		$month =  array ('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря') ;    
		return ($dd > 0 ? $dd . " " : '') . $month[$mm - 1]." ".$yy." г. " ;
	}
	function getRasp($datain, $time)
	{
		global $conn;
		global $position;
		global $value;
		
		$data = date_format($datain, 'Y-m-d') ;
		$query = "";
		if($position == 'teacher')
		$query = "SELECT * FROM TimeTableView WHERE (date = '$data')and(timeStart = '$time')and(teacher LIKE ('$value%'))" ;
		else if ($position = 'student')
		$query = "SELECT * FROM TimeTableView WHERE (class = '$value')and(date = '$data')and(timeStart = '$time')" ;
		$result = $conn->query($query) ;
		$row = $result->fetch_assoc() ;
		switch($position)
		{
			case 'teacher':
			if($row)
			{
				echo $row['discipline'].', ('.$row['type'].') — '.$row['cabinet'] ;	
				echo '</br>'.$row['class'];
			}	
			break;
			case 'student':
			if($row)
			{
				echo $row['discipline'].', ('.$row['type'].') — '.$row['cabinet'] ;	
				echo ('<div class="teacher" data-rel="'.$row['ID'].'">'.$row['teacher'].'</div>') ;
			}
			break;
		}
	}
?>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="language" content="ru">
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/bootstrap-responsive.min.css" media="screen">
		<link rel="stylesheet" type="text/css" href="./css/search.css">
		<script type="text/javascript" src="./js/scripts.js"></script>
		<?php
			if($position == 'teacher')
			echo('<title>Преподаватель '.$value.' | Расписание ИАТУ</title>') ;
			else if ($position == 'student')
			echo('<title>Группа '.$value.' | Расписание ИАТУ</title>') ;
			else
			echo('<title>Расписание ИАТУ</title>') ;
		?>		
		<meta name="description" content="Поиск по имени студента или преподавателя, номер группы знать не обязательно :)">
		<meta name="viewport" content="width=400, initial-scale=1">		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/search_personal.js"></script>
	</head>
	<body>
		<div class="container" id="page">
			<div class="jumbotron">
				<div id="sfld">
					<?php
						if($position == 'teacher')
						echo('<h1>Преподаватель '.$value.'</h1>') ;
						else if ($position == 'student')
						echo('<h1>Группа '.$value.'</h1>') ;
						else
						{
							echo('<h1>Расписание ИАТУ</h1>') ;
							echo('<h4>Пожалуйста, определите преподавателя или группу:</h4>');
						}
					?>
					<input type="text" name="referal" placeholder="Поиск по группам или преподавателям" value="" class="input-xxlarge" autocomplete="off">
					<ul class="search_result"></ul>
					<div id="print"><a><i class="icon-print"></i> распечатать</a></div>
					<div class="results">
						<hr>
						<div class="row-fluid">
							<div class="span4">
								<h2>Понедельник</h2>	
								<?php
									echo '<h6>'.getRusDate(date_format($date, 'Y-m-d')).'</h6>' ;
								?>
								<table class="table table-bordered table-striped">
									<tbody>
										<tr>
											<td>8:30</td>
											<td>
												<?php
													getRasp($date, '08:30');
												?>
											</td>
										</tr>
										<tr>
											<td>10:15</td>
											<td>
												<?php
													getRasp($date, '10:15');
												?>
											</td>
										</tr>
										<tr>
											<td>12:20</td>
											<td>
												<?php
													getRasp($date, '12:20');
												?>
											</td>
										</tr>
										<tr>
											<td>14:05</td>
											<td>
												<?php
													getRasp($date, '14:05');
												?>
											</td>
										</tr>
										<tr>
											<td>15:50</td>
											<td>
												<?php
													getRasp($date, '15:50');
												?>
											</td>
										</tr>
										<tr>
											<td>17:30</td>
											<td>
												<?php
													getRasp($date, '17:30');
												?>
											</td>
										</tr>
										<tr>
											<td>19:10</td>
											<td>
												<?php
													getRasp($date, '19:10');
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<?php
								$date->add(new DateInterval('P1D')) ;
							?>
							<div class="span4">
								<h2>Вторник</h2>
								<?php
									echo '<h6>'.getRusDate(date_format($date, 'Y-m-d')).'</h6>' ;
								?>
								<table class="table table-bordered table-striped">
									<tbody>
										<tr>
											<td>8:30</td>
											<td>
												<?php
													getRasp($date, '08:30');
												?>
											</td>
										</tr>
										<tr>
											<td>10:15</td>
											<td>
												<?php
													getRasp($date, '10:15');
												?>
											</td>
										</tr>
										<tr>
											<td>12:20</td>
											<td>
												<?php
													getRasp($date, '12:20');
												?>
											</td>
										</tr>
										<tr>
											<td>14:05</td>
											<td>
												<?php
													getRasp($date, '14:05');
												?>
											</td>
										</tr>
										<tr>
											<td>15:50</td>
											<td>
												<?php
													getRasp($date, '15:50');
												?>
											</td>
										</tr>
										<tr>
											<td>17:30</td>
											<td>
												<?php
													getRasp($date, '17:30');
												?>
											</td>
										</tr>
										<tr>
											<td>19:10</td>
											<td>
												<?php
													getRasp($date, '19:10');
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<?php
								$date->add(new DateInterval('P1D')) ;
							?>
							<div class="span4">
								<h2>Среда</h2>
								<?php
									echo '<h6>'.getRusDate(date_format($date, 'Y-m-d')).'</h6>' ;
								?>
								<table class="table table-bordered table-striped">
									<tbody>
										<tr>
											<td>8:30</td>
											<td>
												<?php
													getRasp($date, '08:30');
												?>
											</td>
										</tr>
										<tr>
											<td>10:15</td>
											<td>
												<?php
													getRasp($date, '10:15');
												?>
											</td>
										</tr>
										<tr>
											<td>12:20</td>
											<td>
												<?php
													getRasp($date, '12:20');
												?>
											</td>
										</tr>
										<tr>
											<td>14:05</td>
											<td>
												<?php
													getRasp($date, '14:05');
												?>
											</td>
										</tr>
										<tr>
											<td>15:50</td>
											<td>
												<?php
													getRasp($date, '15:50');
												?>
											</td>
										</tr>
										<tr>
											<td>17:30</td>
											<td>
												<?php
													getRasp($date, '17:30');
												?>
											</td>
										</tr>
										<tr>
											<td>19:10</td>
											<td>
												<?php
													getRasp($date, '19:10');
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<?php
							$date->add(new DateInterval('P1D')) ;
						?>
						<div class="row-fluid">
							<div class="span4">
								<h2>Четверг</h2>
								<?php
									echo '<h6>'.getRusDate(date_format($date, 'Y-m-d')).'</h6>' ;
								?>
								<table class="table table-bordered table-striped">
									<tbody>
										<tr>
											<td>8:30</td>
											<td>
												<?php
													getRasp($date, '08:30');
												?>
											</td>
										</tr>
										<tr>
											<td>10:15</td>
											<td>
												<?php
													getRasp($date, '10:15');
												?>
											</td>
										</tr>
										<tr>
											<td>12:20</td>
											<td>
												<?php
													getRasp($date, '12:20');
												?>
											</td>
										</tr>
										<tr>
											<td>14:05</td>
											<td>
												<?php
													getRasp($date, '14:05');
												?>
											</td>
										</tr>
										<tr>
											<td>15:50</td>
											<td>
												<?php
													getRasp($date, '15:50');
												?>
											</td>
										</tr>
										<tr>
											<td>17:30</td>
											<td>
												<?php
													getRasp($date, '17:30');
												?>
											</td>
										</tr>
										<tr>
											<td>19:10</td>
											<td>
												<?php
													getRasp($date, '19:10');
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<?php
								$date->add(new DateInterval('P1D')) ;
							?>
							<div class="span4">
								<h2>Пятница</h2>
								<?php
									echo '<h6>'.getRusDate(date_format($date, 'Y-m-d')).'</h6>' ;
								?>
								<table class="table table-bordered table-striped">
									<tbody>
										<tr>
											<td>8:30</td>
											<td>
												<?php
													getRasp($date, '08:30');
												?>
											</td>
										</tr>
										<tr>
											<td>10:15</td>
											<td>
												<?php
													getRasp($date, '10:15');
												?>
											</td>
										</tr>
										<tr>
											<td>12:20</td>
											<td>
												<?php
													getRasp($date, '12:20');
												?>
											</td>
										</tr>
										<tr>
											<td>14:05</td>
											<td>
												<?php
													getRasp($date, '14:05');
												?>
											</td>
										</tr>
										<tr>
											<td>15:50</td>
											<td>
												<?php
													getRasp($date, '15:50');
												?>
											</td>
										</tr>
										<tr>
											<td>17:30</td>
											<td>
												<?php
													getRasp($date, '17:30');
												?>
											</td>
										</tr>
										<tr>
											<td>19:10</td>
											<td>
												<?php
													getRasp($date, '19:10');
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<?php
								$date->add(new DateInterval('P1D')) ;
							?>
							<div class="span4">
								<h2>Суббота</h2>
								<?php
									echo '<h6>'.getRusDate(date_format($date, 'Y-m-d')).'</h6>' ;
								?>
								<table class="table table-bordered table-striped">
									<tbody>
										<tr>
											<td>8:30</td>
											<td>
												<?php
													getRasp($date, '08:30');
												?>
											</td>
										</tr>
										<tr>
											<td>10:15</td>
											<td>
												<?php
													getRasp($date, '10:15');
												?>
											</td>
										</tr>
										<tr>
											<td>12:20</td>
											<td>
												<?php
													getRasp($date, '12:20');
												?>
											</td>
										</tr>
										<tr>
											<td>14:05</td>
											<td>
												<?php
													getRasp($date, '14:05');
												?>
											</td>
										</tr>
										<tr>
											<td>15:50</td>
											<td>
												<?php
													getRasp($date, '15:50');
												?>
											</td>
										</tr>
										<tr>
											<td>17:30</td>
											<td>
												<?php
													getRasp($date, '17:30');
												?>
											</td>
										</tr>
										<tr>
											<td>19:10</td>
											<td>
												<?php
													getRasp($date, '19:10');
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<hr>
					<div><a href="./now.php">Статистическая информация</a></div>
				</div>
				<p></p>
			</div>
		</div>
	</body>
</html>									