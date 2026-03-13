node {
    checkout scm

    stage("Build") {
        docker.image('php:8.2-cli').inside {
            sh 'apt update'
            sh 'apt install -y unzip git'
            sh 'curl -sS https://getcomposer.org/installer | php'
            sh 'php composer.phar install'
        }
    }

    stage("Testing") {
        docker.image('ubuntu').inside {
            sh 'echo "Ini adalah test"'
        }
    }
}