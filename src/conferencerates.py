from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class ConferenceRates(Base):
  __tablename__ = 'conferencerates'

  conferencerateid = Column (Integer, Sequence('conferencerates_conferencerateid_seq'), primary_key=True)
  conferenceid     = Column (Integer, ForeignKey("conferences.conferenceid"), nullable=False)
  effectivedata    = Column (DateTime)
  perdiem          = Column (Float)
  registration     = Column (Float)
  groundtransport  = Column (Float)
  airfare          = Column (Float)

  def __repr__(self):
    return "<ConferenceRates(perdiem='%d', registration='%s')>" % (self.perdiem, self.registration)
