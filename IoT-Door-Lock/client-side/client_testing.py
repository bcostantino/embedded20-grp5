## client_testing.py

import requests
import time

endpoint = r'http://www.rowanembdoorlock.tk/db-query.php?'

payload = {"response_type":"most_recent",
           "recent_req": None,
           "door_id":"ce76a42e-4a5f-4d37-91c4-a270f8d25e8d",
           "PIN":"330928"}

while True:
    site_request = requests.get(endpoint, params=payload)
    if site_request.json()["command_log"] != 'no outstanding commands':
        payload["recent_req"] = site_request.json()["command_log"][2]
        print(site_request.json())
    time.sleep(1)