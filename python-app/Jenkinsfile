pipeline {
    agent any

    parameters {
        choice(name: 'DEPLOY_ENV', choices: ['dev', 'stage', 'prod'])
        string(name: 'EXTERNAL_PORT', defaultValue: '5000')
        string(name: 'INTERNAL_PORT', defaultValue: '5000')
        string(name: 'APP_VERSION', defaultValue: '1.0.0')
        booleanParam(name: 'DEBUG_MODE', defaultValue: false)
    }
    
    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'git@github.com:Feruza-M/docker-apps-cicd.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t flask-app ./python-app'
            }
        }

        stage('Run Container') {
            steps {
                sh 'docker rm -f flask-dev || true'
                sh 'docker run -d -p 5000:5000 --name flask-dev flask-app'
            }
        }

        stage('Health Check') {
            steps {
                sh 'sleep 5'
                sh 'curl -f http://localhost:5000/config'
            }
        }
    }
}

