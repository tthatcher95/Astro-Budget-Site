import sys
import argparse
from pandas.io.excel import ExcelFile

dbpw = '!MgrBudget$'

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


    for i in salaries.index:
      if (salaries['Employee Name'][i] != 'COPY EMPLOYEE NAME HERE'):
        print (salaries['Employee Name'][i])
        print ("\t%s\t%s\n" % (salaries['Pay Plan'][i], salaries['Position Title'][i]))

# CREATE TABLE people (
#  peopleid SERIAL Primary Key,
#  name VARCHAR(128),
#  username VARCHAR(32)
#);

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
