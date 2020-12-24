## client_testing.py
import requests
import time

endpoint = r'http://ruembdoorlock.000webhostapp.com/client-scripts/db-query.php?'

payload = {"response_type":"most_recent",
           "recent_req": 3,
           "door_id":"A",
           "PIN":"330928"}

while True:
    site_request = requests.get(endpoint, params=payload)
    payload["recent_req"] = site_request.json()["recent_req"]
    
    if site_request.json()["command_log"] != "no outstanding commands":
        print(site_request.json())
    
    time.sleep(1)