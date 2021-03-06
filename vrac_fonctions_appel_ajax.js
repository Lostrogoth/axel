
	var sessionId = 0;

	var lat = 0;
	var long = 0;

	$(function() {

	// Mettons que le nom de ton boutton c'est startBtn
		$('#startBtn').click(function() {

			receiveNewSessionId();
		});

	// quand l'user click sur GO
		function receiveNewSessionId() {

			$.ajax({
				url: "./database_functions.php/createNewSessionId",
				success: function(data) {

					if (data && data > 0) {
						sessionId = data;

						setInterval(sendLocationData(), 10000);
					}
					else {
						newIdFailed();
						console.log("no data - data = "+data);
					}
				},
				error: function(data) {
					newIdFailed();
					console.log("l'appel de la fonction 'createNewSessionId' a échoué.");
					console.log(data);
				}
			});
			console.log("id = "+sessionId);
		}

		function newIdFailed() {

		}

	// appelée toutes les 10 secondes après GO
		function sendLocationData() {

		// enregistrer dans les variables long et lat, à ce moment là
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(putPosInVar);
			} else {
				document.write("Geolocation is not supported by this browser.")
			}
			var now = new Date();
			var date = now.getDate();

			$.ajax({
				url: './database_functions.php/getLocationData',
				data: { user:sessionId, jour:date, long:long, lat:lat},
				type: 'post',
				success: function(data){
					console.log("success");
					console.log(data);
				},
				error : function(data) {
					sendLocationFailed();
					console.log("l'appel de la fonction 'getLocationData' a échoué.");
					console.log(data);
				}
			});
		}

		function putPosInVar(position) {
			var long = position.coords.longitude;
			var lat = position.coords.latitude;
		}

		function sendLocationFailed() {

		}

	});