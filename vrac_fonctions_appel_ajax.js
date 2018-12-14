// REMPLIR LES PATH

<script>

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
				url: "/path/createNewSessionId",
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
			})
		}

		function newIdFailed() {

		}

	// appelée toutes les 10 secondes après GO
		function sendLocationData() {

		// enregistrer dans les variables long et lat, à ce moment là
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
			} else {
				document.write("Geolocation is not supported by this browser.")
			}
			var now = new Date();
			var date = now.getDate();

			$.ajax({
				url: '/path/getLocationData',
				data: { user:sessionId, jour:date, long:position.coords.longitude, lat:position.coords.latitude },
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

		function sendLocationFailed() {

		}

	});

</script>
