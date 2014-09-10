import sys
import argparse
from people import *
from pandas.io.excel import ExcelFile
from sqlalchemy import *
from sqlalchemy.orm import sessionmaker

dbconnect = {'server': 'spacely.wr.usgs.gov', 'port'  : '3309', 'user'  : 'budgetmgr', 'pass'  : '!MgrBudget$', 'instance': 'propbudgets_prd'}

def main(argv):
  parser = argparse.ArgumentParser(prog='importxls')
  parser.add_argument('--xls', help='The full pathname of the spread sheet to import')
  args = parser.parse_args()

  if args.xls:
    xl = ExcelFile(args.xls)
    salaries = xl.parse("FY14 Est Salaries")
    # print (salaries.columns)
# Index([u'Employee Name', u'Position Title', u'Pay Plan', u'Appt Type', u'Auth Hours', u'Estimated Salary', u'Estimated Benefits', 
# u'Salary & Benefits', u'Estimated Salary/Hr', u'Estimated Benefit/Hr', u'Salary+Benefit/Hr', u'Leave Category', u'LAF'], dtype='object')

  db = create_engine ('postgresql+psycopg2://' + dbconnect['user'] + ':' + dbconnect['pass'] + '@' + dbconnect['server'] + ':' + 
  dbconnect['port'] + '/' + dbconnect['instance'])

  Session = sessionmaker(bind=db)

  for i in salaries.index:
      if (salaries['Employee Name'][i] != 'COPY EMPLOYEE NAME HERE'):
        print (salaries['Employee Name'][i])
        print ("\t%s\t%s\n" % (salaries['Pay Plan'][i], salaries['Position Title'][i]))
        person = People(name = salaries['Employee Name'][i], username = '')
        session = Session()
        session.add(person)
        session.commit()


#CREATE TABLE salaries (
#  salaryid SERIAL Primary Key,
#  effectivedate TIMESTAMP,
#  payplan VARCHAR(32),
#  title VARCHAR(128),
#  appttype VARCHAR(8),
#  authhours REAL,
#  estsalary REAL,
#  estbenefits REAL,
#  leavecategory REAL,
#  laf REAL
#);

      

if __name__ == "__main__":
  main(sys.argv[1:])
