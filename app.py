from flask import Flask, render_template, request, redirect, session, flash
import pickle
import numpy as np
from PIL import Image
from tensorflow.keras.models import load_model
import mysql.connector
import bcrypt
from werkzeug.security import generate_password_hash

app = Flask(__name__)
app.secret_key = 'C1H7LtrwJv6BGyFUXKbjPmznY2m3E4gI'

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

cursor = conn.cursor()

def predict(values, dic):
    if len(values) == 8:
        model = pickle.load(open("models/diabetes.pkl", "rb"))
        values = np.asarray(values)
        return model.predict(values.reshape(1, -1))[0]
    elif len(values) == 26:
        model = pickle.load(open("models/breast_cancer.pkl", "rb"))
        values = np.asarray(values)
        return model.predict(values.reshape(1, -1))[0]

@app.route("/")
def home():
    return render_template("home.html")

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        login_email = request.form['email']
        login_password = request.form['password']

        cursor.execute("SELECT * FROM users WHERE email = %s", (login_email,))
        result = cursor.fetchone()

        if result:
            hashed_password = result[2]

            if bcrypt.checkpw(login_password.encode(), hashed_password.encode()):
                session['email'] = login_email
                return render_template('home.html', logged_in=True)
            else:
                return "Invalid username or password"
        else:
            return "Invalid username or password"

    return render_template('login.html')

@app.route('/logout', methods=['GET', 'POST'])
def logout():
    session.clear()
    return redirect('/home')

@app.route('/signupPage', methods=['GET', 'POST'])
def signup():
    return render_template('signup.html')

@app.route('/signup', methods=['GET', 'POST'])
def register():
    showError = None
    showAlert = False

    if request.method == 'POST':

        email = request.form['email']
        password = request.form['password']
        contact = request.form['contact']
        cpassword = request.form['cpassword']
        hospitalName = request.form['hospitalName']
        hospitalLocation = request.form['hospitalLocation']

        cursor.execute("SELECT * FROM users WHERE email=%s", (email,))
        if cursor.fetchone():
            showError = "Email already exists"
        elif password != cpassword:
            showError = "Passwords do not match"
        else:
            hashedPassword = generate_password_hash(password)
            cursor.execute("INSERT INTO users (email, password, contact, hospital_name, hospital_location) VALUES (%s, %s, %s, %s, %s)",
                           (email, hashedPassword, contact, hospitalName, hospitalLocation))
            conn.commit()
            showAlert = True
            return redirect('/login')

    return render_template('signup.html', showError=showError, showAlert=showAlert)

@app.route('/home')
def homePage():
    if 'email' in session:
        return render_template('home.html')
    else:
        return redirect('/login')

@app.route("/contactus")
def contactUs():
    return render_template("home.html")

@app.route("/aboutus")
def aboutUs():
    return render_template("about-us.html")

@app.route("/diabetes", methods=["GET", "POST"])
def diabetesPage():
    return render_template("diabetes1.html")

@app.route("/cancer", methods=["GET", "POST"])
def cancerPage():
    return render_template("breast_cancer1.html")

@app.route("/malaria", methods=["GET", "POST"])
def malariaPage():
    return render_template("malaria1.html")

@app.route("/heart", methods=["GET", "POST"])
def heartPage():
    return render_template("heart1.html")

@app.route("/diabetesPredict", methods=["GET", "POST"])
def diabetesPredict():
    return render_template("diabetes_predict.html")

@app.route("/predict", methods=["POST", "GET"])
def predictPage():
    try:
        if request.method == "POST":
            to_predict_dict = request.form.to_dict()
            to_predict_list = list(map(float, list(to_predict_dict.values())))
            pred = predict(to_predict_list, to_predict_dict)
    except:
        message = "Please enter valid Data"
        return render_template("home.html", message=message)

    return render_template("predict.html", pred=pred)

@app.route("/malariapredict", methods=["POST", "GET"])
def malariapredictPage():
    if request.method == "POST":
        try:
            if "image" in request.files:
                img = Image.open(request.files["image"])
                img = img.resize((36, 36))
                img = np.asarray(img)
                img = img.reshape((1, 36, 36, 3))
                img = img.astype(np.float64)
                model = load_model("models/malaria.h5")
                pred = np.argmax(model.predict(img)[0])
        except:
            message = "Please upload an Image"
            return render_template("malaria1.html", message=message)
    return render_template("malaria1_predict.html", pred=pred)

if __name__ == "__main__":
    app.run(debug=True)
