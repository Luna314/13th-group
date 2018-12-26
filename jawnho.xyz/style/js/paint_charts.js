/*-----------------------------------------------------------------------------------*/
/*	绘制"年/季度/月"票房份额饼图  paint_chart1()
/*-----------------------------------------------------------------------------------*/

function paint_chart1(data_submit, data_load) {
    // chart1_div = $('#chart');
    // chart1_div.innerHTML = "";
    // var chart1 = echarts.init($('#chart'));      // 这样写竟然是错误的,无法执行!! why!!
    var chart1 = echarts.init(document.getElementById('chart'));
    var data_entry = [];
    console.log(data_load, data_load.portion);
    for(var i=0; i<data_load.category.length; i++){
        data_entry.push({value: 100 * parseFloat(data_load.portion[i]), name: data_load.category[i]});
    }
    console.log(data_entry);
    // data_entry = [{value:335, name:'直接访问'},
    // 				{value:310, name:'邮件营销'},
    // 				{value:234, name:'联盟广告'},
    // 				{value:135, name:'视频广告'},
    // 				{value:1548, name:'搜索引擎'}
    // 			];

    // 根据提交的数据data_submit生成标题
    var title_text = "";
    if(data_submit.valid == 1){
        title_text = String(data_submit.year) + '年' + '票房份额'
    }else if(data_submit.valid == 2){
        title_text = String(data_submit.year) + '年' + String(data_submit.season) + '票房份额'
    }else if(data_submit.valid == 3){
        title_text = String(data_submit.year) + '年' + String(data_submit.month) + '月' + '票房份额'
    }

    var option1 = {
        backgroundColor: 'white',

        title: {
            text: title_text,
            //subtext: 'subtext',
            //left: 'center',
            x:'center',
            // top: 20,
            textStyle: {
                color: '#aaa'
            }
        },
        tooltip: {
            trigger: 'item',
            // formatter: "{a} <br/>{b} : {c} : {d}%"
            formatter: "{b} : {d}%"
        },
        legend: {
            orient : 'vertical',
            x : 'left',
            //data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
            data: data_load.category
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        series: [
            {
                name: '票房份额',
                type: 'pie',
                clockwise: 'true',
                startAngle: '0',
                radius: '60%',
                center: ['50%', '60%'],
                data: data_entry
            }
        ]
    };
    chart1.setOption(option1);
}



/*-----------------------------------------------------------------------------------*/
/*	绘制"年/季度/月"票房数据直方图 paint_chart2()
/*-----------------------------------------------------------------------------------*/
function paint_chart2(data_submit, data_load) {
    // 根据提交的数据data_submit生成标题
    var title_text = "";
    if(data_submit.valid == 1){
        title_text = String(data_submit.year) + '年' + '票房'
    }else if(data_submit.valid == 2){
        title_text = String(data_submit.year) + '年' + String(data_submit.season) + '票房'
    }else if(data_submit.valid == 3){
        title_text = String(data_submit.year) + '年' + String(data_submit.month) + '月' + '票房'
    }
    // var chart2 = echarts.init($('#chart'));
    var chart2 = echarts.init(document.getElementById('chart'));
    var option2 = {
        backgroundColor: 'white',
        //定义一个标题
        title: {
            text: title_text,
            // left: 'center',
            // x:'center',
            textStyle: {
                color: '#aaa'
            }
        },
        legend: {
            data: ['数据/万']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
		tooltip: {
            trigger: 'item',
            // formatter: "{a} <br/>{b} : {c}万"		// a 表示图例吧 "数据/万"
            formatter: "{b} : {c}万"
        },
        calculable : true,
        //X轴设置
        xAxis: {
            data: data_load.category,
            //设置字体倾斜
            axisLabel:{
                interval:0,
                rotate:45,//倾斜度 -90 至 90 默认为0
                margin:2,
                textStyle:{
                    fontWeight:"bolder",
                    // color:"#000000"
                }
            },
        },
        yAxis: {},
        //name=legend.data的时候才能显示图例
        series: [{
            name: '数据/万',
            type: 'bar',
            data: data_load.boxOffice
        }]
    };
    chart2.setOption(option2);
}


/*-----------------------------------------------------------------------------------*/
/*	绘制"年-年"票房变化趋势折线图 paint_chart3()
/*-----------------------------------------------------------------------------------*/
function paint_chart3(data_submit, data_load) {
    var chart3 = echarts.init(document.getElementById('chart'));
    //console.log(typeof(data_load.year1));		// Object
    year1 = [];
    year2 = [];
    for(var i=1; i<=12; i++){
        if(i<10){
            i = '0' + i;					// 将numer转化为string 09 string
            //console.log(i, typeof(i));
        }else{
            //i = String(i);				// 将numer转化为string 位数相同,可不需要 09 string
            //console.log(i, typeof(i));		// 10 "number"
        }
        year1.push(data_load.year1[i]);
        year2.push(data_load.year2[i]);
    }
    var option3 = {
        backgroundColor: 'white',
        //定义一个标题
        title: {
            text: String(data_submit.year1) + '年和' + String(data_submit.year2) + '年票房走势',
            // left: 'center',
            // x:'center',
            textStyle: {
                color: '#aaa'
            }
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: [data_submit.year1, data_submit.year2]
        },

        calculable: true,


        xAxis: [
            {
                axisLabel: {
                    rotate: 30,
                    interval: 0
                },
                axisLine: {
                    lineStyle: {
                        color: '#CECECE'
                    }
                },
                type: 'category',
                boundaryGap: false,
                data: [1,2,3,4,5,6,7,8,9,10,11,12]
            }
        ],
        yAxis: [
            {

                type: 'value',
                axisLine: {
                    lineStyle: {
                        color: '#CECECE'
                    }
                }
            }
        ],
        series: [
            {
                name: data_submit.year1,
                type: 'line',
                symbol: 'none',
                smooth: 0.2,
                color: ['#66AEDE'],
                data: year1
            },
            {
                name: data_submit.year2,
                type: 'line',
                symbol: 'none',
                smooth: 0.2,
                color: ['#90EC7D'],
                data: year2
            }
        ]
    };
    chart3.setOption(option3);
}




/*-----------------------------------------------------------------------------------*/
/*	绘制"年/top"最佳电影词云图 paint_chart4()
/*-----------------------------------------------------------------------------------*/
function paint_chart4(year, data_load) {
    var chart4 = echarts.init(document.getElementById('chart'));

    var data_entry = [];
    for (var i = 0; i < data_load.length; i++) {
        data_entry.push({
            name: data_load[i].name,
            value: data_load[i].total_box
        });
    }

    var option4 = {
        backgroundColor: 'white',
        title: {
            text: String(year) + "年度最佳电影 Top " + String(data_load.length),
            // left: 'center',
            // x:'center',
            // top: 20,
            textStyle: {
                color: '#aaa'
            }
        },
        tooltip: {},
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    // type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        series: [{
            type: 'wordCloud',
            gridSize: 20,
            sizeRange: [12, 50],
            rotationRange: [0, 0],
            shape: 'circle',
            textStyle: {
                normal: {
                    color: function () {
                        return 'rgb(' + [
                            Math.round(Math.random() * 160),
                            Math.round(Math.random() * 160),
                            Math.round(Math.random() * 160)
                        ].join(',') + ')';
                    }
                },
                emphasis: {
                    shadowBlur: 3,
                    shadowColor: '#333'
                }
            },
            data: data_entry
        }]
    };
    chart4.setOption(option4);
}


/*-----------------------------------------------------------------------------------*/
/*	绘制"年/top"劳模演员词云图 paint_chart5()
/*-----------------------------------------------------------------------------------*/
function paint_chart5(year, data_load) {
    var chart5 = echarts.init(document.getElementById('chart'));

    var data_entry = [];
    for (var i = 0; i < data_load.length; i++) {
        data_entry.push({
            name: data_load[i].name,
            value: data_load[i].times
        });
    }

    var option5 = {
        backgroundColor: 'white',
        title:{
            text:String(year) + "年度劳模演员 Top" + String(data_load.length),
            // left: 'center',
            // x:'center',
            // top: 20,
            textStyle: {
                color: '#aaa'
            }
        },
        tooltip: {},
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        series: [{
            type: 'wordCloud',
            gridSize: 20,
            sizeRange: [12, 50],
            rotationRange: [0, 0],
            shape: 'circle',
            textStyle: {
                normal: {
                    color: function() {
                        return 'rgb(' + [
                            Math.round(Math.random() * 160),
                            Math.round(Math.random() * 160),
                            Math.round(Math.random() * 160)
                        ].join(',') + ')';
                    }
                },
                emphasis: {
                    shadowBlur: 10,
                    shadowColor: '#333'
                }
            },
            data: data_entry
        }]
    };
    chart5.setOption(option5);
}

/*-----------------------------------------------------------------------------------*/
/*	绘制"年/top"劳模演员直方图 paint_chart6()
/*-----------------------------------------------------------------------------------*/
function paint_chart6(year, actors, times) {
    var chart6 = echarts.init(document.getElementById('chart'));
    var option6 = {
        backgroundColor: 'white',
        //定义一个标题
        title: {
            text: String(year) + '劳模演员 Top' + String(actors.length),
            // left: 'center',
            // x:'center',
            textStyle: {
                color: '#aaa'
            }
        },
		// tooltip: {
        //     trigger: 'item',
        //     formatter: "{a} <br/>{b} : {c} %"
        //     // formatter: "{b} : {d}%"
        // },
        legend: {
            data: ['参演电影数目/个']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                        funnel: {
                            x: '25%',
                            width: '50%',
                            funnelAlign: 'left',
                            max: 1548
                        }
                    }
                },
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        //X轴设置
        xAxis: {
            data: actors,
            //设置字体倾斜
            axisLabel:{
                interval:0,
                rotate:45,//倾斜度 -90 至 90 默认为0
                margin:2,
                textStyle:{
                    fontWeight:"bolder",
                    // color:"#000000"
                }
            },
        },
        yAxis: {},
        //name=legend.data的时候才能显示图例
        series: [{
            name: '参演电影数目/个',
            type: 'bar',
            data: times
        }]
    };
    chart6.setOption(option6);
}













