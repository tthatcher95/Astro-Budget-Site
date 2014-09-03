import pgdb
import os
import pymongo
import pprint
import sys
import argparse

dbhost = 'dino.wr.usgs.gov'
dbport = '3309'
dbname = 'upc_prd'
dbuser = 'upcmgr'
dbpw   = 'un1pl@c0'

dblimit = 1000000

def main(argv):
  parser = argparse.ArgumentParser(prog='upc2pds')
  parser.add_argument('--archive', help='archive in DI database to select for migration')
  args = parser.parse_args()
  # Connect to UPC database
  upcConn = pgdb.connect (':' + dbname + ':' + dbuser + ':' + dbpw, host=dbhost + ':' + dbport)

  # Define funciton to lookup a UPC record from the URL
  def upcLookup (url):
    print "Looking up " + url
    upcCursor = upcConn.cursor()
    query = "SELECT d.upcid, d.edr_source, i.mission, i.instrument, b.browseurl, t.thumbnailurl, p.processtype, m.targetname "
    query += "FROM datafiles d " 
    query += "JOIN instruments_meta i ON (i.instrumentid=d.instrumentid) " 
    query += "LEFT JOIN targets_meta m ON (d.targetid=m.targetid) " 
    query += "LEFT JOIN (SELECT upcid, value as browseurl FROM meta_string WHERE typeid=347) AS b ON (b.upcid=d.upcid) " 
    query += "LEFT JOIN (SELECT upcid, value as thumbnailurl FROM meta_string WHERE typeid=348) AS t ON (t.upcid=d.upcid) " 
    query += "LEFT JOIN instrument_process p ON (p.instrumentid=i.instrumentid) " 
    query += "WHERE lower(d.edr_source) like lower('%" + url + "') or lower(d.edr_source) like lower('%" + url.replace("data", "missions", 1) + "')"
    upcCursor.execute (query)
  
    result = {}
    row = upcCursor.fetchone()
    while row != None:
      print "Found UPC record: " + str(row[0]) 
      result['upcid']        = row[0]
      result['identifier']   = row[1]  # in case the URL in DI isn't right, we'll set this to the UPC edr_source URL
      result['mission']      = row[2]
      result['instrument']   = row[3]
      if isinstance(row[4], str):
        result['browseurl']    = row[4].replace('$thumbnail_server', 'http://upcimages.wr.usgs.gov')
      if isinstance(row[5], str):
        result['thumbnailurl'] = row[5].replace('$thumbnail_server', 'http://upcimages.wr.usgs.gov')
      result['pow']          = row[6]
      result['target']       = row[7]
      row = upcCursor.fetchone()
    
    upcCursor.close()
    return result
  
  # Import mongo routines and connect to pdsimagingnode DB
  from pymongo import MongoClient
  mclient = MongoClient('localhost', 27017)
  mdb = mclient.pdsimagingnode
  mcollection = mdb.pdsfiles
  # for upcNeeded in mcollection.find({'UPC': True, 'upcid': {'$exists': False}}):
  upcFind = {'UPC': True, 'upcid': {'$exists': False}}
  if args.archive:
    upcFind['archive'] = args.archive
  #upcNeeded = mcollection.find_one({'UPC': True, 'upcid': {'$exists': False}})
  print upcFind
  upcNeeded = mcollection.find_one(upcFind)
  while upcNeeded:
    upcResult = upcLookup(upcNeeded['identifier'])
    if 'upcid' in upcResult:
      # pp = pprint.PrettyPrinter(indent=2)
      # pp.pprint(upcResult)
      mcollection.find_and_modify({"identifier": upcNeeded['identifier']},
                                  {"$set": upcResult})
    else:
      mcollection.find_and_modify({"identifier": upcNeeded['identifier']},
                                  {"$set": {'upcid':-1}})
    upcNeeded = mcollection.find_one(upcFind)
  

if __name__ == "__main__":
  main(sys.argv[1:])
