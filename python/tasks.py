from sqlalchemy import Column, Integer, String, Float, DateTime, ForeignKey, Sequence
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class Tasks(Base):
  __tablename__ = 'tasks'

  taskid     = Column (Integer, Sequence('tasks_taskid_seq'), primary_key=True)
  proposalid = Column (Integer, ForeignKey("proposals.proposalid"), nullable=False)
  taskname   = Column (String)

  def __repr__(self):
    return "<Tasks(taskname='%s')>" % (self.taskname)
