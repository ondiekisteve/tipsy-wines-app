var SLOption={responsive:!0,maintainAspectRatio:!0,datasetStrokeWidth:3,pointDotStrokeWidth:4,tooltipFillColor:"rgba(0,0,0,0.6)",legend:{display:!1,position:"bottom"},hover:{mode:"label"},scales:{xAxes:[{display:!1}],yAxes:[{display:!1}]},title:{display:!1,fontColor:"#FFF",fullWidth:!1,fontSize:40,text:"82%"}},SLlabels=["January","February","March","April","May","June"],LineSL1ctx=document.getElementById("custom-line-chart-sample-one").getContext("2d");(gradientStroke=LineSL1ctx.createLinearGradient(300,0,0,300)).addColorStop(0,"#0288d1"),gradientStroke.addColorStop(1,"#26c6da"),(gradientFill=LineSL1ctx.createLinearGradient(300,0,0,300)).addColorStop(0,"#0288d1"),gradientFill.addColorStop(1,"#26c6da");var SL1Chart=new Chart(LineSL1ctx,{type:"line",data:{labels:SLlabels,datasets:[{label:"My Second dataset",borderColor:gradientStroke,pointColor:"#fff",pointBorderColor:gradientStroke,pointBackgroundColor:"#fff",pointHoverBackgroundColor:gradientStroke,pointHoverBorderColor:gradientStroke,pointRadius:4,pointBorderWidth:1,pointHoverRadius:4,pointHoverBorderWidth:1,fill:!0,backgroundColor:gradientFill,borderWidth:1,data:[24,18,20,30,40,43]}]},options:SLOption}),LineSL2ctx=document.getElementById("custom-line-chart-sample-two").getContext("2d");(gradientStroke=LineSL2ctx.createLinearGradient(500,0,0,200)).addColorStop(0,"#8e24aa"),gradientStroke.addColorStop(1,"#ff6e40"),(gradientFill=LineSL2ctx.createLinearGradient(500,0,0,200)).addColorStop(0,"#8e24aa"),gradientFill.addColorStop(1,"#ff6e40");var gradientStroke,gradientFill,SL2Chart=new Chart(LineSL2ctx,{type:"line",data:{labels:SLlabels,datasets:[{label:"My Second dataset",borderColor:gradientStroke,pointColor:"#fff",pointBorderColor:gradientStroke,pointBackgroundColor:"#fff",pointHoverBackgroundColor:gradientStroke,pointHoverBorderColor:gradientStroke,pointRadius:4,pointBorderWidth:1,pointHoverRadius:4,pointHoverBorderWidth:1,fill:!0,backgroundColor:gradientFill,borderWidth:1,data:[24,18,20,30,40,43]}]},options:SLOption}),LineSL3ctx=document.getElementById("custom-line-chart-sample-three").getContext("2d");(gradientStroke=LineSL3ctx.createLinearGradient(500,0,0,200)).addColorStop(0,"#ff6f00"),gradientStroke.addColorStop(1,"#ffca28"),(gradientFill=LineSL3ctx.createLinearGradient(500,0,0,200)).addColorStop(0,"#ff6f00"),gradientFill.addColorStop(1,"#ffca28");var SL3Chart=new Chart(LineSL3ctx,{type:"line",data:{labels:SLlabels,datasets:[{label:"My Second dataset",borderColor:gradientStroke,pointColor:"#fff",pointBorderColor:gradientStroke,pointBackgroundColor:"#fff",pointHoverBackgroundColor:gradientStroke,pointHoverBorderColor:gradientStroke,pointRadius:4,pointBorderWidth:1,pointHoverRadius:4,pointHoverBorderWidth:1,fill:!0,backgroundColor:gradientFill,borderWidth:1,data:[24,18,20,30,40,43]}]},options:SLOption});
