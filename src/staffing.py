from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class Staffing(Base):
  __tablename__ = 'staffing'

  staffingid = Column (Integer, Sequence('staffing_staffingid_seq'), primary_key=True)
  peopleid   = Column (Integer, ForeignKey("people.peopleid"), nullable=False)
  fiscalyear = Column (String)
  q1hours    = Column (Float)
  q2hours    = Column (Float)
  q3hours    = Column (Float)
  q4hours    = Column (Float)
  flexhours  = Column (Float)

  def __repr__(self):
    return "<Staffing(FY='%s', q1='%d', q2='%d', q3='%d', q4='%d', flex='%d')>" % (self.fiscalyear, self.q1hours, self.q2hours,
    self.q3hours, self.q4hours, self.flexhours)
