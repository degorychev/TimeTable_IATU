<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
    include('config.php');
	global $conn;

	date_default_timezone_set("UTC+4");
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
					echo '<div class="span2" data-room="'.$cab.'">' ;
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
		<script>
			function updater($selector)  {
				var timeout = 1000*60*10 ;
				var self = this ;
				var classes = ["error", "success", "info"] ;				
				this.fill = function (data)  {
					if (data)  {
						$selector.find("div.span2").each(function()  
						{
							var $this = $(this) ;
							var cc =1 ;
							if (!$this.data("room") || !data[$this.data("room")]) return ;
							var cdata = data[$this.data("room")] ;
							$this.find(".male").text(cdata.male) ;
							$this.find(".female").text(cdata.female) ;
							$this.find(".room").text(cdata.room) ;
							$this.find(".groups").text(cdata.groups) ;
							for (var j=0 ; j<classes.length ; j++)
								$this.find("> div").removeClass("alert-"+classes[j]) ;
							if (parseInt(cdata.male)!=0 || parseInt(cdata.female)!=0) cc = parseInt(cdata.male) > parseInt(cdata.female) ? 2:0 ;
							$this.find("> div").addClass("alert-"+classes[cc]) ;
						 }) ;
						setTimeout(self.send, timeout) ;
					 }
				 }

				this.send = function ()  {
					$.ajax( {
url:"/now/api", dataType:"json" }
).done(self.fill) ;
				 }
				setTimeout(this.send, timeout) ;
			 }			
			var upd = new updater($(".board")) ;
			$(".board").on("click", "div[data-room]", function()  {
var $link = $(this).find("a") ;
 if ($link.length) document.location = $link.attr("href") ;
 }
) ;
		</script>
		<div>* информация на этой странице не является точной, мы стараемся сделать статистический анализ расписания наиболее приближенным к реальности, но размеры аудиторий и групп, а так же их расположение могут отличаться</div>
	</div>
	<!-- page -->
	<script>(function() {
var d="webkitvisibilitychange",g="_ns" ;
if(window.mobilespeed_jstiming) {
window.mobilespeed_jstiming.a= {
 }
 ;
window.mobilespeed_jstiming.b=1 ;
var n=function(b,a,e) {
var c=b.t[a],f=b.t.start ;
if(c&&(f||e))return c=b.t[a][0],void 0!=e?f=e:f=f[0],Math.round(c-f) }
,p=function(b,a,e) {
var c="" ;
window.mobilespeed_jstiming.srt&&(c+="&srt="+window.mobilespeed_jstiming.srt,delete window.mobilespeed_jstiming.srt) ;
window.mobilespeed_jstiming.pt&&(c+="&tbsrt="+window.mobilespeed_jstiming.pt,delete window.mobilespeed_jstiming.pt) ;
try {
window.external&&window.external.tran?
		c+="&tran="+window.external.tran:window.gtbExternal&&window.gtbExternal.tran?c+="&tran="+window.gtbExternal.tran():window.chrome&&window.chrome.csi&&(c+="&tran="+window.chrome.csi().tran) }
catch(v) {
 }
var f=window.chrome ;
if(f&&(f=f.loadTimes)) {
f().wasFetchedViaSpdy&&(c+="&p=s") ;
if(f().wasNpnNegotiated) {
c+="&npn=1" ;
var h=f().npnNegotiatedProtocol ;
h&&(c+="&npnv="+(encodeURIComponent||escape)(h)) }
f().wasAlternateProtocolAvailable&&(c+="&apa=1") }
var l=b.t,t=l.start ;
f=[] ;
h=[] ;
for(var k in l)if("start"!=k&&
		0!=k.indexOf("_")) {
var m=l[k][1] ;
m?l[m]&&h.push(k+"."+n(b,k,l[m][0])):t&&f.push(k+"."+n(b,k)) }
delete l.start ;
if(a)for(var q in a)c+="&"+q+"="+a[q] ;
(a=e)||(a="https:"==document.location.protocol?"https://csi.gstatic.com/csi":"http://csi.gstatic.com/csi") ;
return[a,"?v=3","&s="+(window.mobilespeed_jstiming.sn||"mobilespeed")+"&action=",b.name,h.length?"&it="+h.join(","):"",c,"&rt=",f.join(",")].join("") }
,r=function(b,a,e) {
b=p(b,a,e) ;
if(!b)return"" ;
a=new Image ;
var c=window.mobilespeed_jstiming.b++ ;
window.mobilespeed_jstiming.a[c]=
		a ;
a.onload=a.onerror=function() {
window.mobilespeed_jstiming&&delete window.mobilespeed_jstiming.a[c] }
 ;
a.src=b ;
a=null ;
return b }
 ;
window.mobilespeed_jstiming.report=function(b,a,e) {
if("prerender"==document.webkitVisibilityState) {
var c=!1,f=function() {
if(!c) {
a?a.prerender="1":a= {
prerender:"1" }
 ;
if("prerender"==document.webkitVisibilityState)var h=!1 ;
else r(b,a,e),h=!0 ;
h&&(c=!0,document.removeEventListener(d,f,!1)) }
 }
 ;
document.addEventListener(d,f,!1) ;
return"" }
return r(b,a,e) }
 ;
var u=function(b,a,e,c) {
return 0<
		e?(c?b.tick(a,c,e):b.tick(a,"",e),!0):!1 }
 ;
window.mobilespeed_jstiming.getNavTiming=function(b) {
if(window.performance&&window.performance.timing) {
var a=window.performance.timing ;
u(b,"_dns",a.domainLookupStart)&&u(b,"dns_",a.domainLookupEnd,"_dns") ;
u(b,"_con",a.connectStart)&&u(b,"con_",a.connectEnd,"_con") ;
u(b,"_req",a.requestStart)&&u(b,"req_",a.responseStart,"_req") ;
u(b,"_rcv",a.responseStart)&&u(b,"rcv_",a.responseEnd,"_rcv") ;
if(u(b,g,a.navigationStart)) {
u(b,"ntsrt_",a.responseStart,g) ;
u(b,"nsfs_",
		a.fetchStart,g) ;
u(b,"nsrs_",a.redirectStart,g) ;
u(b,"nsre_",a.redirectEnd,g) ;
u(b,"nsds_",a.domainLookupStart,g) ;
u(b,"nscs_",a.connectStart,g) ;
u(b,"nsrqs_",a.requestStart,g) ;
var e=!1 ;
try {
e=window.external&&window.external.startE }
catch(c) {
 }
!e&&window.chrome&&window.chrome.csi&&(e=Math.floor(window.chrome.csi().startE)) ;
e&&(u(b,"_se",e),u(b,"sens_",a.navigationStart,"_se")) ;
u(b,"ntplt0_",a.loadEventStart,g) ;
u(b,"ntplt1_",a.loadEventEnd,g) ;
window.chrome&&window.chrome.loadTimes&&(a=window.chrome.loadTimes().firstPaintTime)&&
		u(b,"nsfp_",1E3*a,g) }
 }
 }
 }
 ;
 }
).call(this) ;
	</script>
	<style id="extraClass">.extraClassAspect  {
-webkit-transform: scaleX(1.34)!important ;
 }
.extraClassCrop  {
-webkit-transform: scale(1.34)!important ;
 }
</style>
	</body>
</html>