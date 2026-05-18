window.onload = function(){
    var checkin = document.getElementById("checkin").value;
    var checkout = document.getElementById("checkout").value;
    var guests = document.getElementById("guests").value;

    if(checkin != "" && checkout != "" && guests != ""){
        var xhttp = new XMLHttpRequest();

        xhttp.open("GET", "../Controller/availableRooms.php?ajax=1&checkin=" + checkin + "&checkout=" + checkout + "&guests=" + guests, true);

        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                var rooms = JSON.parse(this.responseText);

                var output = "<table border='1'>";
                output += "<tr>";
                output += "<th>Room Type</th>";
                output += "<th>Amenities</th>";
                output += "<th>Price Per Night</th>";
                output += "<th>Total Price</th>";
                output += "<th>Action</th>";
                output += "</tr>";

                if(rooms.length > 0){
                    for(var i = 0; i < rooms.length; i++){
                        output += "<tr>";
                        output += "<td>" + rooms[i].name + "</td>";
                        output += "<td>" + rooms[i].amenities + "</td>";
                        output += "<td>" + rooms[i].price_per_night + "</td>";
                        output += "<td>" + rooms[i].total_price + "</td>";
                        output += "<td><a href='bookingForm.php?room_type_id=" + rooms[i].id + "&checkin=" + checkin + "&checkout=" + checkout + "&guests=" + guests + "'>Book Now</a></td>";
                        output += "</tr>";
                    }
                }else{
                    output += "<tr>";
                    output += "<td colspan='5'>No available room found</td>";
                    output += "</tr>";
                }

                output += "</table>";

                document.getElementById("roomResult").innerHTML = output;
            }
        };

        xhttp.send();
    }
}