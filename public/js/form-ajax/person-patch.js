function editChildren(event) {
    event.preventDefault();
    var sendData = $('#editPerson').serializeArray();
    if ((sendData.indexOf('=&') > -1)) {
        customPopUpMin($('#emptySubmit'));
        return;
    }
    console.log(($('#editPerson').data("default")));

    //get default values

    //parse from array to json format
    var ret = {"roles":[], address: {}};
    console.log(sendData);
    for (var i = 0; i < sendData.length; i++) {
        // console.log(sendData[i]);
        if (sendData[i].value === "" || sendData[i].value === "Neznámé")
            continue;
        if (sendData[i].name === "roles") {
            ret[sendData[i].name].push(sendData[i].value);
        } else if(sendData[i].name.search("address") > -1) {
            console.log(sendData[i].defaultValue);
            ret.address[sendData[i].name.split('.')[1]] = sendData[i].value;
        } else {
            if (sendData[i].value === "false") {
                ret[sendData[i].name] = false;
            } else if (sendData[i].value === "true") {
                ret[sendData[i].name] = true;
            } else {
                ret[sendData[i].name] = sendData[i].value;
            }
        }
    }

    $.ajax({
        type: "PATCH",
        headers: {'Content-Type': 'application/json'},
        url: "/api/people/"+ret.id,
        data: JSON.stringify(ret)
    }).done(function() {
        $('#people').DataTable().ajax.reload();
    });
}