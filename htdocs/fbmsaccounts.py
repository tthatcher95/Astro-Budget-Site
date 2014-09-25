from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class FBMSAccounts(Base):
  __tablename__ = 'fbmsaccounts'

  fbmsid     = Column (Integer, Sequence('fbmsaccounts_fbmsid_seq'), primary_key=True)
  proposalid = Column (Integer, ForeignKey("proposals.proposalid"), nullable=False)
  accountno  = Column (String)

  def __repr__(self):
    return "<FBMS Account(accountno='%s')>" % (self.accountno)
