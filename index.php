<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
    include('config.php');
	global $conn;
?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Расписание ИАТУ</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<meta name="description" content="Современное представление расписания">
		<link rel="stylesheet" href="./css/main.css">
		<link rel="stylesheet" href="./css/popup.css">
	</head>
	<body>
		<div class="pol-grid">
			<a href="http://xn--80ap5ae.xn--p1ai/">
				<div class="pol-grid-item" style="background-color:#114b9a;">
					<div class="pol-grid-item-avatar" style="background-color:#c6dde3;">
						<img src="./img/logo.png">
					</div>
					<div class="pol-grid-item-name">
						<h4>На главную</h4>
					</div>
				</div></a>
				
				<div class="pol-grid-item" style="background-color:#d3202f;">
					<a href="#win1"><div class="pol-grid-item-avatar" style="background-color:#f67860;">
						<img src="./img/student.png">
					</div>
					<div class="pol-grid-item-name">
						<h4>Студенту</h4>
					</div></a>
				</div>
				<div class="pol-grid-item" style="background-color:#f2722a;">
					<a href="#win2"><div class="pol-grid-item-avatar" style="background-color:#f7b986;">
						<img src="./img/teacher.png">
					</div>
					<div class="pol-grid-item-name">
						<h4>Преподавателю</h4>
					</div></a>
				</div>
				
				<div class="pol-grid-item" style="background-color:#4fa94d;">
					<div class="pol-grid-item-avatar" style="background-color:#aedcb0;">
						<img src="./img/stolov.png">
					</div>
					<div class="pol-grid-item-name">
						<h4>Мониторинг столовой</h4>
					</div>
				</div>
				
				<div class="pol-grid-item" style="background-color:#a70627;">
					<a href="./now.php">
						<div class="pol-grid-item-avatar" style="background-color:#88cfec;">	
							<img src="./img/kabinet.png">
						</div>
						<div class="pol-grid-item-name">
							<h4>Мониторинг кабинетов</h4>
						</div>
					</a>
				</div>
				<div class="pol-grid-item" style="background-color:#434b75;">
					<div class="pol-grid-item-avatar" style="background-color:#e9d48b;">
						<img src="./img/jurnal.png">
					</div>
					<div class="pol-grid-item-name">
						<h4>Заполнение журнала</h4>
					</div>
				</div>
		</div>
		<a href="#x" class="overlay" id="win1"></a>
		<div class="popup">
			<h2>  Выбор группы студентов </h2>
			<select name="selectGroup" class="Group" onchange="location=value">
				<option value="">Группы</option>
				<?php 
					$query=('SELECT class FROM TimeTableView  where (date>DATE_ADD(now(), INTERVAL -31 DAY)) group by class');
					$result = $conn->query($query) ;				
					while ($row = $result->fetch_assoc()) 
					{
						echo '<option value=student.php?gruppa='.$row['class'].'>'.$row['class'].'</option>';
						} 
					?>
					</select>
					<a class="close" title="Закрыть" href="#close"></a>
		</div>
		<a href="#x" class="overlay" id="win2"></a>
		<div class="popup">
			<h2> Выбор фамилии преподавателя </h2>
			<select name="selectGroup" class="Group" onchange="location=value">
				<option value="">Преподаватели</option>
				<?php 
					$query=('SELECT teacher FROM TimeTableView where (date>DATE_ADD(now(), INTERVAL -31 DAY)) group by teacher');
					$result = $conn->query($query) ;				
					while ($row = $result->fetch_assoc()) 
					{
						echo '<option value=teacher.php?teacher='.$row['teacher'].'>'.$row['teacher'].'</option>';
					} 
				?>
				<a class="close" title="Закрыть" href="#close"></a>
			</div>
			
			<style id="extraClass">.extraClassAspect {-webkit-transform: scaleX(1)!important;}.extraClassCrop {-webkit-transform: scale(1)!important;}</style>
		</body>
	</html>				