from sqlalchemy import Column, Integer, String, Float, ForeignKey, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class ConferenceAttendee(Base):
  __tablename__ = 'conferenceattendee'

  conferenceattendeeid = Column (Integer, Sequence('conferenceattendee_conferenceattendeeid_seq'), primary_key=True)
  conferenceid         = Column (Integer, ForeignKey("conferences.conferenceid"), nullable=False)
  proposalid           = Column (Integer, ForeignKey("proposals.proposalid"), nullable=False)
  peopleid             = Column (Integer, ForeignKey("people.peopleid"), nullable=False)
  meetindays           = Column (Integer)
  traveldays           = Column (Integer)

  def __repr__(self):
    return "<ConferenceAttendee(meetingdays='%d', traveldays='%d')>" % (self.meetingdays, self.traveldays)
