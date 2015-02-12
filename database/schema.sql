DROP SCHEMA public CASCADE;
CREATE SCHEMA public;

CREATE TABLE people (
  peopleid SERIAL Primary Key,
  name VARCHAR(128),
  username VARCHAR(32),
  admin BOOLEAN
);

CREATE TABLE salaries (
  salaryid SERIAL Primary Key,
  peopleid INTEGER,
  effectivedate TIMESTAMP,
  payplan VARCHAR(32),
  title VARCHAR(128),
  appttype VARCHAR(8),
  authhours REAL,
  estsalary REAL,
  estbenefits REAL,
  leavecategory REAL,
  laf REAL,
  CONSTRAINT fk_salaries_people_peopleid FOREIGN KEY (peopleid) REFERENCES people(peopleid)
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
  peopleid INTEGER,
  projectname VARCHAR(256),
  proposalnumber VARCHAR(128),
  awardnumber VARCHAR(128),
  programid INTEGER,
  perfperiodstart TIMESTAMP,
  perfperiodend TIMESTAMP,
  CONSTRAINT fk_proposals_programid FOREIGN KEY (programid) REFERENCES fundingprograms(programid),
  CONSTRAINT fk_proposals_peopleid FOREIGN KEY (peopleid) REFERENCES people(peopleid)
);

CREATE TABLE fbmsaccounts (
  fbmsid SERIAL Primary Key,
  accountno VARCHAR(128),
  proposalid INTEGER,
  CONSTRAINT fk_fbmsaccounts_proposalid FOREIGN KEY (proposalid) REFERENCES proposals(proposalid)
);

CREATE TABLE conferences (
  conferenceid SERIAL Primary Key,
  meeting VARCHAR(256),
  location VARCHAR(128)
);

CREATE TABLE conferencerates (
  conferencerateid SERIAL Primary Key,
  conferenceid INTEGER,
  effectivedate TIMESTAMP,
  perdiem REAL,
  registration REAL,
  groundtransport REAL,
  airfare REAL,
  CONSTRAINT fk_conferencerates_conferenceid FOREIGN KEY (conferenceid) REFERENCES conferences(conferenceid)
);

CREATE TABLE conferenceattendee (
  conferenceattendeeid SERIAL Primary Key,
  conferenceid INTEGER,
  proposalid INTEGER,
  peopleid INTEGER,
  meetingdays INTEGER,
  traveldays INTEGER,
  startdate TIMESTAMP,
  CONSTRAINT fk_conferenceattendee_conferenceid FOREIGN KEY (conferenceid) REFERENCES conferences(conferenceid),
  CONSTRAINT fk_conferenceattendee_peopleid FOREIGN KEY (peopleid) REFERENCES people(peopleid),
  CONSTRAINT fk_conferenceattendee_proposalid FOREIGN KEY (proposalid) REFERENCES proposals(proposalid)
);

CREATE TABLE tasks (
  taskid BIGSERIAL Primary Key,
  proposalid INTEGER,
  taskname VARCHAR(1024),
  CONSTRAINT fk_tasks_proposalid FOREIGN KEY (proposalid) REFERENCES proposals(proposalid)
);

CREATE TABLE staffing (
  staffingid BIGSERIAL Primary Key,
  taskid BIGINT,
  peopleid INTEGER,
  fiscalyear VARCHAR(4),
  q1hours REAL,
  q2hours REAL,
  q3hours REAL,
  q4hours REAL,
  flexhours REAL,
  CONSTRAINT fk_staffing_peopleid FOREIGN KEY (peopleid) REFERENCES people(peopleid),
  CONSTRAINT fk_staffing_peopleid FOREIGN KEY (taskid) REFERENCES tasks(taskid)
);

CREATE TABLE expensetypes (
  expensetypeid SERIAL Primary Key,
  description VARCHAR(256)
);

CREATE TABLE expenses (
  expenseid BIGSERIAL Primary Key,
  proposalid INTEGER,
  expensetypeid INTEGER,
  description VARCHAR(256),
  amount REAL,
  fiscalyear VARCHAR(4),
  CONSTRAINT fk_expenses_proposalid FOREIGN KEY (proposalid) REFERENCES proposals(proposalid),
  CONSTRAINT fk_expenses_expensetypeid FOREIGN KEY (expensetypeid) REFERENCES expensetypes(expensetypeid)
);

CREATE TABLE administrators (
  adminid SERIAL Primary Key,
  peopleid INTEGER,
  CONSTRAINT fk_staffing_peopleid FOREIGN KEY (peopleid) REFERENCES people(peopleid)
);

