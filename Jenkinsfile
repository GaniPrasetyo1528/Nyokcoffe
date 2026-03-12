def runInPhp84(String commands) {
    docker.image('php:8.4-cli').inside('--entrypoint="" -u root') {
        sh """
            set -e

            apt-get update
            apt-get install -y \
                git \
                unzip \
                curl \
                zip \
                libzip-dev \
                libsqlite3-dev \
                libonig-dev \
                libxml2-dev \
                openssh-client \
                rsync

            docker-php-ext-install zip pdo pdo_sqlite mbstring xml

            curl -sS https://getcomposer.org/installer | php
            mv composer.phar /usr/local/bin/composer

            git config --global --add safe.directory "$WORKSPACE"

            ${commands}
        """
    }
}

node {
    checkout scm

    stage('Build') {
        runInPhp84('''
            php -v
            composer --version
            composer install --no-interaction --prefer-dist --optimize-autoloader
        ''')
    }

    stage('Testing') {
        runInPhp84('''
            if [ ! -f .env ]; then
                if [ -f .env.example ]; then
                    cp .env.example .env
                else
                    printf "APP_ENV=testing\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\nDB_CONNECTION=sqlite\nDB_DATABASE=database/database.sqlite\nCACHE_STORE=array\nQUEUE_CONNECTION=sync\nSESSION_DRIVER=array\n" > .env
                fi
            fi

            mkdir -p database
            touch database/database.sqlite

            if grep -q "^DB_CONNECTION=" .env; then
                sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/" .env
            else
                echo "DB_CONNECTION=sqlite" >> .env
            fi

            if grep -q "^DB_DATABASE=" .env; then
                sed -i "s|^DB_DATABASE=.*|DB_DATABASE=database/database.sqlite|" .env
            else
                echo "DB_DATABASE=database/database.sqlite" >> .env
            fi

            php artisan key:generate --force || true
            php artisan config:clear || true
            php artisan cache:clear || true

            php artisan test
        ''')
    }

    stage('Assets') {
        docker.image('node:22-alpine').inside('--entrypoint="" -u root') {
            sh '''
                set -e
                npm ci --no-audit --no-fund
                npm run build
            '''
        }
    }

    stage('Prepare Production') {
        runInPhp84('''
            composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
        ''')
    }

    stage('Deploy') {
        docker.image('instrumentisto/rsync-ssh:alpine').inside('--entrypoint="" -u root') {
            withCredentials([
                sshUserPrivateKey(
                    credentialsId: 'production',
                    keyFileVariable: 'SSH_KEY',
                    usernameVariable: 'SSH_USER'
                )
            ]) {
                sh '''
                    set -e

                    if [ -z "$PROD_HOST" ]; then
                        echo "ERROR: PROD_HOST is not set"
                        exit 1
                    fi

                    APP_DIR="/home/$SSH_USER/prod.kelasdevops.xyz"

                    mkdir -p ~/.ssh
                    chmod 700 ~/.ssh

                    ssh-keyscan -H "$PROD_HOST" > ~/.ssh/known_hosts
                    chmod 600 "$SSH_KEY" ~/.ssh/known_hosts

                    ssh -i "$SSH_KEY" -o StrictHostKeyChecking=yes "$SSH_USER@$PROD_HOST" "mkdir -p $APP_DIR"

                    rsync -av --delete \
                        -e "ssh -i $SSH_KEY -o StrictHostKeyChecking=yes" \
                        ./ "$SSH_USER@$PROD_HOST:$APP_DIR/" \
                        --exclude=.env \
                        --exclude=storage \
                        --exclude=.git \
                        --exclude=node_modules \
                        --exclude=tests \
                        --exclude=Jenkinsfile

                    ssh -i "$SSH_KEY" -o StrictHostKeyChecking=yes "$SSH_USER@$PROD_HOST" \
                        "sh $APP_DIR/deploy/remote-bootstrap.sh $APP_DIR"
                '''
            }
        }
    }
}