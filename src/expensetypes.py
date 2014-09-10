from sqlalchemy import Column, Integer, String, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class ExpenseTypes(Base):
  __tablename__ = 'expensetypes'

  expensetypeid = Column (Integer, Sequence('expensetypes_expensetypeid_seq'), primary_key=True)
  description   = Column (String)

  def __repr__(self):
    return "<ExpenseTypes(description='%s')>" % (self.description)
