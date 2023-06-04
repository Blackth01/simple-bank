@extends('parts/layout')

@section('content')

@include('parts/navbar')
<div style="margin-top:2%">
  <div style="margin-bottom:2%">
    <h2 id="form_title">Account creation</h2>
  </div>
  <form onsubmit="return false;" method="POST" class="form-group">
    <div>
      <label for="person_id">Person:</label>
      <select id="person_id" name="person_id" class="form-control" required>
      </select>
    </div>
    <br>
    <div>
      <label for="number">Number: </label>
      <input class="form-control" type="number" id="number" name="number" required>
    </div>
    <br>
    <input class="form-control" type="hidden" id="id_to_update" name="id_to_update">
    <div>
      <button id="savebutton" class="btn btn-success" onClick="start_commit()">Save</button>
      <button id="cancelbutton" class="btn btn-danger" onClick="reset_form()">Clear</button>
    </div>
  </form>
  <br>
  <table id="main_table" class="table table-striped table-hover table-responsive">
    <tr>
      <th>Name</td>
      <th>CPF</td>
      <th>Account number</td>
      <th>Edit</td>
      <th>Delete</td>
    </tr>
  </table>
  <h3 id="no_data">No data created yet</h3>
</div>
<script>
  const fields_used = ["person_id", "number"];
  const form_title = "account";

  const retrieve_data_and_fill_select = (data) => {
    if("data" in data){
      content = data.data;
      content.forEach(
        (element) => {
           let value = element["id"];
           let text = element["name"]+" - "+element["cpf"];
            $('#person_id').append($('<option>', {
                value: value,
                text: text
            }));
        }
      );
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

  const retrieve_data_and_fill_table = (data) => {
    if("data" in data){
      content = data.data;
      if(content.length){
        $('#main_table').show();
        $('#no_data').hide();
        $('#main_table tr:not(:first)').remove();

        content.forEach(
          (element) => {

            var table = document.getElementById("main_table");
            var row = table.insertRow(-1);

            var cell_name = row.insertCell(0);
            cell_name.innerHTML = element["person"]["name"];

            var cell_cpf = row.insertCell(1);
            cell_cpf.innerHTML = element["person"]["cpf"];

            var cell_number = row.insertCell(2);
            cell_number.innerHTML = element["number"];

            var cell_edit = row.insertCell(3);
            cell_edit.innerHTML = "<button class=\"btn btn-primary\" onclick=\"start_update("+element["id"]+")\"><i class=\"fas fa-edit\"></i></button>";

            let disable = "";
            if(element["statements_count"] > 0){
              disable = "disabled";
            }

            var cell_delete = row.insertCell(4);
            cell_delete.innerHTML = "<button class=\"btn btn-danger\" "+disable+" onclick=\"start_delete("+element["id"]+")\"><i class=\"far fa-trash-alt\"></i></button>";
          }
        );
      }
      else{
        $('#main_table').hide();
        $('#no_data').show();
      }
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

  const handle_commit_response = (data) => {
     if(data.statusCode === 200){
        if(data.requestMethod === "POST"){
          show_alert("Account successfully created!", "success");
        }
        else {
          show_alert("Account successfully updated!", "success");
        }
        reset_form();
        fetch_data("/account", retrieve_data_and_fill_table);
     }
     else{
        let text = "";
        if("errors" in data){
          for (const [key, value] of Object.entries(data.errors)) {
            text+="<p>"+value[0]+"</p>";
          }
        }
        show_alert("Invalid information!", "error", text);
     }
  }

  const handle_delete_response = (data) => {
     if(data.statusCode === 200){
        show_alert("Account successfully deleted!", "success")
        fetch_data("/account", retrieve_data_and_fill_table);
     }
     else{
        if("message" in data){
          show_alert(data.message, "error");
        }
        else{
          show_alert("An error occurred while deleting the account!", "error"); 
        }
     }
  }

  function start_update(id){
    fetch_single_data("/account", id, retrieve_data_and_fill_form);
  }

  function start_delete(id){
    Swal.fire({
      title: 'Do you really want to delete this account?',
      showDenyButton: true,
      showCancelButton: true,
      showConfirmButton: false,
      denyButtonText: `Yes`,
      cancelButtonText: `No`,
    }).then((result) => {
      if (result.isDenied) {
        delete_data("/account", id, handle_delete_response);
      }
    })
    
  }

  function start_commit(){
    commit_data("/account", handle_commit_response);
  }

  fetch_data("/person", retrieve_data_and_fill_select);
  fetch_data("/account", retrieve_data_and_fill_table);
</script>
@endsection