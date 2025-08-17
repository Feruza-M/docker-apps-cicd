# app.py
import os
from flask import Flask, jsonify

app = Flask(__name__)
app.config['API_VERSION'] = os.getenv('API_VERSION', 'v1')
app.config['DEBUG_MODE'] = os.getenv('DEBUG_MODE', 'false').lower() == 'true'

@app.route('/config')
def config():
    return jsonify({
        'api_version': app.config['API_VERSION'],
        'debug': app.config['DEBUG_MODE'],
        'environment': os.getenv('APP_ENV', 'development')
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=int(os.getenv('PORT', 5000)))