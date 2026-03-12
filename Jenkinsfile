node {
    checkout scm

    stage("Build Image") {
        sh 'docker build -t laravel-php-composer:8.4 .'
    }

    stage("Install Dependency") {
        docker.image('laravel-php-composer:8.4').inside('-u root') {
            sh 'php -v'
            sh 'composer --version'
            sh 'git config --global --add safe.directory /var/jenkins_home/workspace/laravel-jenkins'
            sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
        }
    }

    stage("Testing") {
        docker.image('laravel-php-composer:8.4').inside('-u root') {
            sh 'php artisan --version || true'
            sh 'echo "Testing pipeline selesai"'
        }
    }
}