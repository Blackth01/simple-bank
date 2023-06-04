const url_domain = "/api";

function show_alert(msg, type, text){
  if(text === undefined){
    text = "";
  }

  Swal.fire({
    position: 'top-end',
    width: 300,
    icon: type,
    title: msg,
    html: text,
    showConfirmButton: true,
    backdrop: false,
    timer: 10000
  })
}

function fetch_data(endpoint, handle_data){
  var request_code = 0;

  fetch(url_domain+endpoint)
    .then(response => {
      if(response.status != 500){
        request_code = response.status;
        return response.json();
      }
      else{
        throw new Error('Internal server error while retrieving data');
      }
    })
    .then(data => {
      if(handle_data){
        data.statusCode = request_code;
        handle_data(data);
      }
      else{
        console.log("Request finished successfully!");
      }
    })
    .catch(error => {
      show_alert("An error occurred while retrieving data!", "error");
      console.log("An error occurred while retrieving data! "+error)
    });
}


function fetch_single_data(endpoint, id, handle_data){
  var request_code = 0;

  fetch(url_domain+endpoint+"/"+id)
    .then(response => {
      if(response.status != 500){
        request_code = response.status;
        return response.json();
      }
      else{
        throw new Error('Internal server error while retrieving data');
      }
    })
    .then(data => {
      if(handle_data){
        data.statusCode = request_code;
        handle_data(data);
      }
      else{
        console.log("Request finished successfully!");
      }
    })
    .catch(error => {
      show_alert("An error occurred while retrieving data!", "error");
      console.log("An error occurred while retrieving data! "+error);
    });
}

function delete_data(endpoint, id, handle_data){
  var request_code = 0;

  fetch(url_domain+endpoint+"/"+id, {method: "DELETE"})
    .then(response => {
      if(response.status != 500){
        request_code = response.status;
        return response.json();
      }
      else{
        throw new Error('Internal server error while deleting data');
      }
    })
    .then(data => {
      if(handle_data){
        data.statusCode = request_code;
        handle_data(data);
      }
      else{
        console.log("Request finished successfully!");
      }
    })
    .catch(error => {
      show_alert("An error occurred while deleting data!", "error");
      console.log("An error occurred while deleting data! "+error);
    });
}

function commit_data(endpoint, handle_data){
  let payload = {}

  fields_used.forEach(
    (element) => {
      payload[element] = document.getElementById(element).value;
    }           
  );

  let id_to_update = document.getElementById("id_to_update").value;
  let method = "POST";

  if(id_to_update){
    if(isNaN(parseInt(id_to_update))){
      show_alert("Error while updating data: ID is not a number!", "error");
      return false;
    }
    endpoint+="/"+id_to_update;
    method = "PUT";
  }

  var request_code = 0;

  fetch(url_domain+endpoint, {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    method: method,
    body: JSON.stringify(payload)
  }).then(function(response) {
      if(response.status != 500){
        request_code = response.status;
        return response.json();
      }
      else{
        throw new Error('Internal server error while retrieving data');
      }
  }).then(function(data) {
      if(handle_data){
        data.statusCode = request_code;
        data.requestMethod = method;
        handle_data(data);
      }
      else{
        console.log("Request finished successfully!");
      }
  })
  .catch(function(error) {
      console.log(error)
      if(method === "POST"){
        show_alert("An error occurred while saving data!", "error");
      }
      else{
        show_alert("An error occurred while updating data!", "error");
      }
   });
}

function reset_form(data){
  fields_used.forEach(
    (element) => {
      document.getElementById(element).value = '';
    }           
  );
  document.getElementById("id_to_update").value = '';
  $("#savebutton").text("Save");
  $("#cancelbutton").text("Clear");
  $("#form_title").text(capitalize_first_letter(form_title)+" creation");
}

function capitalize_first_letter(word){
  return word.charAt(0).toUpperCase()+word.slice(1)
}

const retrieve_data_and_fill_form = (data) => {
  if("data" in data){
    content = data.data;

    fields_used.forEach(
      (element) => {
        document.getElementById(element).value = content[element];
      }           
    );
    document.getElementById("id_to_update").value = content["id"];
    $("#savebutton").text("Update");
    $("#cancelbutton").text("Cancel");
    $("#form_title").text(capitalize_first_letter(form_title)+" update");
  }
  else{
    if("message" in data){
      show_alert(data.message, "error");
    }
    else{
      show_alert("An error occurred while executing the request!", "error");
    }
  }
}
