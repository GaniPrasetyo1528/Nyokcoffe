node {

    stage('Checkout') {
        checkout scm
    }

    stage('Build') {
        docker.image('php:8.2-cli').inside('--entrypoint="" -u root') {
            sh '''
            apt-get update
            apt-get install -y git unzip libzip-dev curl

            docker-php-ext-install zip

            curl -sS https://getcomposer.org/installer | php
            mv composer.phar /usr/local/bin/composer

            composer install --ignore-platform-req=ext-gd
            '''
        }
    }