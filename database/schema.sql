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
