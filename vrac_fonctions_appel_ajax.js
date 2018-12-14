
	var sessionId = 0;

	var lat = 0;
	var long = 0;

	$(function() {

	// Mettons que le nom de ton boutton c'est startBtn
		$('#startBtn').click(function() {

			receiveNewSessionId();

			setInterval(sendLocationData(), 10000);
		});

	// quand l'user click sur GO
		function receiveNewSessionId() {

			$.ajax({
				url: "./database_functions.php/createNewSessionId",
				success: function(data) {

					if (data && data > 0)
						sessionId = data;
					else{
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
			console.log(sessionId);
		}

		function newIdFailed() {

		}

	// appelée toutes les 10 secondes après GO
		function sendLocationData() {

		// enregistrer dans les variables long et lat, à ce moment là
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition();
			} else {
				document.write("Geolocation is not supported by this browser.")
			}
			var now = new Date();
			var date = now.getDate();

			$.ajax({
				url: './database_functions.php/getLocationData',
				data: { user:sessionId, jour:date, long:long, lat:lat},
				type: 'post',
				success: function(){
					console.log("success");
				},
				error : function(data) {
					sendLocationFailed();
					console.log("l'appel de la fonction 'getLocationData' a échoué.");
					console.log(data);
				}
			});
		}

		function putPosInVar() {
			var long = position.coords.longitude;
			var lat = position.coords.latitude;
		}

		function sendLocationFailed() {

		}

	});