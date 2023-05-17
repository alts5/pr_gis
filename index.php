<?php
include "connection.php";
?>
<!DOCTYPE>
<html>
<head>
	<title>ГИС "Мониторинг посадочных площадей"</title>
	<link rel="shortcut icon" href="design/favicon.svg" type="image/svg">
	<link rel = "stylesheet" href = "design/style.css">
	<script src="../jquery.min.js"></script>
	<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
	
	<style>
	html, body {
		margin:0;
		padding:0;
	}

	body {
		font-family: SEGOE UI;
		opacity: 0;
		animation: ani 2.5s forwards;
	}
	
	a {
		text-decoration:none;
		cursor: pointer;
	}

	a:hover {
		text-decoration:underline;
		cursor:pointer;
	}
	
	
	.navigation {
		width:100%;
		background-color: #191970;
		top: 0;
		position: fixed;
		
	}

	.navigation table {
		border-collapse:collapse;
		width:100%;	
		color: white;
		font-size: 16px;
	}

	.navigation td {
		padding: 20px;
	}

	::-webkit-scrollbar { 
		width: 6px; 
		height: 5px;
	}
	
	::-webkit-scrollbar-track {  
		background-color: #191970;
	}
	
	::-webkit-scrollbar-track-piece { 
		background-color: white;
	}
	
	::-webkit-scrollbar-thumb { 
		height: 5px; 
		background-color: #191970; 
	}
		
	.main_body_menu {
		border-collapse: collapse; 
		background:white;
		width: 24%; 
		box-shadow: 0 0 10px rgba(0,0,0,0.1); 
		vertical-align:top; padding:61px 0 0 0;
	}
	
	.main_body {
		width: 100%;
		border-collapse: collapse;
		padding: 20px;
		width: 100%;
		height: 100%;
	}
	
	.main_body_headers {
		color: #363a48;
		font-weight: 700;
		font-size: 20px;
		border-bottom: 1px solid #b3b3b3;
		margin: 20px 10px 10px;
		padding-bottom: 15px;
	}
	
	.main_body_headers_mini {
		color: #363a48;
		font-weight: 700;
		font-size: 14px;
	}
	
	@keyframes ani {
		0% {opacity: 0;}
		100% {opacity: 1;}
	
	}
	
	.main_body_button {
		background: #191970	;
		color: white;
		text-align: center;
		padding: 12px;
		font-size: 14px;
		bottom: 0;
		cursor: pointer;
	}
	
	.main_body_button:hover {
		background: #00008b;
	}
	
	.main_body_button:active {
		background: #0000cd;
	}
	
	button {
		border: none;
	}
	
	.main_body_message {
		text-align:center;
		color: #7f7f7f;
	}
	
	#newFieldWinWrap, #cardFieldWinWrap  {
		display: none;
		opacity: 0.8;
		position: fixed;
		left: 0;
		right: 0;	
		top: 0;
		bottom: 0;
		padding: 10px;
		background-color: rgba(1, 1, 1, 0.725);
		z-index: 100;
		overflow: auto;
	}
 
	#newFieldWinWindow, #cardFieldWinWindow {
		width: 480px;
		height: 190px;
		margin: auto auto;
		display: none;
		background: #fff;
		z-index: 200;
		position: fixed;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
		border-radius: 3px;
		text-align: left;
	}
	
	.main_body_window_item {
		background-color: #191970; 
		text-align: left; 
		padding: 10px; 
		
	}
	
	.main_body_input{
		width: 100%;
		border: none;
		border-bottom: 1px solid grey;
		padding: 10px 0;
		margin: 10px 0 0px;
		font-size: 14px!important;
		outline: none;
		margin: 0 0 10px 0;
	}
	
	.main_body_window_content {
		padding: 20px 20px 0 20px; 
		overflow-y:none;
	}
	
	.main_body_hidden {
		display:none;
	}
	
	.main_body_empty_message {
		color: #ff5656;
		display:none;
		font-size: 13px;
		text-align: left;
	}
	
	.main_body_table {
		border-collapse:collapse;
		width: 100%;
	}
	
	.main_body_table th {
		padding: 10px;
		border-bottom: 1px solid grey;
		font-size: 13px;
		text-align:center;
		font-weight: 600;
	}
	
	.main_body_table td {
		padding: 10px;
		font-size: 14px;
		text-align:center;
	}
	
	.main_body_table tr:nth-child(2n) {
		background: #eee;
	}
	
	.seazonTr:hover {
		text-decoration: underline;
		cursor: pointer;
	}
	.seazonTr {
		text-decoration: none;
		cursor: pointer;
	}
	</style>
	<script type="text/javascript">

	function newFieldWindow(state){
		$('.leaflet-interactive path').remove();
		$('#newFieldWinWindow').css('display', state);
		$('#newFieldWinWrap').css('display', state);
	}


	function cardFieldWindow(state, fieldId) {
		$('.leaflet-interactive path').remove();

		if (state == "block")
			$.post('/data_getter', {'getting_data': 'field_data', 'id' : fieldId}, function(data){
				var data_array = data.split(";");
				$("#itemCard").val(data_array[1]);
				$("#codeCard").val(data_array[2]);
				$("#squareCard").val(data_array[3]);
			});
			
			$.post('/data_getter', {'getting_data': 'field_history', 'id' : fieldId}, function(data){
				data_array = data.split(";");
				$("#field_using_history").empty();
				for (var i = 0; i < data_array.length-1; i++) {
					data_val = data_array[i].split(",");
					$("#field_using_history").append('<tr class = "seazonTr"><td style = "width: 20%">'+data_val[0]+'</td><td style = "width: 50%">'+data_val[1]+'</td><td style = "width: 30%">'+data_val[2]+'</td></tr>');
				}
			});
		$('#cardFieldWinWindow').css('display', state);
		$('#cardFieldWinWrap').css('display', state);
		$('#fieldIdInfo').val(fieldId);
		
	}
	
	function formChecker(form, elem) {
		var js = 0;
		for (var  i = 0; i < elem.length; i++) {
			if ($('#' + elem[i]).val() == "") {
				$('#' + elem[i]).css('border-bottom','1px solid #ff5656');
				$('#' + form + 'Error').css('display','block');
			}
			else {
				$('#' + elem[i]).css('border-bottom','1px solid grey');
				$('#' + form + 'Error').css('display','none');
				js++;
			}
		}
		if (js == elem.length) $('#'+form).submit();
	}
	
	var coord = [];
	DG.then(function() {
		var map,
		map = DG.map('map', {
			center: [55.230, 51.957],
			zoom: 13
		});

		document.getElementById("showOnMapBtn").onclick = function() {
			$('.leaflet-interactive path').remove();
			$.post('/data_getter', {'getting_data': 'field_coordinates', 'id' : $("#fieldIdInfo").val()}, function(data){
				var arr = [[[]]];
							
				var pr1 = data.split(";");	
				pr1.pop();
				
				for (var i = 0; i < pr1.length; i++) {
					for (var j = 0; j < 2; j++) {
						temp = (pr1[i].split(","));
					}
					arr[0][i] = temp;
				}
				
				for (var i = 0; i < pr1.length; i++) {
					for (var j = 0; j < 2; j++) {
						if (!isNaN(arr[0][i][j])) {
							arr[0][i][j] = Number(arr[0][i][j]);
						}
					}
				}
			
				
				var data2 = [
                    {
                        "type": "Feature",
                        "properties": {
                            "info": "Я полигон"
                        },
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": arr,
                        }
                    }
                ];
				
				
                DG.geoJson(data2, {
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup(feature.properties.info);
                    }
                }).addTo(map);

			});
			$("#cardFieldWinWrap").css("display","none");
			$("#cardFieldWinWindow").css("display","none");
		}
		
		ptr = DG.icon({		
			iconUrl: 'design/pointer.svg',
			iconSize: [36, 38]
		});
		marker = DG.marker([55.230, 51.957], {
			draggable: true,
			icon: ptr
		}).addTo(map);
		
		

		marker.on('click', (e) => {
			var normalIcon = L.icon({iconUrl: "design/pointer.svg"}),
			clickedIcon = L.icon({iconUrl: "design/pointer_pressed.svg"});
			if (flag == 1 ) {
				e.target.setIcon(clickedIcon);
				var lat = e.target._latlng.lat.toFixed(3),
				lng = e.target._latlng.lng.toFixed(3);
				
				if (coord.indexOf(lat + ';' + lng) == -1) {
					coord.push(lat + ';' + lng);
					var dunix = String(Date.now());
					dunix = dunix.slice(7);
					$('#pointersList').append(
						"<table style = 'border: none; width: 100%; border-bottom: 1px solid #363a48;' id = '" + dunix + "'><tr><td><div style = 'padding:10px;  display: inline-block; width: 90%'> Точка c координатой (" + lat + "; " + lng + ")</div>"
						+ "</td><td><div style = 'cursor: pointer'><img onclick = deletePtr('" + lat +";"+ lng + "','" + dunix + "') style = 'width: 12px' src = 'design/close_grey.svg'></div></td></tr></table>"
					);
					
				if ($('#emptyPointersListMessage').is(":visible") && !$('#pointersList').is(':empty'))  {
					$('#emptyPointersListMessage').css("display","none");
					$('#cancelPtr').text("Сохранить границы");
					$('#cancelPtr').attr("onclick", "cancelBtnHandler('Create')");
				}
				else if ($('#pointersList').is(':empty')) {
					$('#emptyPointersListMessage').css("display","block");
					$('#cancelPtr').text("Отменить");
					$('#cancelPtr').attr("onclick", "cancelBtnHandler('Cancel')");
				}
				else {
					$('#emptyPointersListMessage').css("display","none");
					$('#cancelPtr').text("Сохранить границы");
					$('#cancelPtr').attr("onclick", "cancelBtnHandler('Create')");
				}
					$('#fieldCoordsForm').val(coord);
				}	
			}
			setTimeout(function(){e.target.setIcon(normalIcon)},100);
			});
		});

	var flag = 0;
	
	function deletePtr(val, id) {
		$("#" + id).remove();
		delete coord[coord.indexOf(val)];
		
		if ($('#emptyPointersListMessage').is(":visible") && !$('#pointersList').is(':empty'))  {
			$('#emptyPointersListMessage').css("display","none");
			$('#cancelPtr').text("Сохранить границы");
			$('#cancelPtr').attr("onclick", "cancelBtnHandler('Create')");
		}
		else if ($('#pointersList').is(':empty')) {
			$('#emptyPointersListMessage').css("display","block");
			$('#cancelPtr').text("Отменить");
			$('#cancelPtr').attr("onclick", "cancelBtnHandler('Cancel')");
		}
		else {
			$('#emptyPointersListMessage').css("display","none");
			$('#cancelPtr').text("Сохранить границы");
			$('#cancelPtr').attr("onclick", "cancelBtnHandler('Create')");
		}
	}
	
	var menuCode;
	
	function selectFieldConturs() {
		$('#newFieldWinWindow').css('display', 'none');
		$('#newFieldWinWrap').css('display', 'none');
		menuCode = $('#leftMenu').html();
		$('#leftMenu').html("");
		$('#leftMenu').html(
			"<div class = 'main_body_headers'>Выбор границ посадочной площади на карте</div><div class = 'main_body_message' id = 'emptyPointersListMessage'>Перемещайте метку на карте в нужное место. Для выбора точки нажмите метку</div><div style = 'overflow:auto; height: 84%; padding: 10px' id = 'pointersList'></div>"
		+ "<button class = 'main_body_button' onclick = cancelBtnHandler('Cancel') style = 'position: fixed; width: 24%' id = 'cancelPtr'>Отменить добавление площади</button>"
		);
		flag = 1;
	}
	
	function cancelBtnHandler(mode) {
		if (mode != "Cancel") 
			newFieldWindow("block");
		else
			$('#leftMenu').html(menuCode)
	}
	

	function addEventHistory() {
		$('#field_using_history').prepend('<tr class = "seazonTr">'
		+ '<td style = "width: 20%"><input placeholder = "2022" name = "seazon[]" class = "main_body_input" style = "text-align-last:center"></td>'
		+ '<td style = "width: 50%"><input placeholder = "Гречиха посевная" name = "plant[]" class = "main_body_input" style = "text-align-last:center"></td>'
		+ '<td style = "width: 30%"><input placeholder = "1893" name = "volume[]" class = "main_body_input" style = "text-align-last:center"></td>'
		+ '</tr>'
		);
	}

	</script>

</head>
<body >
	<!--Верхняя панель-->
	<div class = "navigation">
		<table>
			<tr>
				<td style = 'text-transform: uppercase; font-weight:400'>ИС "Мониторинг посадочных площадей"</td>
			</td>
		</table>
	</div>
	
	<!--Основная часть страницы-->
	<table class = "main_body">
		<tr>
			<td class = "main_body_menu" id = "leftMenu">
				<div class = 'main_body_headers'>Список посадочных площадей</div>
				<button class = "main_body_button" onclick = "newFieldWindow('block')" style = "position: fixed; width: 24%;">Добавить площадь</button>
				<?php
					
					$fields_list_sql = mysql_query("SELECT * FROM fields");
					if (mysql_num_rows($fields_list_sql) < 1) {
						print '<div class = "main_body_message">Сведения не найдены</div>';
					} else {
						print '<div style = "overflow:auto; height: 84%; padding: 10px">';
						$i = 1;
						while ($field = mysql_fetch_array($fields_list_sql)) {
							print '<div style = "padding:10px; border-bottom: 1px solid #363a48"><a onclick = cardFieldWindow("block","'.$field["id"].'")> #'.$field['code'].'. '.$field["item"].'</a></div>';
							$i++;
						}
						print '</div>';
					}
				?>
			</td>
			    <body>
        
			<td id="map">
			</td>		
		</tr>
	</table>
	
	<!--Окно добавления новой посадочной площади-->
	
	<div id="newFieldWinWrap"></div>
	<div id="newFieldWinWindow" style = 'height:390px' >
		<div class = "main_body_window_item" >
			<table style = 'width: 100%'><tr><td style = "color: white; font-size: 14px;">Добавление посадочной площади</td><td onclick = "newFieldWindow('none')"><img src = 'design/close.svg' style = 'width: 10px; cursor: pointer'></td></tr></table>		
		</div>
		<div class = 'main_body_window_content'>
			<form action = '../processor' method = 'post' id = 'newForm'>
				<div class = 'main_body_headers_mini'>Наименование посадочной площади</div>
				<input id = 'item' name = 'item' required  autocomplete = 'off' class = 'main_body_input' placeholder = 'Например, Опытное поле Аксаринского сельского поселения' >
				<div class = 'main_body_headers_mini'>Площадь по документам (в гектарах)</div>
				<input id = 'square' name = "square" required  autocomplete = 'off' class = 'main_body_input' placeholder = 'Например, 18' >
				<div class = 'main_body_headers_mini'>Код посадочной площади</div>
				<input id = 'code' name = "code" required  autocomplete = 'off' class = 'main_body_input' placeholder = 'Например, 826у' >
				<input class = "main_body_hidden" name = "mode" value = "new">
				<input class = "main_body_hidden" name = "coords" id = "fieldCoordsForm">
			</form>
			<div class = 'main_body_headers_mini'>Границы посадочной площади на карте</div>
			<div style = "padding: 10px 0; font-size: 13px"><a onclick = "selectFieldConturs()">Указать границы посадочной площади на карте</a></div>
		</div>
		
			
		<table style = 'width: 100%; text-align: right; padding: 0 20px 5px'>
		<tr>
			<td class = "main_body_empty_message" id = "newFormError">Заполните выделенные поля</td>
			<td><button class = "main_body_button" onclick = 'formChecker("newForm", ["item","square","code"])'>Создать</button></td>	
		</tr>
		</table>
	</div>
	
	
		<!--Окно просмотра информации о посадочной площади-->
	
	<div id="cardFieldWinWrap"></div>
	<div id="cardFieldWinWindow" style = 'height:600px; width: 800px' >
		<div class = "main_body_window_item" >
			<table style = 'width: 100%'><tr><td style = "color: white; font-size: 14px;">Карточка посадочной площади</td><td onclick = "cardFieldWindow('none')"><img src = 'design/close.svg' style = 'width: 10px; cursor: pointer; text-align: right'></td></tr></table>		
		</div>
		<div class = 'main_body_window_content' id = 'infoMainContent'>
			<form action = '../processor' method = 'post' id = 'infoForm'>
				<div class = 'main_body_headers_mini'>Наименование посадочной площади</div>
				<input id = 'itemCard' name = 'item' required  autocomplete = 'off' class = 'main_body_input' placeholder = 'Например, Опытное поле Аксаринского сельского поселения' >
				<div class = 'main_body_headers_mini'>Площадь по документам (в гектарах)</div>
				<input id = 'squareCard' name = "square" required  autocomplete = 'off' class = 'main_body_input' placeholder = 'Например, 18' >
				<div class = 'main_body_headers_mini'>Код поля</div>
				<input id = 'codeCard' name = "code" required  autocomplete = 'off' class = 'main_body_input' placeholder = 'Например, 826у' >
				<input class = "main_body_hidden" name = "mode" value = "change">
				<input class = "main_body_hidden" name = "id" id = "fieldIdInfo">
				
			
			<div class = 'main_body_headers_mini'>История использования посадочной площади</div>
			<table class = "main_body_table">
					<tr>
						<th style = "width: 20%">Сезон</th><th style = "width: 50%">Засеянная культура</th><th style = "width: 30%">Объем собранного урожая, в т.</th>
					</tr>					
			</table>
			
			<div style = "overflow:auto; height: 200px">
				<table class = "main_body_table" id = "field_using_history">			
				</table>
			</div>
			</form>
		</div>
		<table style = 'width: 100%; padding: 10px 20px 5px'>
			<tr>
				<td style = 'text-align: left'><button class = "main_body_button" id = 'addToHistory' onclick = 'formChecker("infoForm", ["itemCard","squareCard","codeCard"])' >Обновить сведения</button></td>	
				<td style = 'text-align: center'><button class = "main_body_button" id = 'addToHistory' onclick = 'addEventHistory()' >Добавить сведения в историю</button></td>	
				<td style = 'text-align: right'><button class = "main_body_button" id = 'showOnMapBtn' >Показать на карте</button></td>	
			</tr>
		</table>
	</div>
</body>
</html>
