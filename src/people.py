from sqlalchemy import Column, Integer, String, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class People(Base):
  __tablename__ = 'people'

  peopleid = Column (Integer, Sequence('people_peopleid_seq'), primary_key=True)
  name     = Column (String)
  username = Column (String)

  def __repr__(self):
    return "<People(name='%s', username='%s')>" % (self.name, self.username)
