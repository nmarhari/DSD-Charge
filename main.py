from flask import Flask, render_template, request, redirect, url_for, session
import re

app = Flask(__name__)

@app.route('/')
def home():
    msg = ''
    return render_template('index.html', msg=msg)

app.run(host='localhost', port=5000)