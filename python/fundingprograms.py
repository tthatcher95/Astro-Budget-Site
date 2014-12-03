from sqlalchemy import Column, Integer, String, Sequence, DateTime
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class FundingPrograms(Base):
  __tablename__ = 'fundingprograms'

  programid   = Column (Integer, Sequence('fundingprograms_programid_seq'), primary_key=True)
  programname = Column (String)
  agency      = Column (String)
  pocname     = Column (String)
  procemail   = Column (String)
  startdate   = Column (DateTime)
  enddate     = Column (DateTime)

  def __repr__(self):
    return "<Funding Program(name='%s', agency='%s')>" % (self.programname, self.agency)
