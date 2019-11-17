<?php
	$sROOT = "http://".$_SERVER['SERVER_NAME'].substr(substr(__FILE__, strlen(realpath($_SERVER['DOCUMENT_ROOT']))), 0, - 11 - strlen(basename(__FILE__)));

	print "<link type='text/css' href='$sROOT/css/tablesorter_blue/style.css' rel='stylesheet' />";
	print "<link type='text/css' href='$sROOT/css/grass/jquery-ui-1.8.21.custom.css' rel='stylesheet' />";
	print "<script type='text/javascript' src='$sROOT/js/jquery-1.7.2.min.js'></script>";
	print "<script type='text/javascript' src='$sROOT/js/jquery-ui-1.8.20.custom.min.js'></script>";
	print "<script type='text/javascript' src='$sROOT/js/jquery-ui-slideaccess.js'></script>";
	print "<script type='text/javascript' src='$sROOT/js/jquery-ui-timepicker-addon.js'></script>";
	print "<script type='text/javascript' src='$sROOT/js/jquery.tablesorter.js'></script>";
	print "<script type='text/javascript' src='$sROOT/js/jquery.popupWindow.js'></script>";

?>
	<script type="text/javascript">
		$(function(){
			var formato_data = "dd-mm-yy"
			var formato_data2 = "dd/mm/yy"
			var nomes_mes = ["Janeiro","Fevereiro","Marco","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"]
			var nomes_dias = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"]

			// --- [ Datepicker ] ---------------------------------------------------
			$( "#idData, #idD_ini, #idD_fim, #idDataInicial, #idDataFinal " ).each(function(){
				$(this).datepicker({
					dateFormat: formato_data,
					dayNamesMin: nomes_dias,
					monthNames: nomes_mes
				});
			});

			$( "#idDataSaida, #idDataDevolucao, #idDataCompra, #comp_data, #comp_data_compra, #idDatebuy, #idDataAdmis, #idDataInc" ).each(function(){
				$(this).datepicker({
	                                dateFormat: formato_data2 ,
                                        dayNamesMin: nomes_dias,
                                        monthNames: nomes_mes });
			});

			// --- [ TimePicker ] --------------------------------------------------

			$("#idDate_schedule, #idDataAgendamento").each(function(){
				$(this).datetimepicker({
					dateFormat: "dd/mm/yy",
					timeFormat: 'hh:mm:ss',
					addSliderAccess: true,
					dayNamesMin: nomes_dias,
					monthNames: nomes_mes
				});
			});

			// --- [ Tabelas Ordenadas ] --------------------------------------------

	                $(document).ready(function(){
				$("#tabela_consultgeral").tablesorter();
			});

			// --- [ Auto Completar ] ----------------------------------------------

			// Autocompeltar Contatos
			$("#idContato, #idQuem").each(function(){
				$(this).autocomplete({source: 'autocompletar.php?search=contatos',minLength:2});
			});

			// Autocompletar Computadores
			$("#idEtiqueta, #comp_inv, #idComp_inv").each(function(){
				$(this).autocomplete({source: 'autocompletar.php?search=computers',minLength:2});
			});

			//autocompletar Ramais
			$("#idRamal, #idTelefone").each(function(){
				$(this).autocomplete({source: 'autocompletar.php?search=ramais',minLength:2});
			});

			//autocompletar Ocorrencias
//			$("#idRamal, #idTelefone").each(function(){
//				$(this).autocomplete({source: 'autocompletar.php?search=ocorrencias',minLength:2});
//			});


		// --- [ NOTIFICACOES DA AREA DE TRABALHO ] ------------------------------------

		Notification = {
			support: function(){
				if(window.webkitNotifications){
					return true;
				} else {
					return false;
				}
			},

 			permission: function(){
				return window.webkitNotifications.checkPermission();
			},

			requestPermission: function(callback){
				window.webkitNotifications.requestPermission(function() {
					if (callback) {
						callback(window.webkitNotifications.checkPermission() == 0);
					}
				});
			},

			notify: function(icon, title, text, expires){
				if(Notification.permission() == 0){
					var notify = window.webkitNotifications.createNotification(icon, title, text);
					notify.show();

					if(expires){
						setTimeout(function(){
							notify.cancel();
						},
						expires
					)};
				}
			}
		} // FIM DA VARIAVAL Notification


	// --- [ Janelas de Popup ] -----------------------------------------

		function popup_jquery(pagina,tamX,tamY) {
			$('#popup_modal').dialog({
				modal: true,
				open: function ()
				{
					$(this).load(pagina);
				},
				height: tamX,
				width: tamY
			});
		}

		$("#alert_click_error").effect( "pulsate", { times: 3 }, 700 , function(){
			browsermsg_text_firefox = "<b>Voce esta utilizando o Mozilla Firefox como navegador!<br>    Deseja baixar o complemento para Notificacoes da area de trabalho??</b>";

			$(this).click(function(){
				msg = "Alertas da Area de Trabalho nao Suportados para utilizar este recurto,\nutilize navegadores como Mozilla Firefox ou Google Chrome!";
				alert(msg);
				   if (BrowserDetect.browser == "Firefox"){
					$("<p>" + browsermsg_text_firefox +  "</p>").dialog({
						resizable: false,
						height:200,
						width: 350,
						modal: true,
						title: "Instalar Complemento????",
						buttons: {
							"instalar Complemento": function() {
								$( this ).dialog( "close" );
								var url = "http://" + window.location.hostname + "/public/downloads/tab_notifier-2.13-sm+fx-linux.xpi";
								janela_popup = window.open(url,"Download - Complemento","location=1,status=1,scrollbars=1, width=100,height=100");
							},
							"Cancelar": function() {
								$( this ).dialog( "close" );
							}
						}
					});
				    }
			});
		});

		$("#alert_click_warning").effect( "pulsate", { times: 3 }, 700 , function(){
			$(this).click(function(){
				alert("Alertas Nao Habilitados\n      Ou nao permitidos.");
				permit_notifications();
			});
		});

		$("#ver_chamado").dialog();

	});


	function permit_notifications(){
		Notification.requestPermission(function(){alert("Notificacoes Habilitadas com Sucesso!!!")});
	}

        function check_notifications(nChamados){
		if (!window.webkitNotifications){
			document.write("<td> | </td><td><input type='image' id='alert_click_error' src='includes/imgs/warning_nsupported.png' width='21px' height'21px'>");
		} else {
			if ( window.webkitNotifications.checkPermission() != 0){
				document.write("<td> | </td><td><input type='image' id='alert_click_warning' src='includes/imgs/warning_disabled.png' width='29px' height'29px'>");
			} else {
				mostra_notifications();
			}
		}
	}

	var auto_refresh_notifications = setInterval(function(){

	     $(document).ready(function mostra_notifications(){
		   $.ajax({
		   url: "ocomon/geral/verifica_chamados.php",
		   context: document.body
		}).done(function(nChamados){
			if(nChamados >= 1){
				Notification.notify(
					'./includes/icons/fiv.gif',
					document.title,
					'Voce tem ' + nChamados + ' chamado(s)!',
					5000
					);
				}
			});
		}); // FIM DA FUNCAO MOSTRA_NOTIFICACAO
	}, 500000 ); // EM MILISEGUNDOS

	var BrowserDetect = {
		init: function () {
			this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
			this.version = this.searchVersion(navigator.userAgent)
				|| this.searchVersion(navigator.appVersion)
				|| "an unknown version";
			this.OS = this.searchString(this.dataOS) || "an unknown OS";
		},
		searchString: function (data) {
			for (var i=0;i<data.length;i++)	{
				var dataString = data[i].string;
				var dataProp = data[i].prop;
				this.versionSearchString = data[i].versionSearch || data[i].identity;
				if (dataString) {
					if (dataString.indexOf(data[i].subString) != -1)
						return data[i].identity;
				}
				else if (dataProp)
					return data[i].identity;
			}
		},
		searchVersion: function (dataString) {
			var index = dataString.indexOf(this.versionSearchString);
			if (index == -1) return;
			return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
		},
		dataBrowser: [
			{
				string: navigator.userAgent,
				subString: "Chrome",
				identity: "Chrome"
			},
			{ 	string: navigator.userAgent,
				subString: "OmniWeb",
				versionSearch: "OmniWeb/",
				identity: "OmniWeb"
			},
			{
				string: navigator.vendor,
				subString: "Apple",
				identity: "Safari",
				versionSearch: "Version"
			},
			{
				prop: window.opera,
				identity: "Opera",
				versionSearch: "Version"
			},
			{
				string: navigator.vendor,
					subString: "iCab",
				identity: "iCab"
			},
			{
				string: navigator.vendor,
				subString: "KDE",
				identity: "Konqueror"
			},
			{
				string: navigator.userAgent,
				subString: "Firefox",
				identity: "Firefox"
			},
			{
				string: navigator.vendor,
				subString: "Camino",
				identity: "Camino"
			},
			{		// for newer Netscapes (6+)
				string: navigator.userAgent,
				subString: "Netscape",
				identity: "Netscape"
			},
			{
				string: navigator.userAgent,
				subString: "MSIE",
				identity: "IExplorer",
				versionSearch: "MSIE"
			},
			{
				string: navigator.userAgent,
				subString: "Gecko",
				identity: "Mozilla",
				versionSearch: "rv"
			},
			{ 	// for older Netscapes (4-)
				string: navigator.userAgent,
				subString: "Mozilla",
				identity: "Netscape",
				versionSearch: "Mozilla"
			}
		],
		dataOS : [
			{
				string: navigator.platform,
				subString: "Win",
				identity: "Windows"
			},
			{
				string: navigator.platform,
				subString: "Mac",
				identity: "Mac"
			},
			{
				   string: navigator.userAgent,
				   subString: "iPhone",
				   identity: "iPhone/iPod"
		    },
			{
				string: navigator.platform,
				subString: "Linux",
				identity: "Linux"
			}
		]

	};

	BrowserDetect.init();

        function permit_notifications(){
            Notification.requestPermission(function(){alert("Notificacoes Habilitadas com Sucesso!!!")});
        }

	</script>
   </head>
</html>
