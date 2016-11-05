import smtplib
import datetime
from datetime import timedelta
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
import json
import boto3
import botocore

def setupBucket():
    s3 = boto3.resource('s3')
    bucket = s3.Bucket('3219')
    exists = True
    try:
        s3.meta.client.head_bucket(Bucket='3219')
    except botocore.exceptions.ClientError as e:
        # If a client error is thrown, then check that it was a 404 error.
        # If it was a 404 error, then the bucket does not exist.
        error_code = int(e.response['Error']['Code'])
        if error_code == 404:
            exists = False
            return None
    return s3
    
def loadUpdatedData(s3):
    try:
        S3Obj = s3.Object('3219', 'emails.json')
        updatedData = json.load(S3Obj.get()["Body"])
        return updatedData
    except botocore.exceptions.ClientError as e:
        error_code = e.response['Error']['Code']
        if error_code == "404":
            exists = False
            return None
    
def loadExistingData(s3):
    existingData = []
    try:
        S3Obj = s3.Object('3219', 'data.json')
        existingData = json.load(S3Obj.get()["Body"])
    except botocore.exceptions.ClientError as e:
        error_code = e.response['Error']['Code']
        if error_code == "404" or "NoSuchKey":
            exists = False
    
    return existingData, S3Obj

def getExInfo(existingData):
    subList = []
    lastSentList = []
    for mailRecord in existingData:
        subList.append(mailRecord['address'])
        lastSentList.append(mailRecord['lastSent'])
    #subList = ["bihuutue@gmail.com"]
    return subList, lastSentList

def sendEmail(sender, password, updatedData, subList, lastSentList):
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.ehlo()
    server.starttls()
    
    server.login(sender+"@gmail.com", password)
    data = []
    for mailRecord in updatedData:
        time = datetime.datetime.now() + timedelta(hours=8)
        msg = MIMEMultipart()
        msg['From'] = sender
        msg['To'] = "Subscribers"
        msg['Subject'] = "TEST NOTIFICATION"
        address = mailRecord['address']
        sinceLastLogin = ""
        sinceLastSent = "This is the first email! \n"
        username = address.split("@")[0]
        header = "Dear " + username + ", \n \n"
        notification = "This notification was sent at: " + str(time.strftime("%d-%b-%Y %H:%M")) + " SGT! \n"
        signature = "Best Regards, \n"+"THJJ Team"

        
        if address in subList:
            lastLogin = datetime.datetime.strptime(mailRecord['lastSent'], "%d-%b-%Y %H:%M")
            print(time, lastLogin)
            delta = time - lastLogin
            print(delta.seconds/60)
            sinceLastLogin = "It has been "+ str(delta.days)+ " days and "+ str(delta.seconds/3600) + " hours since you last logged in! \n"
            
            lastSent = datetime.datetime.strptime(lastSentList[subList.index(address)], "%d-%b-%Y %H:%M")
            delta = time - lastSent
            sinceLastSent = "It has been "+ str(delta.days)+ " days and "+ str(delta.seconds/3600) + " hours since the last notification! \n \n"

        
        body = header + notification + sinceLastLogin + sinceLastSent + signature
        msg.attach(MIMEText(body, 'plain'))
        #server.sendmail(sender, address, msg.as_string())
        #print(username + msg.as_string())

        newRecord =  {
            'address' : address,
            'lastSent' : time.strftime("%d-%b-%Y %H:%M")
        }
        data.append(newRecord)
    server.quit()
    return data

def lambda_handler(event, context):
    #Set default values for sender:
    sender = "besttemvn"
    #The password should be input as a field of event or set as below
    password = event['password']
    #password = ""
    
    #Setup the bucket
    s3 = setupBucket()
    if s3 == None:
        return "Bucket does not exist"
    
    #Load updated data from S3
    updatedData = loadUpdatedData(s3)
    if updatedData == None:
        return "File emails.json does not exist"
    
    #Load existing data from S3
    existingData, S3Obj = loadExistingData(s3)

    #Get existing inforamtion:
    subList, lastSentList = getExInfo(existingData)
    
    #Send the emails:
    data = sendEmail(sender, password, updatedData, subList, lastSentList)
    print("Email sent!")
    
    #Update existing JSON data to S3
    S3Obj.put(Body= json.dumps(data))
    return "Email sent!"