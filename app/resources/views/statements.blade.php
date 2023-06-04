@extends('parts/layout')

@section('content')

@include('parts/navbar')
<div style="margin-top:2%">
  <div style="margin-bottom:2%">
    <h2 id="form_title">Transaction creation</h2>
  </div>
  <form onsubmit="return false;" method="POST" class="form-group">
    <div>
      <label for="person_id">Person:</label>
      <select onchange="person_changed()"  id="person_id" name="person_id" class="form-control" required>
      </select>
    </div>
    <br>
    <div>
      <label for="account_id">Account:</label>
      <select onchange="account_changed()" id="account_id" name="account_id" class="form-control" required>
      </select>
    </div>
    <br>
    <div>
      <label for="value">Value: </label>
      <input class="form-control" type="number" id="value" name="value" step="any" required>
    </div>
    <br>
    <div>
      <label for="operation">Deposit/Withdraw:</label>
      <select id="operation" name="operation" class="form-control" required>
          <option value="1" selected>Deposit</option>
          <option value="2">Withdraw</option>
      </select>
    </div>
    <input class="form-control" type="hidden" id="id_to_update" name="id_to_update">
    <br>
    <div>
      <button id="savebutton" class="btn btn-success" onClick="start_commit()">Save</button>
    </div>
  </form>
  <br>
  <table id="main_table" class="table table-striped table-hover table-responsive">
    <tr>
      <th>Date</td>
      <th>Value</td>
    </tr>
  </table>
  <h3 id="balance"></h3>
  <h3 id="no_data">No data registered</h3>
</div>
<script>
  const fields_used = ["account_id", "value"]
  const form_title = "transaction";

  const retrieve_data_and_fill_select1 = (data) => {
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
      document.getElementById("person_id").value = "";
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

  const retrieve_data_and_fill_select2 = (data) => {
    if("data" in data){
      content = data.data;
      $('#account_id').empty();

      content.forEach(
        (element) => {
           let value = element["id"];
           let text = element["number"]+" - Balance: R$"+element["balance"];
            $('#account_id').append($('<option>', {
                value: value,
                text: text
            }));
        }
      );
       document.getElementById("account_id").value = "";
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

      statements = content.statements;
      if(statements.length){
        $('#main_table').show();
        $('#balance').show();
        $('#no_data').hide();
        $('#main_table tr:not(:first)').remove();

        statements.forEach(
          (element) => {

            var table = document.getElementById("main_table");
            var row = table.insertRow(-1);

            var cell_date = row.insertCell(0);
            cell_date.innerHTML = element["transaction_date"];

            var cell_value = row.insertCell(1);
            if(element["value"]>0){
              cell_value.innerHTML = element["value"];
            }
            else{
              cell_value.innerHTML = "<p style=\"color:red\">"+element["value"]+"</p>"; 
            }
          }
        );
      }
      else{
        $('#main_table').hide();
        $('#balance').hide();
        $('#no_data').show();
      }

      $('#account_id option:contains("'+content["number"]+'")').text(content["number"]+" - Balance: R$"+content["balance"]);
      $('#balance').html("Balance: R$: "+content["balance"]);
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
          show_alert("Transaction sucessfully executed!", "success");
        }
        else {
          show_alert("Transaction sucessfully executed!", "success");
        }
        let account_id = document.getElementById("account_id").value;

        document.getElementById("value").value = "";

        fetch_single_data("/account/statement", account_id, retrieve_data_and_fill_table);
     }
     else{
        document.getElementById("value").value = "";

        let text = "";
        if("errors" in data){
          for (const [key, value] of Object.entries(data.errors)) {
            text+="<p>"+value[0]+"</p>";
          }
        }
        if(text === "" && "message" in data){
          show_alert(data.message, "error");
        }
        else {
          show_alert("Invalid information!", "error", text);
        }
     }
  }

  function person_changed(){
    fetch_single_data("/person/accounts", document.getElementById("person_id").value, retrieve_data_and_fill_select2);

    $('#main_table').hide();
    $('#balance').hide();
    $('#no_data').show();
  }

  function account_changed(){
    let account_id = document.getElementById("account_id").value;

    fetch_single_data("/account/statement", account_id, retrieve_data_and_fill_table);
  }

  function start_commit(){
    let value = document.getElementById("value").value;
    let operation = document.getElementById("operation").value;

    if(isNaN(parseInt(value))){
      show_alert("The value inserted is not a number!", "error");
      return false;
    }

    if(operation == 2){
      value = value*-1;
    }

    document.getElementById("value").value = value;

    commit_data("/transaction", handle_commit_response);
  }

  fetch_data("/person", retrieve_data_and_fill_select1);
  $('#main_table').hide();
  $('#balance').hide();
  $('#no_data').show();
</script>
@endsection