from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class Salaries(Base):
  __tablename__ = 'salaries'

  salaryid      = Column (Integer, Sequence('salaries_salaryid_seq'), primary_key=True)
  peopleid      = Column (Integer, ForeignKey("people.peopleid"), nullable=False)
  effectivedata = Column (DateTime)
  payplan       = Column (String)
  title         = Column (String)
  appttype      = Column (String)
  authhours     = Column (Float)
  estsalary     = Column (Float)
  estbenefits   = Column (Float)
  leavecategory = Column (Float)
  laf           = Column (Float)

  def __repr__(self):
    return "<Salaries(peopleid='%d', payplan='%s', title='%s')>" % (self.peopleid, self.payplan, self.title)
