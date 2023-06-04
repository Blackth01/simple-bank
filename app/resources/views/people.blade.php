@extends('parts/layout')

@section('content')

@include('parts/navbar')
<div style="margin-top:2%">
  <div style="margin-bottom:2%">
    <h2 id="form_title">Person creation</h2>
  </div>
  <form onsubmit="return false;" method="POST" class="form-group">
    <div>
      <label for="nome">Name:</label>
      <input class="form-control" value="" type="text" id="name" name="name" required/>
    </div>
    <br>
    <div>
      <label for="cpf">CPF: </label>
      <input oninput="cpf_mask(this)" class="form-control" type="text" id="cpf" name="cpf" required>
    </div>
    <br>
    <div>
      <label for="address">Address: </label>
      <input class="form-control" type="text" id="address" name="address" required>
    </div>
    <br>
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
      <th>Address</td>
      <th>Edit</td>
      <th>Remove</td>
    </tr>
  </table>
  <h3 id="no_data">No data registered yet</h3>
</div>
<script>
  const fields_used = ["name", "cpf","address"];
  const form_title = "person";

  function cpf_mask(i){
     var v = i.value;

     if(isNaN(v[v.length-1])){ // avoids any char that isn't a number
        i.value = v.substring(0, v.length-1);
        return;
     }

     i.setAttribute("maxlength", "14");
     if (v.length == 3 || v.length == 7) i.value += ".";
     if (v.length == 11) i.value += "-";
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
            cell_name.innerHTML = element["name"];

            var cell_cpf = row.insertCell(1);
            cell_cpf.innerHTML = element["cpf"];

            var cell_address = row.insertCell(2);
            cell_address.innerHTML = element["address"];

            var cell_edit = row.insertCell(3);
            cell_edit.innerHTML = "<button class=\"btn btn-primary\" onclick=\"start_update("+element["id"]+")\"><i class=\"fas fa-edit\"></i></button>";

            var cell_delete = row.insertCell(4);
            cell_delete.innerHTML = "<button class=\"btn btn-danger\" onclick=\"start_delete("+element["id"]+")\"><i class=\"far fa-trash-alt\"></i></button>";
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
          show_alert("Person successfully created!", "success");
        }
        else {
          show_alert("Person successfully updated!", "success");
        }
        reset_form();
        fetch_data("/person", retrieve_data_and_fill_table);
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
        show_alert("Person sucessfully deleted!", "success")
        fetch_data("/person", retrieve_data_and_fill_table);
     }
     else{
        if("message" in data){
          show_alert(data.message, "error");
        }
        else{
          show_alert("An error occurred while removing the person!", "error"); 
        }
     }    
  }

  function start_update(id){
    fetch_single_data("/person", id, retrieve_data_and_fill_form);
  }

  function start_delete(id){
    Swal.fire({
      title: 'Do you really want to delete this person?',
      showDenyButton: true,
      showCancelButton: true,
      showConfirmButton: false,
      denyButtonText: `Yes`,
      cancelButtonText: `No`,
    }).then((result) => {
      if (result.isDenied) {
        delete_data("/person", id, handle_delete_response);
      }
    })
    
  }

  function start_commit(){
    //Removes from the CPF the characters which aren't a number
    cpf_value = document.getElementById("cpf").value;
    document.getElementById("cpf").value = cpf_value.replace(/\D/g,'');

    commit_data("/person", handle_commit_response);
  }

  fetch_data("/person", retrieve_data_and_fill_table);
</script>
@endsection