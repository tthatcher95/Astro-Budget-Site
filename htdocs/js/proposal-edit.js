function figureCosts(proposalid) {
  $('#budgetDashboard').html('');
  deleteProjectBudgetDashboard('#budgetDashboard');
  $.getJSON( "index.php?view=proposal-cost-titles-json&proposalid=" + proposalid, function( data ) {
    var items = [];
    $.each( data.data[0], function( key, val ) {
      $.each( val, function( title, mesg ) {
        $(title).html(mesg);
      });
    });
  });
  $('#fbmsTitle').html('FBMS Accounts');

  // Dashboard
  $.getJSON( "index.php?view=proposal-costs-json&proposalid=" + proposalid, function( data ) {
    projectBudgetDashboard('#budgetDashboard',data.data[0].budget);
    projectBudgetTable('#budgetTable', data.data[0].budget);
  });
}

function projectBudgetTable (id, data) {
  var newTable = "<table class='display' width='100%'>";
  newTable += "<tr><th>Year</th>";
  $.each(data, function(i, item) {
    newTable += "<td>" + item.fy + "</td>";
  });

  newTable += "</tr>\n<tr><th>Costs</th>";
  $.each(data, function(i, item) {
    newTable += "<td>$";
    var costs = (item.costs.expenses + item.costs.staffing + item.costs.travel + item.costs.equipment + item.costs.overhead);
    newTable += costs.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    newTable += "</td>";
  });

  newTable += "</tr>\n<tr><th>Funding</th>";
  $.each(data, function(i, item) {
    newTable += "<td>$" + item.funding.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + "</td>";
  });

  newTable += "</tr>\n<tr><th>Total</th>";
  $.each(data, function(i, item) {
    totals = item.funding - (item.costs.expenses + item.costs.staffing + item.costs.travel + item.costs.equipment +
      item.costs.overhead);
    if (totals < 0) { newTable += "<td><font color='firebrick'>$"; }
    else { newTable += "<td><font color='darkolivegreen'>$"; }
    newTable += totals.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    newTable += "</td>";
  });

  newTable += "</tr><tr></table>\n";

  $(id).html(newTable);
}

function loadFundingTable (reload, proposalid) {
  if (reload) {
    $('#fundingTable').dataTable().fnDestroy();
  }

  $('#fundingTableDiv').html("<table id='fundingTable' class='display' cellspacing='0' width='100%'>" +
    "<thead><tr><th>FY</th><th>New Funding</th><th>Carryover</th><th>&nbsp;</th></tr></thead></table>");

  $('#fundingTable').dataTable( {
    "processing": true,
    "serverSide": false,
    "autoWidth": false,
    'ajax': 'index.php?view=funding-list-json&proposalid=' + proposalid,
    'lengthMenu': [[5, 10, 20, -1], [5, 10, 20, 'All']]
  });
}

function loadFbmsTable (reload, proposalid) {
  if (reload) {
    $('#fbmsTable').dataTable().fnDestroy();
  }

  $('#fbmsTableDiv').html("<table id='fbmsTable' class='display' cellspacing='0' width='100%'>" +
    "<thead><tr><th>Account No.</th><th>&nbsp;</th></tr></thead></table>");

  $('#fbmsTable').dataTable( {
    "processing": true,
    "serverSide": false,
    "autoWidth": false,
    'ajax': 'index.php?view=fbms-list-json&proposalid=' + proposalid,
    'lengthMenu': [[5, 10, 20, -1], [5, 10, 20, 'All']]
  } );
}

function loadOverheadTable (reload, proposalid) {
  if (reload) {
    $('#overheadTable').dataTable().fnDestroy();
  }

  $('#overheadTableDiv').html("<table id='overheadTable' class='display' cellspacing='0' width='100%'>" +
    "<thead><tr><th>Rate</th><th>Description</th><th>Fiscal Year</th><th></th></tr></table>");

  $('#overheadTable').dataTable( {
    'processing': true,
    'serverSide': false,
    'autoWidth': false,
    'ajax': 'index.php?view=overhead-list-json&proposalid=' + proposalid,
    'lengthMenu': [[5, 10, 20, -1], [5, 10, 20, 'All']]
  } );
}

function loadTasksTable (reload, proposalid) {
  if (reload) {
    $('#tasksTable').dataTable().fnDestroy();
  }

  $('#tasksTableDiv').html("<table id='tasksTable' class='display' cellspacing='0' width='100%'>" +
    "<thead><tr><th>Task</th><th>Staffing</th><th>Hours</th><th>Est. Cost<br/>(Est Sal + Ben w/ LAF)</th>" +
    "<th>FYs</th><th>&nbsp;</th></tr></thead></table>");
  $('#tasksTable').dataTable( {
    'processing': true,
    'serverSide': false,
    'autoWidth': false,
    'ajax': 'index.php?view=tasks-list-json&proposalid=' + proposalid,
  } );
}

function loadConferencesTable (reload, proposalid) {
  if (reload) {
    $('#conferencesTable').dataTable().fnDestroy();
  }

  $('#conferencesTableDiv').html("<table id='conferencesTable' class='display' cellspace='0' width='100%'>" +
    "<thead><tr><th>Meeting</th><th>Number<br/>Travelers</th><th>Starting</th><th>FY</th>" +
    "<th>Meeting<br/>Days</th><th>Travel<br/>Days</th><th>Airfare</th><th>Ground<br/>Transport</th>" +
    "<th>Registration<br/>and Other</th><th>per diem</th><th>Lodging</th><th>Total</th><th>&nbsp;</th></tr></thead></table>");

  $('#conferencesTable').dataTable( {
    'processing': true,
    'serverSide': false,
    'autoWidth': false,
    'ajax': 'index.php?view=conference-attendee-list-json&proposalid=' + proposalid,
    // 'lengthMenu': [[5, 10, 20, -1], [5, 10, 20, 'All']]
  } );
}

function loadExpensesTable (reload, proposalid) {
  if (reload) {
    $('#expensesTable').dataTable().fnDestroy();
  }

  $('#expensesTableDiv').html("<table id='expensesTable' class='display' cellspace='0' width='100%'>" +
    "<thead><tr><th>Expense</th><th>Type</th><th>Amount</th><th>Fiscal</th><th>&nbsp;</th></tr></thead></table>");

  $('#expensesTable').dataTable( {
    'processing': true,
    'serverSide': false,
    'autoWidth': false,
    'ajax': 'index.php?view=expense-list-json&proposalid=' + proposalid,
    // 'lengthMenu': [[5, 10, 20, -1], [5, 10, 20, 'All']]
  } );
}

function saveProposal() {
  $.post("index.php", $("#proposalForm").serialize());

  $("#warningDiv").html("<p>Updated proposal details</p>");
  $("#warningDiv").show();
}

function editFundingDialog(fundingid, proposalid) {
  $("#editDialog").load(
    "index.php?view=funding-edit&proposalid=" + proposalid + "&fundingid=" + fundingid);

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 300,
    width: 400,
    modal: true,
    buttons: {
      "Save Funding": function () { saveFunding(proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function saveFunding(proposalid) {
  $.post("index.php", $("#fundingForm").serialize())
    .always (function() {

      dialog.dialog("close");
      $("#warningDiv").html("<p>Updated [" + $("#fiscalyear").val() + "]</p>");
      $("#warningDiv").show();

      loadFundingTable(true, proposalid);
    });
}

function deleteFundingDialog(fundingid, proposalid) {
  var fy;

  $.getJSON( "index.php?view=funding-list-json&proposalid=" + proposalid + "&fundingid=" + fundingid, function( data ) {
    var pattern = />(.+)<\/a>/i;
    fy = pattern.exec(data.data[0][0])[1];
    $("#editDialog").html("<html><head><title>Confirm Deletion</title></head>" +
                        "<body><h2>Are you sure you want to delete funding for " + fy + "?</h2></body></html>");
  });

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 200,
    width: 400,
    modal: true,
    buttons: {
      "Delete Funding": function () { deleteFunding(fundingid, proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function deleteFunding(fundingid, proposalid) {
  $.get("index.php?view=funding-delete&fundingid=" + fundingid + "&proposalid=" + proposalid)
    .always (function() {
      dialog.dialog("close");
      $("#warningDiv").html("<p>Deleted [" + fundingid + "]</p>");
      $("#warningDiv").show();

      loadFundingTable(true, proposalid);
    });
}

function editOverheadDialog(overheadid, proposalid) {
  $("#editDialog").load(
    "index.php?view=overhead-edit&proposalid=" + proposalid + "&overheadid=" + overheadid);

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 275,
    width: 525,
    modal: true,
    buttons: {
      "Save Overhead": function () { saveOverhead(proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function saveOverhead(proposalid) {
  $.post("index.php", $("#overheadForm").serialize())
    .always(function(){

      loadOverheadTable(true, proposalid);

      dialog.dialog("close");
      $("#warningDiv").html("<p>Updated [" + $("#overheadid").val() + "] (" + $("#rate").val() + ")</p>");
      $("#warningDiv").show();
    });
}

function deleteOverheadDialog(overheadid, proposalid) {
  var account;

  $.getJSON( "index.php?view=overhead-list-json&proposalid=" + proposalid + "&overheadid=" + overheadid, function( data ) {
    $("#editDialog").html("<html><head><title>Confirm Deletion</title></head>" +
                        "<body><h2>Are you sure you want to delete the overhead rate?</h2></body></html>");
  });

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 200,
    width: 400,
    modal: true,
    buttons: {
      "Delete Overhead Rate": function () { deleteOverhead(overheadid, proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function deleteOverhead(overheadid, proposalid) {
  $.get("index.php?view=overhead-delete&overheadid=" + overheadid + "&proposalid=" + proposalid)
    .always (function() {
      dialog.dialog("close");
      $("#warningDiv").html("<p>Deleted [" + overheadid + "]</p>");
      $("#warningDiv").show();

      loadOverheadTable(true, proposalid);
    });
}

function editFBMSDialog(fbmsid, proposalid) {
  $("#editDialog").load(
    "index.php?view=fbms-edit&proposalid=" + proposalid + "&fbmsid=" + fbmsid);

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 200,
    width: 400,
    modal: true,
    buttons: {
      "Save FBMS": function () { saveFBMS(proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function saveFBMS(proposalid) {
  $.post("index.php", $("#fbmsForm").serialize())
    .always(function(){

      loadFbmsTable(true, proposalid);

      dialog.dialog("close");
      $("#warningDiv").html("<p>Updated [" + $("#fbmsid").val() + "] (" + $("#accountno").val() + ")</p>");
      $("#warningDiv").show();
    });
}

function deleteFBMSDialog(fbmsid, proposalid) {
  var account;

  $.getJSON( "index.php?view=fbms-list-json&proposalid=" + proposalid + "&fbmsid=" + fbmsid, function( data ) {
    var pattern = />(.+)<\/a>/i;
    account = pattern.exec(data.data[0][0])[1];
    $("#editDialog").html("<html><head><title>Confirm Deletion</title></head>" +
                        "<body><h2>Are you sure you want to delete FBMS account " + account + "?</h2></body></html>");
  });

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 200,
    width: 400,
    modal: true,
    buttons: {
      "Delete FBMS": function () { deleteFBMS(fbmsid, proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function deleteFBMS(fbmsid, proposalid) {
  $.get("index.php?view=fbms-delete&fbmsid=" + fbmsid + "&proposalid=" + proposalid)
    .always (function() {
      dialog.dialog("close");
      $("#warningDiv").html("<p>Deleted [" + fbmsid + "]</p>");
      $("#warningDiv").show();

      loadFbmsTable(true, proposalid);
    });
}

function editTaskDialog (taskid, proposalid) {
  if (taskid == 'new') {
    $.post("index.php", $("#newTaskForm").serialize())
      .always( function(data) {
      console.log("Inside post: " + data);
      return (editTaskDialog(data, proposalid));
    });
  }

  $("#editDialog").load(
    "index.php?view=task-edit&proposalid=" + proposalid + "&taskid=" + taskid);

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 600,
    width: 1000,
    modal: true,
    buttons: {
      "Save Task": function () { saveTask(proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function saveTask (proposalid) {
  $.post("index.php", $("#taskForm").serialize())
    .always (function() {

      loadTasksTable(true, proposalid);

      dialog.dialog("close");
      $("#warningDiv").html("<p>Updated " + $("#taskname").val() + "</p>");
      $("#warningDiv").show();

      figureCosts(proposalid);
   });
}

function deleteTaskDialog(taskid, proposalid) {
  var task;

  $.getJSON( "index.php?view=tasks-list-json&proposalid=" + proposalid + "&taskid=" + taskid, function( data ) {
    var pattern = />(.+)<\/a>/i;
    task = pattern.exec(data.data[0][0])[1];
    $("#editDialog").html("<html><head><title>Confirm Deletion</title></head>" +
                        "<body><h2>Are you sure you want to delete task " + task +
                        " and any staffing assigned to it?</h2></body></html>");
  });

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 250,
    width: 400,
    modal: true,
    buttons: {
      "Delete Task": function () { deleteTask(taskid, proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function deleteTask(taskid, proposalid) {
  $.get("index.php?view=task-delete&taskid=" + taskid + "&proposalid=" + proposalid)
    .always (function() {
      dialog.dialog("close");
      $("#warningDiv").html("<p>Deleted [" + taskid + "]</p>");
      $("#warningDiv").show();

      loadTasksTable(true, proposalid);
      figureCosts(proposalid);
    });
}

function editAttendeeDialog(proposalid, travelid) {
  $("#editDialog").load(
    "index.php?view=conference-attendee-edit&proposalid=" + proposalid + "&travelid=" + travelid);

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 500,
    width: 1000,
    modal: true,
    buttons: {
      "Save Travel": function () { saveAttendee(proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function saveAttendee(proposalid) {
  $.post("index.php", $("#conferenceAttendeeForm").serialize())
    .always (function() {

      dialog.dialog("close");
      $("#warningDiv").html("<p>Updated [" + $("#expenseid").val() + "] (" + $("#description").val() + ")</p>");
      $("#warningDiv").show();

      figureCosts(proposalid);

      loadConferencesTable(true, proposalid);
    });
}

function deleteAttendeeDialog(travelid, proposalid) {
  var description;

  $.getJSON("index.php?view=conference-attendee-list-json&proposalid=" + proposalid +
      "&travelid=" + travelid, function( data ) {
    var pattern = />(.+)<\/a>/i;
    description = pattern.exec(data.data[0][0])[1];
    $("#editDialog").html("<html><head><title>Confirm Deletion</title></head>" +
                        "<body><h2>Are you sure you want to delete trip " + description + "?</h2></body></html>");
  });

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 200,
    width: 500,
    modal: true,
    buttons: {
      "Delete Trip": function () { deleteAttendee(travelid, proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}


function deleteAttendee(travelid, proposalid) {
  $.get("index.php?view=conference-attendee-delete&travelid=" + travelid + "&proposalid=" + proposalid)
    .always (function() {
      dialog.dialog("close");
      $("#warningDiv").html("<p>Deleted [" + travelid + "]</p>");
      $("#warningDiv").show();

      loadConferencesTable(true, proposalid);
      figureCosts(proposalid);
    });
}

function loadConferenceRate() {
  $("#meeting").val($("#conferenceiddropdown option:selected").text());
  $.getJSON("index.php?view=conference-rate-list-json&conferenceid=" + $("#conferenceiddropdown").val() +
      "&effectivedate=" + $("#tripstartdate").val(), function( data ) {
    $("#perdiem").val(data.data[0][1]);
    $("#lodging").val(data.data[0][2]);
    $("#registration").val(data.data[0][3]);
    $("#groundtransport").val(data.data[0][4]);
    $("#airfare").val(data.data[0][5]);
    $("#city").val(data.data[0][6]);
    $("#state").val(data.data[0][7]);
    $("#country").val(data.data[0][8]);
  });
}

function editExpenseDialog(expenseid, proposalid) {
  $("#editDialog").load(
    "index.php?view=expense-edit&proposalid=" + proposalid + "&expenseid=" + expenseid);

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 600,
    width: 800,
    modal: true,
    buttons: {
      "Save Expense": function () { saveExpense(proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function saveExpense(proposalid) {
  $.post("index.php", $("#expenseForm").serialize())
    .always (function () {

      dialog.dialog("close");
      $("#warningDiv").html("<p>Updated [" + $("#expenseid").val() + "] (" + $("#description").val() + ")</p>");
      $("#warningDiv").show();

      figureCosts(proposalid);

      loadExpensesTable(true, proposalid);
  });
}

function deleteExpenseDialog(expenseid, proposalid) {
  var description;

  $.getJSON( "index.php?view=expense-list-json&proposalid=" + proposalid + "&expenseid=" + expenseid, function( data ) {
    var pattern = />(.+)<\/a>/i;
    description = pattern.exec(data.data[0][0])[1];
    $("#editDialog").html("<html><head><title>Confirm Deletion</title></head>" +
                        "<body><h2>Are you sure you want to delete expense " + description + "?</h2></body></html>");
  });

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 200,
    width: 400,
    modal: true,
    buttons: {
      "Delete Expense": function () { deleteExpense(expenseid, proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}

function deleteExpense(expenseid, proposalid) {
  $.get("index.php?view=expense-delete&expenseid=" + expenseid + "&proposalid=" + proposalid)
    .always (function() {
      dialog.dialog("close");
      $("#warningDiv").html("<p>Deleted [" + expenseid + "]</p>");
      $("#warningDiv").show();

      loadExpensesTable(true, proposalid);
      figureCosts(proposalid);
    });
}
