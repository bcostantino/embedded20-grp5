## client_post_testing.py

import datetime
import requests

url = r'http://ruembdoorlock.000webhostapp.com/client-scripts/post-status.php'
payload = {'door_id':'ce76a42e-4a5f-4d37-91c4-a270f8d25e8d',
           'status':1}

site_response = requests.post(url, data=payload)

print(site_response.content)