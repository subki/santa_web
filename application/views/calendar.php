<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<style>
  html, body {
    height: 100%;
    width: 100%;
    padding: 0px;
    margin: 0px;
  }

  .gantt-fullscreen {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 30px;
    height: 30px;
    padding: 2px;
    font-size: 32px;
    background: transparent;
    cursor: pointer;
    opacity: 0.5;
    text-align: center;
    -webkit-transition: background-color 0.5s, opacity 0.5s;
    transition: background-color 0.5s, opacity 0.5s;
  }

  .gantt-fullscreen:hover {
    background: rgba(150, 150, 150, 0.5);
    opacity: 1;
  }
</style>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="PT Passion Abadi Korpora Information System">
  <meta name="author" content="Yustinus Widya Wiratama">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/favicon.png" type="image/x-icon">

</head>

<div id="gantt_here" style='width:100%; height:90%;'></div>

<script type="text/javascript" src="<?php echo base_url() ?>assets/jquery-easyui-1.9.4/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dhtmlx/dhtmlxgantt.js"></script>
<link href="<?php echo base_url(); ?>assets/dhtmlx/dhtmlxgantt.css" rel="stylesheet">

<script>
	var filterValue = "";
	var delay;
  var json = <?php echo json_encode($data) ?>;
	$(document).ready(function() {
		gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
		gantt.config.start_date = new Date(2020, 10, 31);
		gantt.config.end_date = new Date(2021, 12, 31);
		gantt.config.show_tasks_outside_timescale = true;
		gantt.config.readonly = true;
		gantt.config.min_column_width = 20;
		gantt.config.scale_height = 90;
		gantt.config.scales = [
			{unit: "day", step: 1, format: "%d"},
			{unit: "week", step: 1, format: "Week %W"},
			{unit: "month", step: 1, format: "%M"},
			{unit: "year", step: 1, format: "%Y"},
		];

		var textFilter = `
  <input class='data-text-filter' style='width: 80%; height: 20px; margin-left:10px;' type='text' placeholder='Find Here' oninput='gantt.$doFilter(this.value)'>`;
		gantt.config.columns = [
			{name:"text", label:textFilter, width:"300", tree:true },
      {name:"startdate", label:"Start", width:"120", align: "center" },
      {name:"enddate", label:"End", width:"120", align: "center" },
      {name:"pic", label:"PIC", width:"120", align: "center" }
      ];
		gantt.attachEvent("onTaskClick", function(id,e){
			//any custom logic here
      console.log("id",id)
      console.log("e",e)
			return true;
		});
		gantt.templates.task_class  = function(start, end, task){
			return "high";
		};
		gantt.attachEvent("onBeforeTaskDisplay", function(id, task){
			if(!filterValue) return true;
			var s = task.startdate.split("/");
			var e = task.enddate.split("/");
			var s1 = s[2]+""+s[1]+""+s[0];
			var e1 = e[2]+""+e[1]+""+e[0];
			var normalizedStart = s1.toLowerCase();
			var normalizedEnd = e1.toLowerCase();
			var normalizedText = task.text.toLowerCase();
			var normalizedValue = filterValue.toLowerCase();
			var text = normalizedText.indexOf(normalizedValue) > -1;
			var start = normalizedStart.indexOf(normalizedValue) > -1;
			var end = normalizedEnd.indexOf(normalizedValue) > -1;

			return (text || start || end)
		});
		gantt.$doFilter = function(value){
			filterValue = value;
			clearTimeout(delay);
			delay = setTimeout(function(){
				gantt.render();
				$(".data-text-filter").focus();
				$(".data-text-filter").val(filterValue);
			}, 200)
		}
		gantt.init("gantt_here");
		for(var i=0; i<json.length; i++){
			json[i].color = color[Math.floor(Math.random() * 5)]
      json[i].start_date = new Date();
			if(json[i].start_date > new Date(json[i].end_date)){
				var e = json[i].start_date;
				var s = json[i].end_date;
				json[i].start_date = s;
				json[i].end_date = e;
      }
			json[i].startdate = json[i].start_date;
			json[i].enddate = json[i].end_date;
			gantt.addTask(json[i]);
    }
		gantt.eachTask(function(task){
			task.$open = true;
		});
		gantt.render();
	});

	var color = ['#d11141','#00b159','#00aedb','#f37735','#ffc425','#34c461']


</script>
</body>
</html>