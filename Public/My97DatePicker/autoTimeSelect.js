function buttonSelectTime(button){
	var startTime = $("#countTimestart").val();
	var endTime = $("#countTimeend").val();	
	var monthPerDays = new Array(0,31,29,31,30,31,30,31,31,30,31,30,31);
	

	
	switch(button){
		case "today":
			flagDate = getDateObj("");
			startTime = flagDate.getTime()+" 00:00:00";
			endTime = flagDate.getTime()+" 23:59:59";
			break;
		case "lastDay":
			var flagTime = startTime;
			var flagDate = getDateObj(flagTime);
			if(Number(flagDate.day)==1 || Number(flagDate.day)<1){
				if(Number(flagDate.month)==1 || Number(flagDate.month)<1){
					flagDate.year = String(Number(flagDate.year)-1);
					flagDate.month='12';
					flagDate.day=String(monthPerDays[12]);	
				}else{
					flagDate.month=String(Number(flagDate.month)-1);
					flagDate.day=String(monthPerDays[Number(flagDate.month)]);	
				}
					
			}else{
				flagDate.day = String(Number(flagDate.day)-1);
			}
			startTime = flagDate.getTime()+" 00:00:00";
			endTime = flagDate.getTime()+" 23:59:59";
			break;
		case "nextDay":
			var flagTime = endTime;
			var flagDate = getDateObj(flagTime);
			if(Number(flagDate.day)==monthPerDays[Number(flagDate.month)] || Number(flagDate.day) >monthPerDays[Number(flagDate.month)]){
				if(Number(flagDate.month)==12){
					flagDate.year = String(Number(flagDate.year)+1);
					flagDate.month='01';
					flagDate.day='01';
				}else{
					flagDate.month=String(Number(flagDate.month)+1);
					flagDate.day='01';
				}
					
			}else{
				flagDate.day = String(Number(flagDate.day)+1);
			}
			startTime = flagDate.getTime()+" 00:00:00";
			endTime = flagDate.getTime()+" 23:59:59";
			break;
		case "lastWeek":
			var EnglishMonth = Array(0,'Jan','Feb','Mar','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec');
			var flagTime = startTime;
			var flagDate = getDateObj(flagTime);
			
			var dateTmp = EnglishMonth[Number(flagDate.month)]+" "+Number(flagDate.day)+", "+Number(flagDate.year)+" 00:00:00";
			var date = new Date(dateTmp);
			var stampTime = date.getTime();
			weekDayNum = date.getDay();
			if(weekDayNum==0){
				weekDayNum=7;
			}
			var lastWeek1StampTime = stampTime-(6+weekDayNum)*24*60*60*1000;
			var lastWeek7StampTime = stampTime-(weekDayNum)*24*60*60*1000;
			var lastWeek1date=new Date(lastWeek1StampTime).toLocaleDateString();
			var lastWeek7date=new Date(lastWeek7StampTime).toLocaleDateString();
			startTime = changeTime(lastWeek1date)+" 00:00:00";
			endTime = changeTime(lastWeek7date)+" 23:59:59";
			break;
		case "nextWeek":
			var EnglishMonth = Array(0,'Jan','Feb','Mar','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec');
			var flagTime = endTime;
			var flagDate = getDateObj(flagTime);
			
			var dateTmp = EnglishMonth[Number(flagDate.month)]+" "+Number(flagDate.day)+", "+Number(flagDate.year)+" 23:59:59";
			var date = new Date(dateTmp);
			var stampTime = date.getTime();
			weekDayNum = date.getDay();
			if(weekDayNum==0){
				weekDayNum=7;
			}
			var lastWeek1StampTime = stampTime+(8-weekDayNum)*24*60*60*1000;
			var lastWeek7StampTime = stampTime+(14-weekDayNum)*24*60*60*1000;
			var lastWeek1date=new Date(lastWeek1StampTime).toLocaleDateString();
			var lastWeek7date=new Date(lastWeek7StampTime).toLocaleDateString();
			startTime = changeTime(lastWeek1date)+" 00:00:00";
			endTime = changeTime(lastWeek7date)+" 23:59:59";
			break;
		case "lastMonth":
			var flagTime = startTime;
			var flagDate = getDateObj(flagTime);
			if(Number(flagDate.month)==1){
				flagDate.year=String(Number(flagDate.year)-1);
				flagDate.month = '12';	
			}else{
				flagDate.month = String(Number(flagDate.month)-1);
			}
			flagDate.day = '1';
			startTime = flagDate.getTime()+" 00:00:00";
			flagDate.day = monthPerDays[Number(flagDate.month)];
			endTime = flagDate.getTime()+" 23:59:59";
			break;
		case "nextMonth":
			var flagTime = endTime;
			var flagDate = getDateObj(flagTime);
			if(Number(flagDate.month)==12){
				flagDate.year=String(Number(flagDate.year)+1);
				flagDate.month = '01';	
			}else{
				flagDate.month = String(Number(flagDate.month)+1);
			}
			flagDate.day = '1';
			startTime = flagDate.getTime()+" 00:00:00";
			flagDate.day = monthPerDays[Number(flagDate.month)];
			endTime = flagDate.getTime()+" 23:59:59";
			break;
		case "lastSeason":
			var seasonMonth = new Array(0,1,1,1,2,2,2,3,3,3,4,4,4);
			var season = new Array();
			season[1] = new Array();
			season[1][1] = "-01-01 00:00:00";
			season[1][2] = "-03-31 23:59:59";
			season[2] = new Array();
			season[2][1] = "-04-01 00:00:00";
			season[2][2]= "-06-30 23:59:59";
			season[3] = new Array();
			season[3][1] = "-07-01 00:00:00";
			season[3][2] = "-09-30 23:59:59";
			season[4] = new Array();
			season[4][1] = "-10-01 00:00:00";
			season[4][2] = "-12-31 23:59:59";
			var flagTime = startTime;
			var flagDate = getDateObj(flagTime);
			var nowseason = seasonMonth[Number(flagDate.month)];
			if(nowseason==1){
				var year = flagDate.year-1;	
				var lastseason = 4;
				startTime = year+season[lastseason][1];
				endTime = year+season[lastseason][2];
			}else{
				var year = flagDate.year;	
				var lastseason = nowseason-1;
				startTime = year+season[lastseason][1];
				endTime = year+season[lastseason][2];
			}
			break;
		case "nextSeason":
			var seasonMonth = new Array(0,1,1,1,2,2,2,3,3,3,4,4,4);
			var season = new Array();
			season[1] = new Array();
			season[1][1] = "-01-01 00:00:00";
			season[1][2] = "-03-31 23:59:59";
			season[2] = new Array();
			season[2][1] = "-04-01 00:00:00";
			season[2][2]= "-06-30 23:59:59";
			season[3] = new Array();
			season[3][1] = "-07-01 00:00:00";
			season[3][2] = "-09-30 23:59:59";
			season[4] = new Array();
			season[4][1] = "-10-01 00:00:00";
			season[4][2] = "-12-31 23:59:59";
			var flagTime = endTime;
			var flagDate = getDateObj(flagTime);
			var nowseason = seasonMonth[Number(flagDate.month)];
			if(nowseason==4){
				var year = String(Number(flagDate.year)+1);	
				var lastseason = 1;
				startTime = year+season[lastseason][1];
				endTime = year+season[lastseason][2];
			}else{
				var year = flagDate.year;	
				var lastseason = Number(nowseason)+1;
				startTime = year+season[lastseason][1];
				endTime = year+season[lastseason][2];
			}
			break;
		case "lastYear":
			var flagTime = startTime;
			var flagDate = getDateObj(flagTime);
			flagDate.year = String(Number(flagDate.year)-1);
			flagDate.month = '1';
			flagDate.day = '1';
			startTime = flagDate.getTime()+" 00:00:00";
			flagDate.month = '12';
			flagDate.day = '31';
			endTime = flagDate.getTime()+" 23:59:59";
			break;
		case "nextYear":
			var flagTime = endTime;
			var flagDate = getDateObj(flagTime);
			flagDate.year = String(Number(flagDate.year)+1);
			flagDate.month = '1';
			flagDate.day = '1';
			startTime = flagDate.getTime()+" 00:00:00";
			flagDate.month = '12';
			flagDate.day = '31';
			endTime = flagDate.getTime()+" 23:59:59";
			break;
			
	}
	
	$("#countTimestart").val(startTime);
	$("#countTimeend").val(endTime);
	
}

function getDateObj(time){
	if(time==""){
		var now = new Date();
		var nowYear = now.getFullYear();
		var nowMonth = String(Number(now.getMonth())+1);
		if(nowMonth.length<2){
			nowMonth = '0'+nowMonth;	
		}
		var nowDay = String(now.getDate());	
		if(nowDay.length<2){
			nowDay = '0'+nowDay;	
		}
		time = nowYear+"-"+nowMonth+"-"+nowDay;
	}
	
	var dateObj = new Object();
	dateObj.time = time;
	dateObj.year = time.substr(0,4);
	dateObj.month = time.substr(5,2);
	dateObj.day = time.substr(8,2);
	
	dateObj.getTime=function(){
		if(this.month.length<2){
			this.month = '0'+this.month;
		}
		
		if(this.day.length<2){
			this.day = '0'+this.day;	
		}
		var timeTmp = this.year+"-"+this.month+"-"+this.day;
		this.time = timeTmp;
		return timeTmp;
	}
	
	dateObj.getYear=function(){
		this.year=this.time.substr(0,4);	
		return this.year;
	}
	dateObj.getMonth=function(){
		this.month=this.time.substr(5,2);
		return this.monty;
	}
	dateObj.getDay=function(){
		this.day=this.time.substr(8,2);
		return this.day;	
	}
	return dateObj;
}

//日期转换(2005年1月1日===>2005-1-01)
function changeTime(str){
  var curYear = str.substring(0,str.indexOf('年'));
  var curMonth =str.substring(str.indexOf('年')+1,str.indexOf('月'));
  var curDay =str.substring(str.indexOf('月')+1,str.indexOf('日'));
   
  if (curMonth<10){
  curMonth="0"+curMonth;
  }
  if(curDay<10){
  curDay="0"+curDay;
  }
  var returnDate = curYear+"-"+curMonth+"-"+curDay; 
 return returnDate;
}


function cycleChange(){
	var cycleType = $("#cycleSelect").val();
	var flagDate = getDateObj("");
	//var startTime = flagDate.getTime()+" 00:00:00";
	//var endTime = flagDate.getTime()+" 23:59:59";
	var startTime = $("#countTimestart").val();
	var endTime = $("#countTimeend").val();
	$("#countTimestart").val(startTime);
	$("#countTimeend").val(endTime);
	//return false;
	switch(cycleType){
		case "day":
			$("#lastBut").val("上一天");$("#lastBut").unbind("click").click(function(){buttonSelectTime('lastDay');});
			$("#nextBut").val("下一天");$("#nextBut").unbind("click").click(function(){buttonSelectTime('nextDay');});
			break;
		case "week":
			$("#lastBut").val("上一周");$("#lastBut").unbind("click").click(function(){buttonSelectTime('lastWeek');});
			$("#nextBut").val("下一周");$("#nextBut").unbind("click").click(function(){buttonSelectTime('nextWeek');});
			buttonSelectTime('lastWeek');
			buttonSelectTime('nextWeek');
			break;
		case "month":
			$("#lastBut").val("上一月");$("#lastBut").unbind("click").click(function(){buttonSelectTime('lastMonth');});
			$("#nextBut").val("下一月");$("#nextBut").unbind("click").click(function(){buttonSelectTime('nextMonth');});
			buttonSelectTime('lastMonth');
			buttonSelectTime('nextMonth');
			break;
		case "season":
			$("#lastBut").val("上一季");$("#lastBut").unbind("click").click(function(){buttonSelectTime('lastSeason');});
			$("#nextBut").val("下一季");$("#nextBut").unbind("click").click(function(){buttonSelectTime('nextSeason');});
			buttonSelectTime('lastSeason');
			buttonSelectTime('nextSeason');
			break;
		case "year":
			$("#lastBut").val("上一年");$("#lastBut").unbind("click").click(function(){buttonSelectTime('lastYear');});
			$("#nextBut").val("下一年");$("#nextBut").unbind("click").click(function(){buttonSelectTime('nextYear');});
			buttonSelectTime('lastYear');
			buttonSelectTime('nextYear');
			break;
		default:
			break;
				
	}

}