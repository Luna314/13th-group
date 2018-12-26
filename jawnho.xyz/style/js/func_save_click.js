// 点击Save(id=save)按钮导出图片
$(document).ready(function () {
        $("#save").click(function () {
			// 基于准备好的dom，初始化echarts实例
			//var myChart = echarts.init(document.getElementById('chart'));
			// 获取echarts中的canvas
			var mycanvas = $("#chart").find("canvas")[0];
			var image = mycanvas.toDataURL("image/jpeg");
			const data_submit = {
				img: image
			};
			console.log("Data Submit:", data_submit);
			$.post("ControllerPost.php", data_submit, function (status) {
                console.log('success');
                console.log("Save Status:", status);
				alert("保存成功!");
            }, "json").error(function () {
                alert("通讯失败");
            });
        })
    });
	
$(document).ready(function () {
        $("#clc").click(function () {
			data_submit = {
				func_id: 666
			};
			console.log("Data Submit: ", data_submit);
			$.get("ControllerGet.php", data_submit, function (data) {
				console.log('success');
				console.log("Data Loaded: ", data);
				// alert("已清空图表库");
				// location.reload([true]);
				window.location.href = "report.html";
			}, "json").error(function () {
				alert("通讯失败");
			});
		});
});
