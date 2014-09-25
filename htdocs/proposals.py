from sqlalchemy import Column, Integer, String, DateTime, ForeignKey, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class Proposals(Base):
  __tablename__ = 'proposals'

  proposalid      = Column (Integer, Sequence('proposals_proposalid_seq'), primary_key=True)
  peopleid        = Column (Integer, ForeignKey("people.peopleid"), nullable=False)
  programid       = Column (Integer, ForeignKey("fundingprograms.programid"), nullable=False)
  projectname     = Column (String)
  proposalnumber  = Column (String)
  awardnumber     = Column (String)
  perfperiodstart = Column (DateTime)
  perfperiodend   = Column (DateTime)

  def __repr__(self):
    return "<Proposals(project='%s', proposalnumber='%s', awardnumber='%s')>" % (self.projectname, self.proposalnumber, self.awardnumber)
