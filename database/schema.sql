CREATE TABLE people (
  peopleid SERIAL Primary Key,
  name VARCHAR(128),
  username VARCHAR(32)
);

CREATE TABLE salaries (
  salaryid SERIAL Primary Key,
  effectivedate TIMESTAMP,
  payplan VARCHAR(32),
  title VARCHAR(128),
  appttype VARCHAR(8),
  authhours REAL,
  estsalary REAL,
  estbenefits REAL,
  leavecategory REAL,
  laf REAL
);

CREATE TABLE fundingprograms (
  programid SERIAL Primary Key,
  programname VARCHAR(256),
  agency VARCHAR(32),
  pocname VARCHAR(128),
  pocemail VARCHAR(128),
  startdate TIMESTAMP,
  enddate TIMESTAMP
);

CREATE TABLE proposals (
  proposalid SERIAL Primary Key,
  peopleid INTEGER refereneces people(peopleid),
  projectname VARCHAR(256),
  proposalnumber VARCHAR(128),
  awardnumber VARCHAR(128),
  programid INTEGER references fundingprograms(programid),
  perfperiodstart TIMESTAMP,
  perfperiodend TIMESTAMP
);

CREATE TABLE fbmsaccounts (
  fbmsid SERIAL Primary Key,
  accountno VARCHAR(128),
  proposalid INTEGER references proposals(proposalid)
);

CREATE TABLE conferences (
  conferenceid SERIAL Primary Key,
  meeting VARCHAR(256),
  location VARCHAR(128)
);

CREATE TABLE conferencerates (
  conferencerateid SERIAL Primary Key,
  conferenceid INTEGER references conferences(conferenceid),
  effectivedate TIMESTAMP,
  perdiem REAL,
  registration REAL,
  groundtransport REAL,
  airfare REAL
);

CREATE TABLE conferenceattendee (
  conferenceattendeeid SERIAL Primary Key,
  conferenceid INTEGER references conferences(conferenceid),
  proposalid INTEGER references proposals(proposalid),
  peopleid INTEGER references people(peopleid),
  meetingdays INTEGER,
  traveldays INTEGER
);

CREATE TABLE tasks (
  taskid BIGSERIAL Primary Key,
  proposalid INTEGER references proposals(proposalid),
  taskname VARCHAR(1024)
);

CREATE TABLE staffing (
  staffingid BIGSERIAL Primary Key,
  taskid BIGINT references tasks(taskid),
  peopleid INTEGER references people(peopleid),
  fiscalyear VARCHAR(4),
  q1hours REAL,
  q2hours REAL,
  q3hours REAL,
  q4hours REAL
  flexhours REAL
);

CREATE TABLE expensetypes (
  expensetypeid SERIAL Primary Key,
  description VARCHAR(256)
);

CREATE TABLE expenses (
  expenseid BIGSERIAL Primary Key,
  proposalid INTEGER references proposals(proposalid),
  expensetypeid INTEGER references expensetypes(expensetypeid),
  description VARCHAR(256),
  amount REAL,
  fiscalyear VARCHAR(4)
);
