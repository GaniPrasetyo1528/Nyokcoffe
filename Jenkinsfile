node {
    checkout scm

    stage("Build"){
        docker.image('my-php-composer:8.2').inside('-u root') {
            sh 'php -v'
            sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
        }
    }

    stage("Test") {
        docker.image('ubuntu:22.04').inside('-u root') {
            sh 'echo "Ini adalah test"'
        }
    }
}