import mysql.connector

servername = "localhost"
username = "root" 
password = "12345"
dbname = "diagnosphere"

conn = mysql.connector.connect(
    host=servername,
    user=username,
    password=password,
    database=dbname
)

if conn.is_connected():
    pass
else:
    raise Exception(conn.error)

