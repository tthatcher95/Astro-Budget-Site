from sqlalchemy import Column, Integer, String, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class Conferences(Base):
  __tablename__ = 'conferences'

  conferenceid = Column (Integer, Sequence('conferences_conferenceid_seq'), primary_key=True)
  meeting      = Column (String)
  location     = Column (String)

  def __repr__(self):
    return "<Conference(meeting='%s', location='%s')>" % (self.meeting, self.location)
