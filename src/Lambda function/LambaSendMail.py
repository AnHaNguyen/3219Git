import smtplib
import datetime
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
import json
import os.path
import boto3
import botocore

def lambda_handler(event, context):
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.ehlo()
    server.starttls()
    
    sender = "besttemvn"
    password = event['password']
    #The password should be input as a field of event or set as below
    #password = ""
    
    #setup the bucket
    s3 = boto3.resource('s3')
    bucket = s3.Bucket('testnoti')
    exists = True
    try:
        s3.meta.client.head_bucket(Bucket='testnoti')
    except botocore.exceptions.ClientError as e:
        # If a client error is thrown, then check that it was a 404 error.
        # If it was a 404 error, then the bucket does not exist.
        error_code = int(e.response['Error']['Code'])
        if error_code == 404:
            exists = False
    
    
    server.login(sender+"@gmail.com", password)
    #subList = ["bihuutue@gmail.com", "edge.skywalker@gmail.com"]
    subList = ["bihuutue@gmail.com"]
    
    #Read JSON data from S3
    S3Obj = s3.Object('testnoti', 'data.json')
    data = json.load(S3Obj.get()["Body"])
    
    
    time = datetime.datetime.now().strftime("%d-%b-%Y %H:%M")
    msg = MIMEMultipart()
    msg['From'] = sender
    msg['To'] = "Subscribers"
    msg['Subject'] = "TEST NOTIFICATION"
    bodyPart1 = "A new notification sent at: " + str(time) + "! \n"
    
    for sub in subList:
    	body = bodyPart1
    	
    	sinceLastSent = "This is the first email!"
    	for mailRecord in data:
    		if mailRecord['address'] == sub:
    			lastSent = datetime.datetime.strptime(mailRecord['lastSent'], "%d-%b-%Y %H:%M")
    			delta = datetime.datetime.now() - lastSent
    			sinceLastSent = "It has been "+ str(delta.days)+ " days since you last received a notification!"
    			break
    	body += sinceLastSent
    	msg.attach(MIMEText(body, 'plain'))
    	server.sendmail(sender, sub, msg.as_string() + sinceLastSent)
    server.quit()
    
    print("Email sent!")
    
    data = []
    for sub in subList:
    	mailRecord = {
    		'address' : sub,
    		'lastSent' : time
    	}
    	data.append(mailRecord)
    	
    print(data)
    
    
    #Write JSON data to S3
    S3Obj.put(Body= json.dumps(data))
    return str(lastSent)