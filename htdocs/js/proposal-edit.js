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
  });
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
    "<thead><tr><th>Rate</th><th>Description</th><th>Fiscal Year</th></tr></table>");

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
    "<th>Registration</th><th>per diem<br/>w/ Lodging</th><th>Total</th><th>&nbsp;</th></tr></thead></table>");

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
      return (editTaskDialog(data));
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

function editAttendeeDialog(proposalid, conferenceattendeeid) {
  $("#editDialog").load(
    "index.php?view=conference-attendee-edit&proposalid=" + proposalid + 
    "&conferenceattendeeid=" + conferenceattendeeid);

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 600,
    width: 800,
    modal: true,
    buttons: {
      "Save Attendee": function () { saveAttendee(proposalid); },
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

function deleteAttendeeDialog(conferenceattendeeid, proposalid) {
  var description;

  $.getJSON( "index.php?view=conference-attendee-list-json&proposalid=" + proposalid + 
      "&conferenceattendeeid=" + conferenceattendeeid, function( data ) {
    var pattern = />(.+)<\/a>/i;
    description = pattern.exec(data.data[0][0])[1];
    $("#editDialog").html("<html><head><title>Confirm Deletion</title></head>" +
                        "<body><h2>Are you sure you want to delete trip " + description + "?</h2></body></html>");
  });

  dialog = $("#editDialog").dialog({
    autoOpen: false,
    height: 200,
    width: 400,
    modal: true,
    buttons: {
      "Delete Trip": function () { deleteAttendee(conferenceattendeeid, proposalid); },
      Cancel: function () {
        dialog.dialog("close");
      }
    }
  });

  dialog.dialog("open");
}


function deleteAttendee(attendeeid, proposalid) {
  $.get("index.php?view=conference-attendee-delete&conferenceattendeeid=" + attendeeid + "&proposalid=" + proposalid)
    .always (function() {
      dialog.dialog("close");
      $("#warningDiv").html("<p>Deleted [" + attendeeid + "]</p>");
      $("#warningDiv").show();

      loadConferencesTable(true, proposalid);
      figureCosts(proposalid);
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
