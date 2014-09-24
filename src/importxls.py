import sys
import argparse
import datetime
from tables import *
# from people import *
# from salaries import *
from pandas.io.excel import ExcelFile
from sqlalchemy import *
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

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
        #print (salaries['Employee Name'][i])
        #print ("\t%s\t%s " % (salaries['Pay Plan'][i], salaries['Position Title'][i]))
        session = Session()
        # Check if the user is already in the database
        add_person (salaries, i, session)

def add_person (data, index, session):
  user_count = session.query(People).filter_by(name=data['Employee Name'][index]).count()
  if (user_count == 0):
    person = People(name = data['Employee Name'][index], username = '')
    session.add(person)
    session.commit()
    print ("Added %s to people\n" % (data['Employee Name']))

  user_rec = session.query(People).filter_by(name=data['Employee Name'][index]).first()
  print ("Searching for people ID %d" %(user_rec.peopleid))
  salary_count = session.query(Salaries).filter_by(peopleid=user_rec.peopleid).count()
  if (salary_count == 0):
    salary = Salaries(peopleid = user_rec.peopleid, effectivedate = datetime.datetime.utcnow(), payplan = data['Pay Plan'][index], title = data['Position Title'][index], appttype = data['Appt Type'][index], estsalary = data['Estimated Salary'][index], estbenefits = data['Estimated Benefits'][index], leavecategory = data['Leave Category'][index], laf = data['LAF'][index])
    session.add(salary)
    session.commit()
    print ("Added %s to salaries\n" % (data['Employee Name']))


if __name__ == "__main__":
  main(sys.argv[1:])
